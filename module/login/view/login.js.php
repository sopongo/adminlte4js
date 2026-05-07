console.log("Login module loaded");

// 2. ใช้ Event Delegation แทนการผูก ID ตรงๆ (ปลอดภัยที่สุด)
function initFormEvents() {
    document.querySelector('[id^="email"]').focus(); // ตั้งโฟกัสที่ช่อง Email เมื่อโหลดหน้า
    // ดักจับการ Submit ที่ระดับ document เลย (รองรับทั้งหน้าปกติและ Modal)
    document.addEventListener('submit', function(e) {
        //alert('Form submitted!'); // Debug: เช็คว่าฟังก์ชันนี้ทำงานหรือไม่
        const form = e.target;
        // เช็ค ID ฟอร์ม
        if (form.id === 'frm_login') {
            e.preventDefault(); // หยุดการ Reload หน้าเว็บ
            console.log("JS is working! Form ID:", form.id); // ถ้าเห็นบรรทัดนี้ใน Console แสดงว่า JS ไม่ตายแล้ว
            // Email Validation
            const email = form.querySelector('[id^="email"]')?.value.trim();
            const password = form.querySelector('[id^="password"]')?.value;
                        
            if (!email) return showAlert("<?php echo Language::lang_Login[$_SESSION['lang']]['warning_1']; ?>", 'warning', "<?php echo Language::lang_Login[$_SESSION['lang']]['warning_9']; ?>", form.querySelector('[id^="email"]')?.id);
            if (!validateEmail(email)) return showAlert("<?php echo Language::lang_Login[$_SESSION['lang']]['warning_2']; ?>", 'warning', "<?php echo Language::lang_Login[$_SESSION['lang']]['warning_9']; ?>", form.querySelector('[id^="email"]')?.id);
            if (!password) return showAlert("<?php echo Language::lang_Login[$_SESSION['lang']]['warning_3']; ?>", 'warning', "<?php echo Language::lang_Login[$_SESSION['lang']]['warning_9']; ?>", form.querySelector('[id^="password"]')?.id);

            // ต่อด้วย Logic Fetch
            fetch('fetch.inc.php?p=chklogin', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password }) // ส่งข้อมูลเป็น JSON
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                console.log("Fetch response:", data); // Debug: ดูข้อมูลที่ได้รับจาก Fetch
                if (data.status === 'success') {
                    showSuccess("<?php echo Language::lang_Login[$_SESSION['lang']]['warning_6']; ?>", "<?php echo Language::lang_Login[$_SESSION['lang']]['warning_8']; ?>", false, 2000);
                    // อาจจะทำการ Redirect หรือโหลดเนื้อหาใหม่ที่นี่
                    window.location.href = '#/app/home'; // ตัวอย่างการ Redirect ไปหน้า Dashboard หลัง Login สำเร็จ
                    window.location.reload(); // รีโหลดหน้าเพื่อให้แสดงเนื้อหาที่เปลี่ยนไปหลังจากล็อกอินสำเร็จ
                } else {
                    showAlert(data.message || "<?php echo Language::lang_Login[$_SESSION['lang']]['warning_4']; ?>", 'error', "<?php echo Language::lang_Login[$_SESSION['lang']]['warning_7']; ?>");
                }
            })
            .catch(error => {
                console.error("Fetch error:", error);
                // กรณี Error ให้ลบ Overlay ออกเพื่อให้เห็นปุ่มเดิม หรือแสดง Alert
                const overlay = document.querySelector('.loading-overlay');
                if (overlay) overlay.remove();
                showAlert("<?php echo Language::lang_Login[$_SESSION['lang']]['warning_5']; ?>", 'error', "<?php echo Language::lang_Login[$_SESSION['lang']]['warning_7']; ?>");
            });
        }
    });
}
initFormEvents(); // เรียกใช้ฟังก์ชันเพื่อผูก Event