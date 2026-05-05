<?php
// ปิดการแสดง Notice เพื่อให้ผลลัพธ์ JSON ไม่พัง
error_reporting(error_reporting() & ~E_NOTICE);

require_once 'connect_db.inc.php';

class CRUD extends Database
{
    /**
     * ฟังก์ชันสำหรับ Query แบบอิสระ (รองรับทั้ง JOIN, UNION หรือ Subquery)
     * @param string $sql คำสั่ง SQL เต็มรูปแบบ
     * @param array $params พารามิเตอร์สำหรับ Binding
     * @param bool $fetchAll true = เอาทุกแถว, false = เอาแถวเดียว
     * 
     * ตัวอย่างการใช้งาน:
     * $sql = "SELECT SUM(field_double) as total FROM tb_data_ex1 WHERE field_number BETWEEN :s AND :e";
     * $res = $crud->customQuery($sql, [':s' => 100, ':e' => 500]);
     * 
     * $sql = "SELECT id_row, field_json->>'$.status' as status_name FROM tb_data_ex1";
     * $rows = $crud->customQuery($sql, [], true); // ดึงทุกแถว
     */
    public function customQuery($sql, $params = [], $fetchAll = false)
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            
            if ($fetchAll) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            } else {
                return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
            }
        } catch (PDOException $e) {
            error_log("Custom Query Error: " . $e->getMessage());
            return [];
        }
    }


    /**
     * 1. ฟังก์ชันเพิ่มข้อมูล (Insert)
     * @param array $data ['column' => 'value']
     * @param string $tableName
     * @return int LastInsertedId
     */
    public function addRow($data, $tableName)
    {
        if (empty($data)) return false;

        $fields = array_keys($data);
        $placeholders = array_map(fn($f) => ":$f", $fields);

        $sql = "INSERT INTO {$tableName} (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholders) . ")";
        
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            $lastId = $this->conn->lastInsertId();
            $this->conn->commit();
            return $lastId;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) $this->conn->rollback();
            error_log("Insert Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 2. ฟังก์ชันแก้ไขข้อมูล (Update)
     * @param array $data ข้อมูลที่จะแก้ ['col' => 'val']
     * @param string $where เงื่อนไขแบบ String เช่น "id = :id"
     * @param array $whereParams ค่าของตัวแปรในเงื่อนไข เช่น [':id' => 10]
     */
    public function updateRow($data, $where, $whereParams, $tableName)
    {
        if (empty($data)) return false;

        $fields = "";
        foreach ($data as $field => $value) {
            $fields .= "{$field} = :{$field}, ";
        }
        $fields = rtrim($fields, ", ");

        $sql = "UPDATE {$tableName} SET {$fields} WHERE {$where}";
        
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($sql);
            // รวม array ข้อมูล และ array เงื่อนไขเข้าด้วยกันเพื่อ execute
            $stmt->execute(array_merge($data, $whereParams));
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) $this->conn->rollback();
            error_log("Update Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 3. ฟังก์ชันลบข้อมูล (Delete)
     */
    public function deleteRow($tableName, $where, $params)
    {
        $sql = "DELETE FROM {$tableName} WHERE {$where}";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Delete Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 4. ฟังก์ชันดึงข้อมูล "แถวเดียว" (Get Single Row)
     * ใช้บ่อยในหน้า Detail
     */
    public function getRow($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("GetRow Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * 5. ฟังก์ชันดึงข้อมูล "หลายแถว" (Get Multiple Rows)
     * ใช้บ่อยในหน้า List / Table
     */
    public function fetchAll($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("FetchAll Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * 6. ฟังก์ชันนับจำนวนเรคคอร์ด (Count)
     */
    public function countRows($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * 7. ฟังก์ชันจัดการไฟล์อัปโหลด (รองรับทั้ง Single และ Multi)
     */
    public function uploadFile($file, $path, $index = null)
    {
        $tmpName = ($index !== null) ? $file['tmp_name'][$index] : $file['tmp_name'];
        $origName = ($index !== null) ? $file['name'][$index] : $file['name'];

        if (empty($tmpName)) return null;

        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        if (!in_array($ext, ["jpg", "png", "gif", "jpeg"])) return null;

        $newName = md5(time() . $origName) . '.' . $ext;
        $destPath = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($path, '/') . $newName;

        if (move_uploaded_file($tmpName, $destPath)) {
            return $newName;
        }
        return null;
    }
}