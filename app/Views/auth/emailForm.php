<?php

use App\Helpers\ViewHelper;

$page_title = 'Login';
ViewHelper::loadCustomerLogin($page_title, 'login');
?>

<div class="login-container">
    <div class="card">
        <div class="card-header">
            <h3 class=""><?= hs(trans('login.welcome')) ?></h3>
            <p class="" style="color:black;font-weight:light; font-size:18px; padding-top:20px"><?= hs(trans('login.welcomeDesc')) ?>n</p>
        </div>
        <div class="card-body">
            <?= App\Helpers\FlashMessage::render() ?>

            <form method="POST" action="<?= APP_USER_URL ?>/email">
                <!-- Email -->
                <div class="mb-3-1">
                    <label for="identifier" style="color: black;font-weight: bold;  padding-bottom: 8px;"><?= hs(trans('login.email')) ?></label>
                    <input type="text" class="form-control" id="identifier" name="identifier" placeholder="example@gmail.com">

                </div>

                <br>
                <div class="d-grid gap-2" style="place-items: center; padding-top: 80px;">
                    <button type="submit" class="authentication-btn"><?= hs(trans('register.reset')) ?></button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <p style="color: black;  "><?= hs(trans('login.noAcc')) ?> <a class="authentication-link" href="register"><?= hs(trans('register.register')) ?></a></p>
            </div>


        </div>
    </div>

</div>
