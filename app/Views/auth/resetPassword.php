<?php

use App\Helpers\FlashMessage;
use App\Helpers\ViewHelper;

$page_title = $data['title'];
ViewHelper::loadCustomerLogin($page_title, 'resetpassword');
FlashMessage::render();
?>

<div class="login-container">
    <div class="card">
        <!-- reset password -->
        <div class="card-header">
            <h3>Reset Password</h3>
            <p style="color:black; font-size:18px; padding-top:20px;">
                Enter your new password below to reset your account
            </p>
        </div>

        <div class="card-body">
            <form method="POST" action="<?= APP_USER_URL ?>/changePassword">
                <!-- new password -->
                <div class="mb-3-1">
                    <label class="field-name">New Password</label>
                    <input
                        type="password"
                        class="form-control"
                        name="password"
                        placeholder="New Password"
                        required>

                </div>
                <!-- confirm new password -->
                <div class="mb-3-2">
                    <label class="field-name">Confirm New Password</label>
                    <input
                        type="password"
                        class="form-control"
                        name="confirmPassword"
                        placeholder="Confirm New Password"
                        required>

                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="authentication-btn">
                        Reset Password
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<?php ViewHelper::loadFooter(); ?>
