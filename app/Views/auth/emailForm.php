<?php

use App\Helpers\ViewHelper;

$page_title = 'Login';
ViewHelper::loadCustomerLogin($page_title, 'login');
?>

<div class="login-container">
    <div class="card">
        <div class="card-header">
            <h3 class="">Welcome Back!</h3>
            <p class="" style="color:black;font-weight:light; font-size:18px; padding-top:20px">Enter your credentials to access your information</p>
        </div>
        <div class="card-body">
            <?= App\Helpers\FlashMessage::render() ?>

            <form method="POST" action="<?= APP_USER_URL ?>/email">
                <!-- Email -->
                <div class="mb-3-1">
                    <label for="identifier" style="color: black;font-weight: bold;  padding-bottom: 8px;">Email</label>
                    <input type="text" class="form-control" id="identifier" name="identifier" placeholder="Email">

                </div>

                <br>
                <div class="d-grid gap-2" style="place-items: center;">
                    <button type="submit" class="authentication-btn">Reset Password</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <p style="color: black;  ">Don't have an account? <a class="authentication-link" href="register">Register here</a></p>
            </div>


        </div>
    </div>

</div>