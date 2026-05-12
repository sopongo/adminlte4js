                       
<div class="card-body">
    <div class="col-md-6">
        <p>รูปแบบการแจ้งเตือน โดยใช้ SweetAlert2 โดยด้านล่างจะเป็นตัวอย่างปุ่มต่าง ๆ ที่จะใช้ระบบนี้ บางเคสเมื่อนำไปใช้งานอาจจะต้องทำการ re-initialize เพื่อให้เรียกใช้งานได้ถูกต้อง</p>
        <p>
            คุณสามารถจัดการ CSS ของปุ่มและองค์ประกอบต่าง ๆ ใน SweetAlert2 ได้โดยการใช้ตัวเลือก customClass ในการกำหนดคลาส CSS ที่ต้องการให้กับแต่ละส่วนของ SweetAlert2 เช่น title, input, confirmButton, cancelButton เป็นต้น ซึ่งจะช่วยให้คุณสามารถปรับแต่งสไตล์ของปุ่มและองค์ประกอบต่าง ๆ ให้เข้ากับธีมของเว็บไซต์หรือแอปพลิเคชันของคุณได้อย่างง่ายดาย โดยไม่จำเป็นต้องแก้ไข CSS ของ SweetAlert2 โดยตรง
            <br /><br />
            <span class="text-danger">หมายเหตุ:</span> ดูการปรับแต่งได้ที่ไฟล์ <code>module/swal/control/swal.js.php</code> ในส่วนของการกำหนด customClass สำหรับแต่ละปุ่มและองค์ประกอบต่าง ๆ ของ SweetAlert2
        </p>
    </div><!-- /.col-md-6-->

<ul class="list-group list-group-flush">
    <!-- Item 1 -->
    <li class="list-group-item border-0 px-0 py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
            <button type="button" class="btn btn-warning-2 btn-sm text-nowrap min-w-140" data-bs-toggle="modal" data-bs-target="#userModal" id="showModal">เปิด Modal</button>
            <span class="text-secondary small ms-md-2">ใช้ Button เรียกใช้งาน Swal เพื่อเปิด Modal</span>
        </div>
    </li>
    
    <!-- Item 2 -->
    <li class="list-group-item border-0 px-0 py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
            <button type="button" class="btn btn-danger btn-sm text-nowrap min-w-140" id="swalAlert">Swal Alert</button>
            <span class="text-secondary small ms-md-2">ใช้ Button เรียกใช้งาน Swal เพื่อแสดง Alert</span>
        </div>
    </li>
    
    <!-- Item 3 -->
    <li class="list-group-item border-0 px-0 py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
            <button type="button" class="btn btn-warning btn-sm text-nowrap min-w-140" id="swalConfirm">Swal Confirm</button>
            <span class="text-secondary small ms-md-2">ใช้ Button เรียกใช้งาน Swal เพื่อแสดง Confirm</span>
        </div>
    </li>
    
    <!-- Item 4 -->
    <li class="list-group-item border-0 px-0 py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
            <button type="button" class="btn btn-blue btn-sm text-nowrap min-w-140" id="swalInput">Swal Input</button>
            <span class="text-secondary small ms-md-2">ใช้ Button เรียกใช้งาน Swal เพื่อแสดง Input</span>
        </div>
    </li>
    
    <!-- Item 5 -->
    <li class="list-group-item border-0 px-0 py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
            <button type="button" class="btn btn-dark btn-sm text-nowrap min-w-140" id="swalhtml">Swal HTML</button>
            <span class="text-secondary small ms-md-2">ใช้ Button เรียกใช้งาน Swal เพื่อแสดง HTML</span>
        </div>
    </li>
    
    <!-- Item 6 -->
    <li class="list-group-item border-0 px-0 py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
            <button type="button" class="btn btn-secondary btn-sm text-nowrap min-w-140" id="swal-radio">Swal Radio</button>
            <span class="text-secondary small ms-md-2">ใช้ Button เรียกใช้งาน Swal เพื่อแสดง Radio</span>
        </div>
    </li>
    
    <!-- Item 7 -->
    <li class="list-group-item border-0 px-0 py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
            <button type="button" class="btn btn-info btn-sm text-nowrap min-w-140" id="swal-radio-custom">Swal Radio Custom</button>
            <span class="text-secondary small ms-md-2">ใช้ Button เรียกใช้งาน Swal เพื่อแสดง Radio Custom</span>
        </div>
    </li>
    
    <!-- Item 8 -->
    <li class="list-group-item border-0 px-0 py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
            <button type="button" class="btn btn-primary btn-sm text-nowrap min-w-140" id="swal-range">Swal Range</button>
            <span class="text-secondary small ms-md-2">ใช้ Button เรียกใช้งาน Swal เพื่อแสดง Range เลือก 0-100</span>
        </div>
    </li>
    <!-- Item 9 -->
    <li class="list-group-item border-0 px-0 py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
            <button type="button" class="btn btn-success btn-sm text-nowrap min-w-140" id="swal-select-custom">Swal Select Custom</button>
            <span class="text-secondary small ms-md-2">ใช้ Button เรียกใช้งาน Swal เพื่อแสดง Select แบบ Custom</span>
        </div>
    </li>
    <!-- Item 10 -->
    <li class="list-group-item border-0 px-0 py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
            <button type="button" class="btn btn-dark btn-sm text-nowrap min-w-140" id="swal-time-range">Swal TimeRange</button>
            <span class="text-secondary small ms-md-2">ใช้ Button เรียกใช้งาน Swal เพื่อแสดง TimeRange</span>
        </div>
    </li>
</ul><!-- /.list-group -->
    

</div><!-- /.card-body -->

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-md modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">เพิ่มผู้ใช้</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
        </div>

        <div class="modal-body">
        <form id="userForm">
            <div class="mb-3">
            <label class="form-label">ชื่อ</label>
            <input type="text" class="form-control" name="name" required />
            </div>
            <div class="mb-3">
            <label class="form-label">อีเมล</label>
            <input type="email" class="form-control" name="email" required />
            </div>
        </form>
        </div>

        <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn-data">บันทึก</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ยกเลิก</button>
        </div>
    </div>
    </div>
</div>
    <!-- end Modal -->