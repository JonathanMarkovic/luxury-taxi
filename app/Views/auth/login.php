<?php

use App\Helpers\ViewHelper;

$page_title = 'Login';
ViewHelper::loadCustomerLogin($page_title, 'login');
?>

<div class="login-container">
    <div class="card">
        <div class="card-header">
            <h3 class=""><?= hs(trans('login.welcome')) ?></h3>
            <p class="" style="color:black;font-weight:light; font-size:18px; padding-top:20px"><?= hs(trans('login.welcomeDesc')) ?></p>
        </div>
        <div class="card-body">
            <?= App\Helpers\FlashMessage::render() ?>

            <form method="POST" action="login">
                <!-- Email -->
                <div class="mb-3-1">
                    <label for="identifier" style="color: black;font-weight: bold;  padding-bottom: 8px;"><?= hs(trans('login.email')) ?></label>
                    <input type="text" class="form-control" id="identifier" name="identifier" placeholder="example@gmail.com">

                </div>
                <br>
                <div class="mb-3-2">
                    <label for="password" style="color: black;font-weight: bold;  padding-bottom: 8px;"><?= hs(trans('login.password')) ?></label>
                    <input
                        placeholder="<?= hs(trans('login.password')) ?>"
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        required>
                </div>

                <div class="mt-3 " style="padding-bottom: 80px; text-align: right;">
                    <a class="authentication-link" href="public/email"><?= hs(trans('login.forgot')) ?></a>
                </div>
                <br>
                <div class="d-grid gap-2" style="place-items: center;">
                    <button type="submit" class="authentication-btn"><?= hs(trans('login.login')) ?></button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <p style="color: black;  "><?= hs(trans('login.noAcc')) ?> <a class="authentication-link" href="register"><?= hs(trans('login.register')) ?></a></p>
            </div>


        </div>
    </div>

</div>
