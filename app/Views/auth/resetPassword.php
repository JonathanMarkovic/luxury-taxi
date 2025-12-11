<?php

use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;

$page_title = $data['title'];
ViewHelper::loadCustomerLogin($page_title, 'resetpassword');
FlashMessage::render();
?>

<div class="login-container">
    <div class="card">
        <!-- reset password -->
        <div class="card-header">
            <h3><?= hs(trans('reset.title')) ?></h3>
            <p style="color:black; font-size:18px; padding-top:20px;">
               <?= hs(trans('reset.desc')) ?>
            </p>
        </div>

        <div class="card-body">
            <form method="POST" action="<?= APP_USER_URL ?>/changePassword">

                <!-- new password -->
                <div class="mb-3-1" style="padding-bottom: 20px;">
                    <label class="field-name" style="color: black; font-weight: bold;  padding-bottom: 8px;"><?= hs(trans('reset.new')) ?></label>
                    <input
                        type="password"
                        class="form-control"
                        name="password"
                        placeholder="New Password"
                        required>

                </div>
                <!-- confirm new password -->
                <div class="mb-3-2" style="padding-top: 20px;">
                    <label class="field-name" style="color: black;font-weight: bold;  padding-bottom: 8px;"><?= hs(trans('reset.confirm')) ?></label>
                    <input
                        type="password"
                        class="form-control"
                        name="confirmPassword"
                        placeholder="Confirm New Password"
                        required>

                </div>
                <div class="mt-3 " style="padding-bottom: 80px; text-align: right;">
                    <a class="authentication-link" href="<?= APP_BASE_URL ?>/login"><?= hs(trans('reset.back')) ?></a>
                </div>

                <div class="d-grid gap-2" style="place-items: center;">
                    <button type="submit" class="authentication-btn">
                        <?= hs(trans('reset.reset')) ?>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<?php ViewHelper::loadFooter(); ?>
