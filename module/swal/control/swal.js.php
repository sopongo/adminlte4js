
// ฟังก์ชันแปลงนาทีเป็น HH:mm (สำหรับ noUiSlider)
function formatTime(values) {
    return values.map(value => {
        const hours = Math.floor(value / 60);
        const minutes = Math.floor(value % 60);
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
    });
}

// --- 2. ฟังก์ชันหลักสำหรับตั้งค่า Plugin (Re-initialization) ---
function reInitPlugins(targetContainer = document) {
    // 2.1 ตั้งค่า Flatpickr
    // เช็คก่อนว่ามี Library flatpickr โหลดเข้ามาในหน้าเว็บหรือไม่
    if (typeof flatpickr === 'function') {
        const timeInputs = targetContainer.querySelectorAll("#timeStart, #timeEnd");
        if (timeInputs.length > 0) {
            flatpickr(timeInputs, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        }
    }

    // 2.2 ตั้งค่า noUiSlider (ช่วงเวลาเดินเครื่อง)
    const slider = targetContainer.querySelector('#time-slider');
    if (slider && typeof noUiSlider !== 'undefined') {
        // ถ้ามี Slider เก่าค้างอยู่ให้ทำลายทิ้งก่อน เพื่อสร้างใหม่ (ป้องกัน Error)
        if (slider.noUiSlider) {
            slider.noUiSlider.destroy();
        }

        noUiSlider.create(slider, {
            start: [480, 1020], 
            connect: true,
            step: 5, 
            range: { 'min': 0, 'max': 1439 }
        });

        slider.noUiSlider.on('update', function (values) {
            const formatted = formatTime(values);
            const textEl = targetContainer.querySelector('#timeRangeText');
            const startInput = targetContainer.querySelector('#timeRangeStart');
            const endInput = targetContainer.querySelector('#timeRangeEnd');

            if (textEl) textEl.textContent = `${formatted[0]} - ${formatted[1]}`;
            if (startInput) startInput.value = formatted[0];
            if (endInput) endInput.value = formatted[1];
        });
    }

    // 2.3 ตั้งค่า Badge สำหรับ Range Slider (ระดับการทำงาน 1-10)
    const rangeInput = targetContainer.querySelector('#customRange');
    const rangeValue = targetContainer.querySelector('#rangeValue');
    if (rangeInput && rangeValue) {
        rangeInput.addEventListener('input', function() {
            rangeValue.textContent = this.value;
        });
    }

    // 2.4 สร้างตัวเลข 00 - 23 ใต้ Slider (ถ้ายังไม่มี)
    const timeLabelsContainer = targetContainer.querySelector('#time-labels');
    if (timeLabelsContainer && timeLabelsContainer.innerHTML === "") {
        for (let i = 0; i <= 23; i++) {
            const span = document.createElement('span');
            span.textContent = i.toString().padStart(2, '0');
            span.style.flex = "1";
            span.style.textAlign = "center";
            timeLabelsContainer.appendChild(span);
        }
    }
}
      
      
document.getElementById('swalConfirm').addEventListener('click', function() {
    Swal.fire({
      title: 'หน้าต่างยืนยันการทำงาน',
      text: 'คุณแน่ใจหรือไม่ว่าต้องการดำเนินการต่อ?',
      icon: 'warning',
      iconHtml: '<i class="bi bi-check-lg" style="font-size: 3rem;"></i>',
      showCancelButton: true,
      allowOutsideClick: false, // Disables clicking on the screen to close
      allowEscapeKey: false,   // (Optional) Disables the ESC key for closing
      confirmButtonText: 'ยืนยัน',
      confirmButtonColor: '#082567', // ใส่โค้ดสีที่ต้องการตรงนี้
      cancelButtonText: 'ยกเลิก',
      cancelButtonColor: '#6c757d', // ใส่โค้ดสีที่ต้องการตรงนี้
      // Disable default Swal button styling to use Bootstrap
      /*buttonsStyling: false,
      customClass: {
        confirmButton: 'btn px-4 mx-2',
        cancelButton: 'btn px-4 mx-2'
      }*/
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'ดำเนินการสำเร็จ',
          text: 'คุณได้ยืนยันการทำงานแล้ว',
          icon: 'success',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#082567', // ใส่โค้ดสีที่ต้องการตรงนี้
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'ยกเลิกการดำเนินการ',
          text: 'คุณได้ยกเลิกการทำงานแล้ว',
          icon: 'error',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#082567', // ใส่โค้ดสีที่ต้องการตรงนี้
        });
      }
    });
});

document.getElementById('swal-select-custom').addEventListener('click', function() {
  Swal.fire({
    title: 'Select an Option',
    showCloseButton: true,
    showCancelButton: true,          
    allowOutsideClick: false,
    allowEscapeKey: false,   
    customClass: {
      title: 'custom-swal-title fs-5', // เพิ่มบรรทัดนี้เพื่อกำหนดคลาสสำหรับ title
      input: 'form-control w-auto', // ใช้คลาส form-control ของ Bootstrap สำหรับ input
      confirmButton: 'btn btn-success px-4 mx-2',
      cancelButton: 'btn btn-danger px-4 mx-2'
    },    
    html: `<div class="col-md-12 mb-3 pe-2">
                    <select class="form-select" id="userOption" name="userOption">
                        <option value="">เลือกตัวเลือก...</option>
                        <option value="1">Option 1</option>
                        <option value="2" selected>Option 2</option>
                        <option value="3">Option 3</option>
                        <option value="4">Option 4</option>
                        <option value="5">Option 5</option>
                    </select>
                </div>
            </div>`,
    preConfirm: () => {
      const selectedOption = document.getElementById('userOption').value;
      if (!selectedOption) {
        Swal.showValidationMessage('You need to choose something!');
        return false;
      }
      return selectedOption; // ส่งค่าที่เลือกกลับไปใน result.value
    }
  }).then((result) => {
    if (result.isConfirmed && result.value) {
      Swal.fire({ 
        text: `You selected: ${result.value}`,
        icon: 'success',
        confirmButtonText: 'Great!',
        confirmButtonColor: '#082567' // ใส่โค้ดสีที่ต้องการตรงนี้
      });
    }
  });
});

document.getElementById('swal-time-range').addEventListener('click', function() {
  reInitPlugins(); // เรียกใช้ฟังก์ชันตั้งค่า Plugin หลังจากที่ Swal เปิดขึ้นมาแล้ว เพื่อให้แน่ใจว่า Element ต่าง ๆ ถูกสร้างขึ้นมาใน DOM แล้ว
  Swal.fire({
    title: 'Select a Time Range',
    showCloseButton: true,
    showCancelButton: true,          
    allowOutsideClick: false,
    allowEscapeKey: false,   
    customClass: {
      title: 'custom-swal-title fs-5', // เพิ่มบรรทัดนี้เพื่อกำหนดคลาสสำหรับ title
      input: 'form-control w-auto', // ใช้คลาส form-control ของ Bootstrap สำหรับ input
      confirmButton: 'btn btn-success px-4 mx-2',
      cancelButton: 'btn btn-danger px-4 mx-2'
    },    
    html: `<div class="mb-4">
    <label class="form-label fw-bold d-flex justify-content-between">
        <span><i class="fas fa-sliders-h me-2 text-success"></i>ช่วงเวลาเดินเครื่อง (Multiple Handles)</span>
        <span id="timeRangeText" class="badge bg-success fs-6 shadow-sm">08:00 - 17:00</span>
    </label>
    
<div class="px-3 pb-2">
    <div id="time-slider" class="mt-3"></div>
    <div id="time-labels" class="d-flex justify-content-between mt-2 text-muted" style="font-size: 10px;"></div>
</div>
    
    <input type="hidden" id="timeRangeStart" name="timeRangeStart">
    <input type="hidden" id="timeRangeEnd" name="timeRangeEnd">
</div>
                
            </div>
`,
    preConfirm: () => {
      const timeRange = document.getElementById('timerange').value;
      if (!timeRange) {
        Swal.showValidationMessage('You need to choose something!');
        return false;
      }
      return timeRange; // ส่งค่าที่เลือกกลับไปใน result.value
    }
  }).then((result) => {
    if (result.isConfirmed && result.value) {
      Swal.fire({ 
        text: `You selected: ${result.value}`,
        icon: 'success',
        confirmButtonText: 'Great!',
        confirmButtonColor: '#082567' // ใส่โค้ดสีที่ต้องการตรงนี้
      });
    }
  });
});      

document.getElementById('swal-radio-custom').addEventListener('click', function() {
  Swal.fire({
    title: 'Select an Option',
    showCloseButton: true,
    showCancelButton: true,          
    allowOutsideClick: false,
    allowEscapeKey: false,   
    customClass: {
      title: 'custom-swal-title fs-5', // เพิ่มบรรทัดนี้เพื่อกำหนดคลาสสำหรับ title
      input: 'form-control w-auto', // ใช้คลาส form-control ของ Bootstrap สำหรับ input
      confirmButton: 'btn btn-success px-4 mx-2',
      cancelButton: 'btn btn-danger px-4 mx-2'
    },    
    html: `<!-- ใช้ d-flex flex-column เพื่อจัดให้เรียงลงมาเป็นแนวตั้ง -->
      <p>ใช้ html ในการสร้างตัวเลือกแบบกำหนดเอง</p>
      <div class="radio d-flex flex-column align-items-center gap-3 w-50 px-4">
          <!-- ตัวเลือกที่ 1 -->
          <div class="d-flex align-items-center w-100 max-w-sm p-2 text-start">
              <input type="radio" name="status" id="statusActive" value="active" class="me-3" checked>
              <label for="statusActive" class="radio-label w-100 m-0 cursor-pointer small">ใช้งาน</label>
          </div>
          <!-- ตัวเลือกที่ 2 -->
          <div class="d-flex align-items-center w-100 max-w-sm p-2 text-start">
              <input type="radio" name="status" id="statusInactive" value="inactive" class="me-3">
              <label for="statusInactive" class="radio-label w-100 m-0 cursor-pointer small">ไม่ใช้งาน</label>
          </div>
      </div>`,
    preConfirm: () => {
      const selectedRadio = document.querySelector('input[name="status"]:checked');
      if (!selectedRadio) {
        Swal.showValidationMessage('You need to choose something!');
        return false;
      }
      return selectedRadio.value;
    }
  }).then((result) => {
    if (result.isConfirmed && result.value) {
      Swal.fire({ 
        text: `You selected: ${result.value}`,
        icon: 'success',
        confirmButtonText: 'Great!',
        confirmButtonColor: '#082567' // ใส่โค้ดสีที่ต้องการตรงนี้
      });
    }
  });
});


document.getElementById('swal-range').addEventListener('click', function() {
  Swal.fire({
    title: '<span class="custom-swal-title fs-5 text-danger">Select an Range</span>',
    showCloseButton: true,
    showCancelButton: true,          
    allowOutsideClick: false, // Disables clicking on the screen to close
    allowEscapeKey: false,   // (Optional) Disables the ESC key for closing
    buttonsStyling: false,
    input: 'range',
    inputAttributes: {
      min: 0,
      max: 100,
      step: 1,
      defaultValue: 50, // กำหนดค่าเริ่มต้นของ Range ที่ต้องการ      
      //output: 'rangeValue' // กำหนดชื่อ key สำหรับค่าที่ส่งกลับมาใน result.value 
    },
    // เพิ่มการกำหนดคลาสที่ตัวครอบหน้าต่าง (popup) ตรงนี้
    customClass: {
      popup: 'custom-modern-range', 
      input: 'w-100',
      confirmButton: 'btn btn-blue me-2',
      cancelButton: 'btn btn-danger'      
    },    
    // เพิ่มคำสั่งดักจับตอนเปิด Modal สำเร็จตรงนี้
    didOpen: () => {
      const rangeInput = Swal.getInput(); // ดึง element ของ input range ออกมา
      const outputElement = Swal.getPopup().querySelector('.swal2-range output'); // ค้นหาตำแหน่งแท็ก output
      if (rangeInput && outputElement) {
        outputElement.textContent = rangeInput.value; // ดึงค่าเริ่มต้น (50) ไปใส่ให้แสดงผลทันที
      }
    },
    inputValidator: (value) => {
      if (!value) {
        return 'You need to choose something!'
      }
    }
  }).then((result) => {
    if (result.value) {
      Swal.fire({ 
        text: `You selected: ${result.value}`,
        icon: 'success',
        confirmButtonText: 'Great!',
        confirmButtonColor: '#082567' // ใส่โค้ดสีที่ต้องการตรงนี้
      });
    }
  });
});


      document.getElementById('swal-radio').addEventListener('click', function() {
        Swal.fire({
          title: 'Select an Option',
          showCloseButton: true,
          showCancelButton: true,          
          allowOutsideClick: false, // Disables clicking on the screen to close
          allowEscapeKey: false,   // (Optional) Disables the ESC key for closing
          input: 'radio',
          inputOptions: {
            'choice1': 'Option 1',
            'choice2': 'Option 2',
            'choice3': 'Option 3'
          },
          inputValidator: (value) => {
            if (!value) {
              return 'You need to choose something!'
            }
          }
        }).then((result) => {
          if (result.value) {
            Swal.fire({ text: `You selected: ${result.value}` });
          }
        });
      });

document.getElementById('swalhtml').addEventListener('click', function() {
  Swal.fire({
    title: '<strong>Bootstrap 5 + SweetAlert</strong>',
    icon: 'question',
    html: `
      You can use <b>bold text</b>, 
      <a href="https://getbootstrap.com" class="link-primary">Bootstrap links</a>, 
      and even <span class="badge bg-success">Badges</span> inside this alert.
    `,
    showCloseButton: true,
    showCancelButton: true,
    focusConfirm: false,
    allowOutsideClick: false, // Disables clicking on the screen to close
    allowEscapeKey: false,   // (Optional) Disables the ESC key for closing
    confirmButtonText: '<i class="bi bi-hand-thumbs-up"></i> Great!',
    cancelButtonText: '<i class="bi bi-hand-thumbs-down"></i> Cancel',
    // Use Bootstrap button classes
    buttonsStyling: false,
    customClass: {
      confirmButton: 'btn btn-blue me-2',
      cancelButton: 'btn btn-danger'
    }
  });
});

document.getElementById('swalInput').addEventListener('click', function() {
    Swal.fire({
      title: 'Enter your name',
      input: 'text',
      inputPlaceholder: 'Your name here...',
      allowOutsideClick: false,
      allowEscapeKey: false, // ป้องกันการปิดโดยการกดปุ่ม Escape
      showCancelButton: true,
      confirmButtonText: 'Submit',
      cancelButtonText: 'Cancel',
      buttonsStyling: false,
      customClass: {
        title: 'custom-swal-title fs-5', // เพิ่มบรรทัดนี้เพื่อกำหนดคลาสสำหรับ title
        input: 'form-control w-auto', // ใช้คลาส form-control ของ Bootstrap สำหรับ input
        confirmButton: 'btn btn-success px-4 mx-2',
        cancelButton: 'btn btn-danger px-4 mx-2'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: `Hello, ${result.value}!`,
          text: 'Welcome to SweetAlert2 with Bootstrap 5 styling.',
          icon: 'success',
          timer: 2000,
          timerProgressBar: true, // แสดงแถบเวลาถอยหลัง
          /*showConfirmButton: false,*/
          confirmButtonText: 'Thanks!',
          confirmButtonColor: '#082567' // ใส่โค้ดสีที่ต้องการตรงนี้
        });
      }
    });
});

document.getElementById('swalAlert').addEventListener('click', function() {
  Swal.fire({
    title: 'Hello, AdminLTE!',
    text: 'This is a SweetAlert2 alert.',
    icon: 'success',
    confirmButtonText: 'Cool',
    confirmButtonColor: '#082567', // ใส่โค้ดสีที่ต้องการตรงนี้
  });
});


document.getElementById('showModal').addEventListener('click', function() {
    // (ตัวอย่าง) เมื่อเปิด Modal อาจจะต้องทำการ re-initialize หรือ reset ค่าต่าง ๆ เพื่อให้พร้อมใช้งาน
    const form = document.getElementById('userForm');
    form.reset(); // รีเซ็ตฟอร์มทุกครั้งที่เปิด Modal
  });

document.getElementById('btn-data').addEventListener('click', async function () {
    const form = document.getElementById('userForm');
    
    // 1. ถามเพื่อยืนยัน (Optional)
    const result = await Swal.fire({
        title: 'ยืนยันการบันทึก?',
        text: "คุณตรวจสอบข้อมูลครบถ้วนแล้วใช่หรือไม่",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ใช่, บันทึกเลย',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#082567',
    });

    if (result.isConfirmed) {
        // 2. แสดง Loading State ทันทีที่กดตกลง
        Swal.fire({
            title: 'กำลังบันทึก...',
            text: 'กรุณารอสักครู่',
            allowOutsideClick: false,
            allowEscapeKey: false, // ป้องกันการปิดโดยการกดปุ่ม Escape
            didOpen: () => {
                Swal.showLoading(); // เรียกใช้ Spinner ของ Swal
            }
        });

        // 3. จำลองการส่งข้อมูลไป Server (เช่นใช้ fetch หรือหน่วงเวลา 2 วินาที)
        await new Promise(resolve => setTimeout(resolve, 2000)); 

        // 4. เมื่อทำงานเสร็จ แสดง Success
        await Swal.fire({
            icon: 'success',
            title: 'บันทึกเรียบร้อยแล้ว',
            timer: 1500,
            showConfirmButton: false
        });

        // ปิด Modal และรีเซ็ตฟอร์ม
        form.reset();
        const userModal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
        if (userModal) userModal.hide();
    }
});


