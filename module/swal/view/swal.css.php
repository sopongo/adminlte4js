/* =========================================================
   CUSTOM STYLE FOR SPECIFIC MODERN RANGE ONLY (FIX CONFLICT)
   ========================================================= */
@media (min-width: 768px) {
    .min-w-140 {
        min-width: 140px !important; /* ควบคุมให้ปุ่มทุกปุ่มกว้างเท่ากันในจอใหญ่ */
    }
}


/* 1. จัดแต่งตัวกล่องข้อความแสดงตัวเลข (Output Value) */
.custom-modern-range .swal2-range output {
    font-size: 1.25rem !important;
    font-weight: 600 !important;
    color: #082567 !important; /* ใช้สีกรมท่าหลักของคุณ */
    background-color: #f0f4fc !important;
    padding: 0px 8px !important; /* ปรับตามโครงสร้างของคุณ */
    border-radius: 20px !important;
    min-width: 50px !important;
    text-align: center !important;
    margin-left: 15px !important;
}

/* 2. ปรับแต่งแถบเส้นสไลด์ (Track) */
.custom-modern-range .swal2-range input[type="range"] {
    -webkit-appearance: none !important;
    appearance: none !important;
    width: 100% !important;
    height: 6px !important; /* เส้นแถบเล็กลง ดูมินิมอล */
    background: #e2e8f0 !important; /* สีเทาอ่อน */
    border-radius: 8px !important;
    outline: none !important;
    transition: background 0.3s ease !important;
    flex-grow: 1 !important; /* บังคับให้ตัวเส้นสไลด์ขยายตามไปด้วยอย่างสมดุล */
}

/* 3. ปรับแต่งปุ่มกดเลื่อน (Thumb) สำหรับเบราว์เซอร์ Chrome, Safari, Edge, Opera */
.custom-modern-range .swal2-range input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none !important;
    appearance: none !important;
    width: 20px !important;
    height: 20px !important;
    border-radius: 50% !important;
    background: #082567 !important; /* สีปุ่มสีกรมท่า */
    border: 2px solid #ffffff !important; /* ตัดขอบขาวให้ลอยขึ้นมา */
    box-shadow: 0 2px 6px rgba(8, 37, 103, 0.3) !important; /* เงาสีน้ำเงินฟุ้งๆ */
    cursor: pointer !important;
    transition: transform 0.1s ease, background-color 0.2s ease !important;
}

/* เอฟเฟกต์ตอนที่ผู้ใช้กำลังกดค้าง/ลากปุ่มเลื่อน (Webkit) */
.custom-modern-range .swal2-range input[type="range"]::-webkit-slider-thumb:active {
    transform: scale(1.2) !important; /* ปุ่มขยายใหญ่ขึ้นเล็กน้อยเวลาลาก */
    background: #1c3d8a !important; /* เปลี่ยนเป็นสีน้ำเงินสว่าง (สี Hover ปุ่มของคุณ) */
}

/* 4. ปรับแต่งปุ่มกดเลื่อน (Thumb) สำหรับเบราว์เซอร์ Firefox */
.custom-modern-range .swal2-range input[type="range"]::-moz-range-thumb {
    width: 20px !important;
    height: 20px !important;
    border-radius: 50% !important;
    background: #082567 !important;
    border: 2px solid #ffffff !important;
    box-shadow: 0 2px 6px rgba(8, 37, 103, 0.3) !important;
    cursor: pointer !important;
    transition: transform 0.1s ease, background-color 0.2s ease !important;
}

.custom-modern-range .swal2-range input[type="range"]::-moz-range-thumb:active {
    transform: scale(1.2) !important;
    background: #1c3d8a !important;
}

/* 5. จัดการระยะห่างและความกว้างทั้งหมดของตัวโครงสร้าง Range */
.custom-modern-range .swal2-range {
    width: 90% !important; /* บังคับให้กว้าง 90% ของพื้นที่หน้าต่างป็อปอัป ตามโครงสร้างของคุณ */
    box-sizing: border-box !important;
    margin: 20px auto !important;
    padding: 0 10px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
