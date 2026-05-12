<h6>ตัวอย่างการเขียน Web Application แบบ CSR (Client-Side Rendering) - SPA (Single Page Application)</h6>
<div class="row ps-3 pe-3">
    <div class="col-md-6">
        <p>โดยใช้ AdminLTE 4 เป็น Template และใช้ Vanilla JavaScript ในการจัดการ DOM และการทำงานต่าง ๆ ของเว็บไซต์  เพื่อให้เว็บไซต์มีความเร็วและประสิทธิภาพสูงสุด โดยไม่ต้องพึ่งพา Framework หรือ Library ใด ๆ เพิ่มเติม</p>
        <p>
            มีไฟล์ <i>index.php</i> และ <i>fetch.inc.php</i> ที่ใช้ในการจัดการการโหลดหน้าและการดึงข้อมูลจาก Server โดยใช้ Fetch API ในการสื่อสารกับ Server และแสดงผลข้อมูลบนหน้าเว็บไซต์ได้อย่างรวดเร็วและมีประสิทธิภาพ
        </p>

    <p>
            CSR (Client-Side Rendering) คืออะไร?
            ในรูปแบบ Client-Side Rendering คือ การย้ายงานหนักในการ Render หน้าเว็บไปให้ฝั่งเบราว์เซอร์ของผู้ใช้ โดยกระบวนการคร่าว ๆ คือ
            <ul class="list-group">
                <li class="list-group-item">1.เมื่อผู้ใช้เปิดเว็บ เบราว์เซอร์จะส่ง Request ไปยังเซิร์ฟเวอร์</li>
                <li class="list-group-item">2.เซิร์ฟเวอร์ส่ง HTML โครงเปล่าที่มีเพียง Container หลัก (เช่น )</li>
                <li class="list-group-item">3.พร้อมส่งไฟล์ JavaScript ขนาดใหญ่ (Bundle) มาด้วย</li>
                <li class="list-group-item">4.เบราว์เซอร์ดาวน์โหลด JavaScript แล้วเริ่มรันโค้ด</li>
                <li class="list-group-item">5.JavaScript ดึงข้อมูลจาก API / Backend มารวมกัน</li>
                <li class="list-group-item">6.จากนั้นจึง Render หน้าเว็บที่สมบูรณ์ให้ผู้ใช้เห็น</li>
            </ul>            
    </p>        
    <button type="button" class="btn btn-warning mb-2">Show Swal Warning</button>    
</div><!-- /.col-md-6 left -->

<div class="col-md-6 bg-light p-3 rounded">
    <h6>ข้อดีของการใช้ CSR (Client-Side Rendering) - SPA (Single Page Application)</h6>
        <p>1. UX ลื่นไหล รองรับ SPA ได้ดีมาก สำหรับเว็บที่ผู้ใช้มีการคลิกไปมาหลายหน้า หรือมีการโต้ตอบจำนวนมาก เช่น Dashboard, ระบบจัดการ, Web App, Platform ภายในองค์กร</p>
        <ul class="list">
        <li>หลังโหลดครั้งแรกเสร็จ การเปลี่ยนหน้าเพจจะเกิดขึ้นในฝั่ง JavaScript</li>
        <li>ไม่ต้องร้องขอ HTML ใหม่จากเซิร์ฟเวอร์ตลอดเวลา</li>
        <li>ให้ความรู้สึกคล้ายการใช้งานแอปบนมือถือ มากกว่าการใช้เว็บแบบเดิม</li>
        </ul>

        <p>2. ประหยัดทรัพยากรเซิร์ฟเวอร์และค่าโฮสติ้ง เพราะใน CSR ส่วนการ Render ส่วนใหญ่เกิดที่เบราว์เซอร์</p>
        <ul class="list">
        <li>เซิร์ฟเวอร์เน้นส่งข้อมูล JSON ผ่าน API</li>
        <li>สามารถใช้ Static File Hosting และ CDN ช่วยกระจายไฟล์ได้ง่าย</li>
        <li>ลดภาระการต้อง Run แอปเซิร์ฟเวอร์แบบหนัก ๆ ตลอดเวลา</li>
        </ul>
        <p>3. จัดการโค้ดฝั่ง Frontend ได้คล่องตัว เมื่อใช้ JavaScript Framework เช่น React, Vue, Angular</p>
        <ul class="list">
        <li>โครงสร้างโค้ดฝั่ง Client ชัดเจน</li>
        <li>แยกทีม Frontend และ Backend ทำงานคู่ขนานกันได้</li>
        <li>เหมาะกับทีมที่อยาก Iteration ฟีเจอร์ฝั่ง UI/UX เร็ว ๆ</li>
        </ul>
</div><!-- /.col-md-6 right -->    

    <div class="col-lg-12">
        <div class="card-body">
            โดยใน Web Application จะเป็นการแสดงตัวอย่างการทำงานรูปแบบต่างๆ เพื่อนำไปใช้ในโปรเจคจริงได้ ได้แก่
            <ul class="list-group list-group-flush">
                <li class="list-group-item">การใช้ Swal (SweetAlert2) แสดง Popup สวยงามแทน Alert ปกติ</li>
                <li class="list-group-item">การใช้ Modal ของ Bootstrap 5 ในการแสดงข้อมูลหรือฟอร์มต่าง ๆ</li>
                <li class="list-group-item">การใช้ Datatable ในการแสดงตารางข้อมูลที่มีฟีเจอร์ครบถ้วน เช่น การค้นหา การจัดเรียง และการแบ่งหน้า</li>
                <li class="list-group-item">การใช้ Fetch API ในการดึงข้อมูลจาก Server และแสดงผลบนหน้าเว็บแบบไดนามิก</li>
                <li class="list-group-item">การส่งค่าด้วย FormData หรือ JSON ผ่าน Fetch API</li>
                <li class="list-group-item">การอัพโหลดไฟล์, รูปภาพ แบบ Multi File ด้วย uppy.io</li>
                <li class="list-group-item">การจัดการ State และการทำงานแบบ Asynchronous ด้วย Vanilla JavaScript</li>
                <li class="list-group-item">การใช้ Google Map ในการแสดงแผนที่และตำแหน่งต่าง ๆ</li>
                <li class="list-group-item">การใช้ Chart.js ในการแสดงกราฟและข้อมูลเชิงสถิติ</li>
                <li class="list-group-item">การใช้ FullCalendar ในการแสดงปฏิทินและกิจกรรมต่าง ๆ</li>
                <li class="list-group-item">การใช้ Select2 ในการสร้าง Dropdown ที่มีฟีเจอร์การค้นหาและเลือกหลายค่า</li>
                <li class="list-group-item">การใช้ Inputmask ในการจัดรูปแบบข้อมูลที่ผู้ใช้กรอก เช่น เบอร์โทรศัพท์, รหัสไปรษณีย์, วันที่</li>
                <li class="list-group-item">การใช้ Cleave.js ในการจัดรูปแบบข้อมูลที่ผู้ใช้กรอกแบบไดนามิก เช่น การแบ่งหลักตัวเลข, การเพิ่มหน่วยเงิน, การจัดรูปแบบวันที่</li>
                <li class="list-group-item">การใช้ Quill.js ในการสร้าง Rich Text Editor ที่มีฟีเจอร์ครบถ้วน เช่น การจัดรูปแบบข้อความ, การแทรกรูปภาพ, การสร้างลิงก์</li>
                <li class="list-group-item">การใช้ Dropzone.js ในการสร้างพื้นที่สำหรับการอัพโหลดไฟล์แบบ Drag and Drop ที่มีฟีเจอร์การแสดงตัวอย่างไฟล์, การจัดการไฟล์ที่อัพโหลด, การแสดงสถานะการอัพโหลด</li>
                <li class="list-group-item">การอ่านไฟล์ Excel ด้วย SheetJS (xlsx)</li>
                <li class="list-group-item">การใช้ Socket.IO ในการสร้างแอปพลิเคชันแบบ Real-time เช่น แชท, การแจ้งเตือน, การอัพเดตข้อมูลแบบทันที</li>
                <li class="list-group-item">การใช้ Pusher ในการสร้างแอปพลิเคชันแบบ Real-time เช่น การแจ้งเตือน, การอัพเดตข้อมูลแบบทันที</li>
            </ul>
        </div>
    </div><!-- /.col-md-12 -->

</div>    

