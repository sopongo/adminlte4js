window.addEventListener('DOMContentLoaded', () => {
    console.log("DOM fully loaded and parsed");

    // ===================================================
    // 1. ฟังก์ชันค้นหาเมนูใน Sidebar (ทำงานหลังจากเมนูโหลดเสร็จ)
    // ===================================================
    const initSidebarSearch = () => {
        const sidebarSearch = document.getElementById('sidebar-search');
        if (sidebarSearch) {
            sidebarSearch.addEventListener('keyup', function() {
                let searchTerm = this.value.toLowerCase();
                let menuItems = document.querySelectorAll('#navigation > li.nav-item');

                menuItems.forEach(function(item) {
                    let text = item.innerText.toLowerCase();
                    
                    if (text.includes(searchTerm)) {
                        item.style.display = 'block';
                        
                        let parentTree = item.closest('.nav-treeview');
                        if (parentTree) {
                            parentTree.style.display = 'block';
                            parentTree.parentElement.classList.add('menu-open');
                        }
                    } else {
                        item.style.display = 'none';
                        if (!item.querySelector('.nav-treeview')) {
                            item.style.display = 'none';
                        }
                    }
                });

                if (searchTerm === "") {
                    menuItems.forEach(i => i.style.display = 'block');
                }
            });        
        }
    };

    // ===================================================
    // 2. ฟังก์ชันจัดการเมนู Sidebar (Active & Menu Open)
    // ===================================================
    const updateActiveMenu = () => {
        const currentHash = window.location.hash;
        console.log("Current URL Hash:", currentHash);

        if (!currentHash) return;

        // ✅ NEW: ตัด id/segment ท้ายออก ให้เหลือแค่ #/app/<page>
        // ตัวอย่าง: "#/app/fetch-static-page/1234" -> "#/app/fetch-static-page"
        const normalizedHash = (() => {
            const parts = currentHash.split('?')[0].split('#/app/'); // กัน querystring เผื่อมี
            if (parts.length < 2) return currentHash.split('?')[0];

            const afterApp = parts[1];          // "fetch-static-page/1234"
            const page = afterApp.split('/')[0]; // "fetch-static-page"
            return `#/app/${page}`;
        })();

        const navLinks = document.querySelectorAll('#navigation .nav-link');

        // ล้างสถานะ active และ menu-open ของเดิมออกทั้งหมดก่อน
        navLinks.forEach(link => {
            link.classList.remove('active');
            const parentItem = link.closest('.nav-item');

            if (parentItem) {
                parentItem.classList.remove('menu-open');
                // ❗️อย่าบังคับ display none เพราะจะชน animation ของ AdminLTE Treeview
                // const treeview = parentItem.querySelector(':scope > .nav-treeview');
                // if (treeview) treeview.style.display = 'none';
            }
        });

        // ตรวจสอบว่าลิงก์ไหนตรงกับ URL Hash ปัจจุบัน
        navLinks.forEach(link => {
            const hrefAttr = link.getAttribute('href');
            if (!hrefAttr) return;

            const cleanHref = hrefAttr.replace('./', '');

            if (cleanHref === normalizedHash) {
                link.classList.add('active');

                const parentTreeView = link.closest('.nav-treeview');
                if (parentTreeView) {
                    const parentItem = parentTreeView.closest('.nav-item');
                    if (parentItem) {
                        parentItem.classList.add('menu-open');
                        // ลบ inline display:none (ที่ AdminLTE slideUp ทิ้งไว้) เพื่อให้
                        // CSS rule ".menu-open > .nav-treeview { display: block }" ทำงานได้
                        // โดยไม่บังคับ display:block ตรง ๆ ซึ่งจะชน animation ในอนาคต
                        parentTreeView.style.removeProperty('display');

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
    // 3. ฟังก์ชันโหลดเมนู Sidebar ผ่าน fetch
    // ===================================================
    const loadSidebar = () => {
        const mount = document.getElementById('left-sidebar-menu');
        if (!mount) {
            console.warn("Element #left-sidebar-menu not found");
            return;
        }

        const cachedMenu = sessionStorage.getItem('sidebar_html');

        // Re-init AdminLTE Treeview click binding หลัง inject HTML เสร็จ
        // AdminLTE4 bind treeview listener ตอน DOMContentLoaded แต่เมนูของเราถูก inject ทีหลัง
        // จึงต้อง replicate การ bind event เดิมของ AdminLTE ให้กับ element ที่เพิ่ง inject
        const reInitAdminLTESidebar = () => {
            if (!window.adminlte?.Treeview) {
                console.warn('AdminLTE Treeview is not available (check adminlte JS include order).');
                return;
            }

            // หา [data-lte-toggle="treeview"] ที่เพิ่ง inject แล้วผูก click listener ใหม่
            // (เลียนแบบ Data API ของ AdminLTE ที่ปกติจะทำตอน DOMContentLoaded)
            const treeviewContainers = document.querySelectorAll('[data-lte-toggle="treeview"]');
            treeviewContainers.forEach((container) => {
                container.addEventListener('click', (event) => {
                    const target = event.target;
                    const targetItem = target.closest('.nav-item');
                    const targetLink = target.closest('.nav-link');

                    // ป้องกัน navigation สำหรับลิงก์ที่ใช้ href="#" เป็น parent treeview toggle
                    if (target?.getAttribute('href') === '#' || targetLink?.getAttribute('href') === '#') {
                        event.preventDefault();
                    }

                    if (targetItem) {
                        const accordionAttr = container.dataset.accordion;
                        const animationSpeedAttr = container.dataset.animationSpeed;
                        const config = {
                            accordion: accordionAttr === undefined ? true : accordionAttr === 'true',
                            animationSpeed: animationSpeedAttr === undefined ? 300 : Number(animationSpeedAttr)
                        };
                        const treeview = new window.adminlte.Treeview(targetItem, config);
                        treeview.toggle();
                    }
                });
            });
        };

        // เรียกทุกครั้งหลัง sidebar ถูก inject (ทั้ง cached และ fetch ใหม่)
        const afterSidebarInjected = () => {
            reInitAdminLTESidebar(); // ทำให้ animation toggle + submenu treeview กลับมาทำงาน
            updateActiveMenu();
            initSidebarSearch();    // ผูก event ค้นหาเมนูทันที
        };

        if (cachedMenu) {
            mount.innerHTML = cachedMenu;
            afterSidebarInjected();
        } else {
            fetch('fetch_sidebar.inc.php', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
            })
                .then(res => res.text())
                .then(html => {
                    sessionStorage.setItem('sidebar_html', html);
                    mount.innerHTML = html;
                    afterSidebarInjected();
                })
                .catch(err => console.error("Load sidebar error:", err));
        }
    };

    // ===================================================
    // 5. ฟังก์ชันจัดการ Route เปลี่ยนหน้าเพจ (SPA Logic)
    // ===================================================
    const handleRoute = () => {
        const appContainer = document.querySelector('.app');
        const path = window.location.hash.replace('#/app/', '');

        updateActiveMenu();

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

        const actionPage = path.split('/')[0] || 'dashboard';
        const idSegment = path.split('/')[1] || null;
        console.log("Parsed Action Page:", actionPage);

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
                // ✅ DEBUG: ดูให้ชัดว่า server ส่งอะไรมาจริง
                console.log('fetch.inc.php response:', data);

                if (data.redirect) {
                    const targetUrl = `${window.location.pathname}${window.location.search}${data.redirect}`;
                    if (window.location.hash === data.redirect) return;
                    window.history.replaceState(null, '', targetUrl);
                    window.dispatchEvent(new Event('hashchange'));
                    return;
                }

                // 1. เปลี่ยน Title ของหน้าเว็บตามที่ Backend ส่งมา
                data['meta-title'] && (document.title = data['meta-title'] + ' | CMMS');                
                data['title-page'] && (document.querySelector('.title-page').innerText = data['title-page']);
                //data['sub-title-page'] && (document.querySelector('.sub-title-page').innerHTML = data['sub-title-page']);
                document.querySelector('.sub-title-page') && (document.querySelector('.sub-title-page').innerHTML = data['sub-title-page'] || '');

                // 2. แทนที่เนื้อหาหน้าเพจ
                appContainer.innerHTML = data.result_html;

                // 3. ค้นหาและรัน <script> ในเนื้อหาที่เพิ่งฉีดเข้ามา
                const scripts = appContainer.querySelectorAll("script");
                scripts.forEach(oldScript => {
                    const newScript = document.createElement("script");
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
                
                // 4. รัน JavaScript เพิ่มเติมที่ส่งมา (result_js)
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

    // ===================================================
    // 6. การผูก Event และสั่งเริ่มทำงานตอนเปิดเว็บ
    // ===================================================
    window.addEventListener('hashchange', handleRoute);

    loadSidebar();
    handleRoute(); 
});
