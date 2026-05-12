<?php
session_start();

require_once 'include/setting.inc.php';
require_once 'include/error_report.inc.php';
require_once 'include/auth.inc.php'; // รวมไฟล์ auth.inc.php
?>
<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CCMS | Login Page</title>

    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="img/ico/main-ico.png">

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="CCMS | Login Page" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="CCMS" />
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard" />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <link rel="preload" href="css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->
    
    <!--begin::google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link type="text/css" href="css/sarabun_font.css" rel="stylesheet" />
    <!--end:: google Fonts-->
    
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        #three-canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
            width: 100%;
            height: 100%;
        }

    </style>
    
  </head>
  <!--end::Head-->

  <!--begin::Body-->
  <body class="login-page bg-body-login">
    
    <!-- 3D Background Canvas -->
    <canvas id="three-canvas"></canvas>

    <div class="app"></div>

        <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        function initThree() {
            scene = new THREE.Scene();
            camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            renderer = new THREE.WebGLRenderer({ canvas: document.getElementById('three-canvas'), antialias: true, alpha: true });
            renderer.setSize(window.innerWidth, window.innerHeight);

            // Tech Box (Representing Logistic Node)
            const geometry = new THREE.BoxGeometry(2, 2, 2);
            const material = new THREE.MeshPhongMaterial({ 
                color: 0x10408f, 
                wireframe: true,
                transparent: true,
                opacity: 0.5
            });
            box = new THREE.Mesh(geometry, material);
            scene.add(box);

            // Particles (Representing Data Flow)
            const pGeometry = new THREE.BufferGeometry();
            const pCount = 500;
            const coords = new Float32Array(pCount * 3);
            for (let i = 0; i < pCount * 3; i++) {
                coords[i] = (Math.random() - 0.5) * 20;
            }
            pGeometry.setAttribute('position', new THREE.BufferAttribute(coords, 3));
            const pMaterial = new THREE.PointsMaterial({ color: 0x10408f, size: 0.04, transparent: true, opacity: 0.7 });
            particles = new THREE.Points(pGeometry, pMaterial);
            scene.add(particles);

            const ambientLight = new THREE.AmbientLight(0xffffff, 0.2);
            scene.add(ambientLight);
            const pointLight = new THREE.PointLight(0xffffff, 0.3);
            pointLight.position.set(5, 5, 5);
            scene.add(pointLight);
            camera.position.z = 5;
        }

        function animate() {
            requestAnimationFrame(animate);
            box.rotation.x += 0.005;
            box.rotation.y += 0.005;
            particles.rotation.y += 0.001;
            renderer.render(scene, camera);
        }

        window.onload = () => {
            initThree();
            animate();
        };

        window.onresize = () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        };
    </script>

    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous" ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="js/adminlte.js"></script>
    <script src="module/login/control/login.js"></script>
    <script src="js/main.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>