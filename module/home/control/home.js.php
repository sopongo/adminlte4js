(document).querySelector('.btn-warning').addEventListener('click', function() {
    Swal.fire({
        title: 'คำเตือน',
        text: 'นี่คือข้อความเตือนจาก Swal!',
        icon: 'warning',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#f39c12',
        allowOutsideClick: false, // อนุญาตให้ปิดโดยการคลิกนอกกล่อง
        allowEscapeKey:true     // ป้องกันการปิดโดยการกดปุ่ม Escape
    });
}); 