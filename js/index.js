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
    /**
 * ฟังก์ชันสำหรับฉีด Link และ Script เข้าไปใน <head>
 * โดยจะตรวจสอบก่อนว่าไฟล์นั้นๆ เคยถูกโหลดไปแล้วหรือยัง เพื่อป้องกันการโหลดซ้ำ
 */
const injectHeaderAssets = (htmlString) => {
    if (!htmlString) return Promise.resolve();

    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = htmlString;
    const assets = tempDiv.querySelectorAll('link, script');
    const promises = [];

    assets.forEach(asset => {
        let exists = false;
        let newElement;

        // จัดการแท็ก <link> (CSS)
        if (asset.tagName === 'LINK') {
            const href = asset.getAttribute('href');
            exists = !!document.querySelector(`link[href="${href}"]`);
            if (!exists) {
                newElement = document.createElement('link');
                Array.from(asset.attributes).forEach(attr => newElement.setAttribute(attr.name, attr.value));
            }
        } 
        // จัดการแท็ก <script> (JS ภายนอกที่มี src)
        else if (asset.tagName === 'SCRIPT' && asset.getAttribute('src')) {
            const src = asset.getAttribute('src');
            exists = !!document.querySelector(`script[src="${src}"]`);
            if (!exists) {
                newElement = document.createElement('script');
                Array.from(asset.attributes).forEach(attr => newElement.setAttribute(attr.name, attr.value));
                
                // สร้าง Promise เพื่อรอให้ไฟล์ JS โหลดเสร็จก่อน เพื่อให้โค้ดในหน้าหลักเรียกใช้งาน Library ได้ทันที
                const p = new Promise((resolve, reject) => {
                    newElement.onload = resolve;
                    newElement.onerror = reject;
                });
                promises.push(p);
            }
        }

        if (newElement) {
            document.head.appendChild(newElement);
        }
    });

    // คืนค่าเป็น Promise.all เพื่อรอให้ทุกไฟล์โหลดเสร็จสมบูรณ์
    return Promise.all(promises);
};

const handleRoute = () => {
    const appContainer = document.querySelector('.app');
    const path = window.location.hash.replace('#/app/', '');

    updateActiveMenu();

    // แสดง Loading Overlay
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
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ path: actionPage, id: idSegment })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            console.log('fetch.inc.php response:', data);

            // ===================================================
            // เริ่มต้นการจัดการ Assets ใน Header ก่อน
            // ===================================================
            injectHeaderAssets(data.result_header).then(() => {
                
                // 1. เปลี่ยน Title และข้อมูลหัวหน้าเพจ
                data['meta-title'] && (document.title = data['meta-title'] + ' | CMMS');                
                data['title-page'] && (document.querySelector('.title-page').innerText = data['title-page']);
                
                // ตรวจสอบและแสดง Sub Title ถ้าไม่มีให้ว่างไว้
                const subTitleEl = document.querySelector('.sub-title-page');
                if (subTitleEl) subTitleEl.innerHTML = data['sub-title-page'] || '';

                // 2. จัดการ CSS เฉพาะโมดูล (dynamic-module-css)
                let dynamicStyle = document.getElementById('dynamic-module-css');
                if (data.result_css) {
                    if (!dynamicStyle) {
                        dynamicStyle = document.createElement('style');
                        dynamicStyle.id = 'dynamic-module-css';
                        document.head.appendChild(dynamicStyle);
                    }
                    dynamicStyle.textContent = data.result_css;
                } else if (dynamicStyle) {
                    dynamicStyle.textContent = '';
                }

                // 3. แทนที่เนื้อหา HTML หลัก
                appContainer.innerHTML = data.result_html;

                // 4. รันสคริปต์ที่แฝงมากับ HTML (ถ้ามี)
                const scripts = appContainer.querySelectorAll("script");
                scripts.forEach(oldScript => {
                    const newScript = document.createElement("script");
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
                
                // 5. รัน JavaScript เพิ่มเติม (result_js) 
                // ใช้ requestAnimationFrame และ setTimeout เพื่อรอให้ DOM พร้อมใช้งาน 100% ป้องกัน Error
                if (data.result_js) {
                    requestAnimationFrame(() => {
                        setTimeout(() => {
                            const scriptTag = document.createElement('script');
                            scriptTag.text = data.result_js;
                            document.body.appendChild(scriptTag);
                            scriptTag.remove(); 
                        }, 0);
                    });
                }
            });
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
