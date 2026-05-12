<?php
session_start();
require_once 'include/setting.inc.php';
require_once 'include/language.inc.php';
Language::lang_Login;
//echo password_hash(MySetting::Hash.(1234), PASSWORD_DEFAULT);
?>
<div class="login-box w-100 justify-content-center align-items-center">
      <div class="login-logo">
        <strong><?php echo Language::lang_Login[$_SESSION['lang']]['title_0']; ?></strong>
      </div>
      <!-- /.login-logo -->
      <div class="card lh-base">
        <div class="card-body">
          <p class="login-box-msg"><?php echo Language::lang_Login[$_SESSION['lang']]['text_1']; ?></p>
          
          <div class="text-center mb-3"><?php echo Language::lang_Login[$_SESSION['lang']]['text_2']; ?> <a href="?lang=th">TH</a> | <a href="?lang=en">EN</a></div>

          <form id="frm_login" name="frm_login"  autocomplete="off">
            <label for="email" class="col-12 p-0 m-0 pb-2"><?php echo Language::lang_Login[$_SESSION['lang']]['text_3']; ?></label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="email" name="email" placeholder="admin@mail.com" autocomplete="" value="admin@mail.com">
              <div class="input-group-text"><span class="bi bi-envelope-at"></span></div>
            </div>

            <label for="password" class="col-12 p-0 m-0 pb-2"><?php echo Language::lang_Login[$_SESSION['lang']]['text_4']; ?></label>
            <div class="input-group mb-3">
                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">              
              <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
            </div>

            <!--begin::Row-->
            <div class="row">
              <div class="col-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                  <label class="form-check-label small" for="flexCheckDefault"><?php echo Language::lang_Login[$_SESSION['lang']]['text_5']; ?></label>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-6">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-warning"><?php echo Language::lang_Login[$_SESSION['lang']]['text_6']; ?></button>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->
          </form>
          
          <p class="mb-1 mt-3 small"><a href="forgot-password.html"><?php echo Language::lang_Login[$_SESSION['lang']]['text_7']; ?></a></p>
          <p class="mb-0 small"><a href="register.html" class="text-center"><?php echo Language::lang_Login[$_SESSION['lang']]['text_8']; ?></a></p>
        </div>
        <!-- /.login-card-body -->
      </div>
    <span class=" w-100 text-sm-center small mt-3 text-muted justify-content-center align-items-center m-auto">Copyright © 2025 - <?php echo date('Y'); ?> <br />All rights reserved by <?php echo MySetting::Owner; ?>. (Version <?php echo MySetting::Version; ?>)</span>      
    </div>
    <!-- /.login-box -->