
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

