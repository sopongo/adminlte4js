// ฟังก์ชันสำหรับเรียก Swal แจ้งเตือนและ Focus ฟิลด์ที่มีปัญหา
function showAlert(message, icon = null, title = null, fieldId = null) {
    Swal.fire({
        icon: icon,
        title: title,
        text: message,
        confirmButtonColor: '#042761' //ffc107
    }).then(() => {
        if (fieldId) {
            document.getElementById(fieldId).focus();
        }
    });
    return false;
}

function showSuccess(message, title = null, button = false, timer = 1000) {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        showConfirmButton: button, // Hides the "OK" button
        timer: timer, // Automatically closes after specified time
        confirmButtonColor: '#042761' //ffc107
    });
    return false;
}

function validateEmail(email) {
    // ใช้ Regular Expression ในการตรวจสอบรูปแบบของอีเมล   
        return String(email).toLowerCase().match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
}



  // 1. จัดการการแสดงผลปุ่มเมื่อ Scroll
window.addEventListener('scroll', function() {
    const scrollUp = document.getElementById('scrollup');
    if (window.scrollY > 100) {
        scrollUp.classList.add('show');
    } else {
        scrollUp.classList.remove('show');
    }
});

// 2. จัดการการคลิกเพื่อเลื่อนขึ้น (Smooth Scroll)
document.addEventListener('DOMContentLoaded', function () {
    const scrollUpBtn = document.getElementById('scrollup');
    
    // เช็คก่อนว่ามีปุ่มนี้อยู่ในหน้าจริงๆ ไหม เพื่อกัน Error
    if (scrollUpBtn) {
        scrollUpBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});

