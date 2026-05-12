<h6>ตัวอย่างการเขียน Web Application แบบ CSR (Client-Side Rendering) - SPA (Single Page Application)</h6>
<div class="row ps-3 pe-3">
    <div class="col-md-6">
        <p>หากต้องการนำไปใช้งาน ให้ทำการคัดลอกโฟลเดอร์ทั้งหมด ไปวางไว้ที่โปรเจคของคุณ อธิบายโฟลเดอร์และไฟล์ที่เกี่ยวข้องดังนี้</p>
    </div><!-- /.col-md-6-->

<div class="container mt-4">
    <div class="directory-content-wrapper">
        <!-- Header/Title Bar -->
        <div class="d-flex justify-content-between align-items-center p-2 bg-light border rounded-top">
            <div class="d-flex align-items-center">
                <i class="bi bi-folder2-open text-warning me-2"></i>
                <span class="fw-bold">User</span> / <span class="fw-bold ms-1">Repository</span>
            </div>
            <small class="text-muted">Last commit 2 days ago</small>
        </div>

        <!-- Directory Table -->
        <table class="table table-hover table-striped mb-0 rounded-bottom">
            <thead>
                <tr class="table-light">
                    <th scope="col" class="col-2">Name</th>
                    <th scope="col" class="col-8">Description</th>
                    <th scope="col" class="col-2 text-end">Updated</th>
                </tr>
            </thead>
            <tbody>
                <!-- Parent Directory -->
                <tr>
                    <td><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-folder-fill text-warning me-2"></i>..</a></td>
                    <td><span class="text-muted">Go to parent</span></td>
                    <td></td>
                </tr>
                <!-- Folder Row -->
                <tr>
                    <td><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-folder-fill text-warning me-2"></i>css</a></td>
                    <td><span class="text-muted">โฟลเดอร์เก็บไฟล์ CSS หลักของระบบ</span></td>
                    <td class="text-muted text-end">3 hours ago</td>
                </tr>

                <tr>
                    <td><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-folder-fill text-warning me-2"></i>include</a></td>
                    <td><span class="text-muted">โฟลเดอร์เก็บไฟล์ PHP ที่ใช้ร่วมกันในระบบ เช่น การเชื่อมต่อฐานข้อมูล ฟังก์ชันช่วยเหลือต่าง ๆ</span></td>
                    <td class="text-muted text-end">3 hours ago</td>
                </tr>                

                <tr>
                    <td><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-folder-fill text-warning me-2"></i>js</a></td>
                    <td><span class="text-muted">โฟลเดอร์เก็บไฟล์ JavaScript หลักของระบบ</span></td>
                    <td class="text-muted text-end">3 hours ago</td>
                </tr>

                <tr>
                    <td><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-folder-fill text-warning me-2"></i>module</a></td>
                    <td><span class="text-muted">โฟลเดอร์เก็บโมดูลต่าง ๆ ของระบบ โดย จะมีโฟลเดอร์ย่อยสำหรับแต่ละโมดูล และแบ่งการจัดการเป็น 3 ส่วนหลัก ได้แก่ view, controller, และ model</span>
                    </td>
                    <td class="text-muted text-end">3 hours ago</td>
                </tr>

                <!-- Sub-folder (ชั้นที่ 1) -->
                <tr class="bg-light-subtle">
                    <td style="padding-left: 1.5rem;"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-folder-fill text-warning me-2"></i>product</a></td>
                    <td><span class="text-muted">add navigation component</span></td>
                    <td class="text-muted text-end">5 hours ago</td>
                </tr>

                <!-- Sub-folder (ชั้นที่ 2) -->
                <tr class="bg-light-subtle">
                    <td style="padding-left: 2.5rem;"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-folder-fill text-warning me-2"></i>control</a></td>
                    <td><span class="text-muted">add navigation component</span></td>
                    <td class="text-muted text-end">5 hours ago</td>
                </tr>
                <tr>
                    <td style="padding-left: 3.5rem;"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-filetype-jsx text-secondary me-2"></i>product.js.php</a></td>
                    <td><span class="text-muted">fix header styling</span></td>
                    <td class="text-muted text-end">1 hour ago</td>
                </tr>

                <!-- Sub-folder (ชั้นที่ 2) -->
                <tr class="bg-light-subtle">
                    <td style="padding-left: 2.5rem;"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-folder-fill text-warning me-2"></i>model</a></td>
                    <td><span class="text-muted">add navigation component</span></td>
                    <td class="text-muted text-end">5 hours ago</td>
                </tr>
                <tr>
                    <td style="padding-left: 3.5rem;"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-filetype-jsx text-secondary me-2"></i>product.js.php</a></td>
                    <td><span class="text-muted">fix header styling</span></td>
                    <td class="text-muted text-end">1 hour ago</td>
                </tr>

                <!-- Sub-folder (ชั้นที่ 2) -->
                <tr class="bg-light-subtle">
                    <td style="padding-left: 2.5rem;"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-folder-fill text-warning me-2"></i>view</a></td>
                    <td><span class="text-muted">โฟลเดอร์เก็บไฟล์แสดงผลข้อมูล</span></td>
                    <td class="text-muted text-end">5 hours ago</td>
                </tr>
                <tr>
                    <td style="padding-left: 3.5rem;"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-filetype-css text-secondary me-2"></i>product.css.php</a></td>
                    <td><span class="text-muted">ไฟล์ CSS สำหรับหน้า product.ui.php</span></td>
                    <td class="text-muted text-end">1 hour ago</td>
                </tr>
                <tr>
                    <td style="padding-left: 3.5rem;"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-filetype-php text-secondary me-2"></i>product.ui.php</a></td>
                    <td><span class="text-muted">ไฟล์แสดงผลข้อมูล</span></td>
                    <td class="text-muted text-end">1 hour ago</td>
                </tr>

                <!-- Root Folder -->
                <tr>
                    <td class="ps-3"><a href="#" class="text-decoration-none text-dark fw-bold"><i class="bi fs-5 bi-folder2 text-warning me-2"></i>src</a></td>
                    <td><span class="text-muted">refactor: move files to src</span></td>
                    <td class="text-muted text-end">Yesterday</td>
                </tr>
                
                <!-- Sub-folder (ชั้นที่ 1) -->
                <tr class="bg-light-subtle">
                    <td style="padding-left: 1.5rem;"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-folder2 text-warning me-2"></i>components</a></td>
                    <td><span class="text-muted">โฟลเดอร์เก็บคอมโพเนนต์</span></td>
                    <td class="text-muted text-end">5 hours ago</td>
                </tr>
                
                <!-- File inside Sub-folder (ชั้นที่ 2) -->
                <tr>
                    <td style="padding-left: 2.5rem;"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-filetype-jsx text-secondary me-2"></i>Header.jsx</a></td>
                    <td><span class="text-muted">fix header styling</span></td>
                    <td class="text-muted text-end">1 hour ago</td>
                </tr>

                <!-- File inside Root (ชั้นที่ 0) -->
                <tr>
                    <td class="ps-3"><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-file-earmark-code text-secondary me-2"></i>README.md</a></td>
                    <td><span class="text-muted">Initial documentation</span></td>
                    <td class="text-muted text-end">2 days ago</td>
                </tr>

                <!-- File Row -->
                <tr>
                    <td><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-file-earmark-code text-secondary me-2"></i>index.php</a></td>
                    <td><span class="text-muted">ไฟล์หลัก</span></td>
                    <td class="text-muted text-end">2 days ago</td>
                </tr>
                <tr>
                    <td><a href="#" class="text-decoration-none text-dark"><i class="bi fs-5 bi-file-earmark-code text-secondary me-2"></i>fetch.inc.php</a></td>
                    <td><span class="text-muted">ไฟล์ช่วยในการดึงข้อมูล โดยใช้ Fetch API</span></td>
                    <td class="text-muted text-end">2 days ago</td>
                </tr>                

            </tbody>
        </table>
    </div>
</div>


</div>    

