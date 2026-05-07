window.addEventListener('DOMContentLoaded', () => {
    console.log("DOM fully loaded and parsed");

// ===================================================
    // ฟังก์ชันจัดการเมนู Sidebar (Active & Menu Open)
    // ===================================================
    const updateActiveMenu = () => {
        const currentHash = window.location.hash; // เช่น "#/app/company-list"
        console.log("Current URL Hash:", currentHash);
        if (!currentHash) return;

        const navLinks = document.querySelectorAll('.nav-sidebar .nav-link');

        // 1. ล้างสถานะ active และ menu-open ของเดิมออกทั้งหมดก่อน
        navLinks.forEach(link => {
            link.classList.remove('active');
            const parentItem = link.closest('.nav-item');
            if (parentItem) {
                parentItem.classList.remove('menu-open');
            }
        });

        // 2. ตรวจสอบว่าลิงก์ไหนตรงกับ URL Hash ปัจจุบัน
        navLinks.forEach(link => {
            const hrefAttr = link.getAttribute('href');
            if (!hrefAttr) return;

            // ตัด './' ออกจาก href (ถ้ามี) เพื่อให้เหลือเฉพาะ hash ไปเทียบกับ window.location.hash
            const cleanHref = hrefAttr.replace('./', ''); 

            if (cleanHref === currentHash) {
                // ไฮไลท์เมนูย่อยที่ถูกเลือก
                link.classList.add('active');

                // ตรวจสอบว่าเมนูนี้อยู่ภายใต้ Treeview (เมนูแม่) หรือไม่
                const parentTreeView = link.closest('.nav-treeview');
                if (parentTreeView) {
                    const parentItem = parentTreeView.closest('.nav-item');
                    if (parentItem) {
                        // สั่งให้เมนูแม่กางออกค้างไว้
                        parentItem.classList.add('menu-open');

                        // ไฮไลท์ตัวเมนูแม่ด้วย
                        const parentLink = parentItem.querySelector(':scope > .nav-link');
                        if (parentLink) {
                            parentLink.classList.add('active');
                        }
                    }
                }
            }
        });
    };

    // ===================================================
    // 1. แยก Logic การจัดการ Route ออกมาเป็นฟังก์ชันกลาง
    // ===================================================
    const handleRoute = () => {
        const appContainer = document.querySelector('.app');
        const path = window.location.hash.replace('#/app/', '');

        // เรียกใช้งานฟังก์ชันอัปเดตเมนูทุกครั้งที่มีการเปลี่ยนหน้า/รีเฟรช
        updateActiveMenu();

        // --- 1. แสดง Loading Overlay โดยไม่ลบข้อมูลเก่า ---
        if (!document.querySelector('.loading-overlay')) {
            const overlayHTML = `
                <div class="loading-overlay">
                    <div class="spinner-border text-gray" role="status" style="width: 6rem; height: 6rem; border-width: 0.6rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="loading-text">กำลังจัดการข้อมูล...</div>
                </div>
            `;
            appContainer.insertAdjacentHTML('afterbegin', overlayHTML);
        }

        const actionPage = 'login' || 'login'; // กำหนดค่าเริ่มต้นเป็น 'login' หากไม่มี path path || 'login'
        const idSegment = null;
        console.log("Action Page:", actionPage);
        
        if (isNaN(idSegment) && idSegment !== null) {
            Swal.fire('ข้อผิดพลาด', 'ID ที่ระบุไม่ถูกต้อง', 'error');
            console.warn("ID segment is not a number:", idSegment);
        }

        if (actionPage) {
            fetch('fetch.inc.php?p=' + actionPage, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ path: actionPage, id: idSegment })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.redirect) {
                    const targetUrl = `${window.location.pathname}${window.location.search}${data.redirect}`;
                    window.history.replaceState(null, '', targetUrl);
                    window.location.replace(targetUrl);
                    return;
                }

                appContainer.innerHTML = data.result_html;

                // ค้นหา <script> ในก้อนที่เพิ่งฉีดเข้าไปแล้วรันมัน
                const scripts = appContainer.querySelectorAll("script");
                scripts.forEach(oldScript => {
                    const newScript = document.createElement("script");
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });

                // 2. รัน JavaScript ที่ส่งมา
                if (data.result_js) {
                    const scriptTag = document.createElement('script');
                    scriptTag.text = data.result_js;
                    document.body.appendChild(scriptTag);
                    scriptTag.remove(); 
                }
            })
            .catch(error => {
                console.error("Error:", error);
                const overlay = document.querySelector('.loading-overlay');
                if (overlay) overlay.remove();
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถโหลดข้อมูลได้', 'error');
            });
        }
    };

    // 2. เรียกใช้ตอนที่ Hash มีการเปลี่ยนแปลง
    window.addEventListener('hashchange', handleRoute);

    // 3. เรียกใช้ "ทันที" ที่โหลดหน้าเว็บเสร็จ (เพื่อรองรับการกด F5)
    handleRoute(); 
});