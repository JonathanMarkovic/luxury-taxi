<?php

use App\Helpers\ViewHelper;

$page_title = 'Login';
ViewHelper::loadCustomerLogin($page_title, 'register');
?>

<div class="login-container">

            <div class="card">
                <div class="card-header" style="padding-bottom:10px">
                    <h3 class=""><?= hs(trans('register.create')) ?></h3>
                    <p class="" style="color:black;font-weight:light; font-size:18px; padding-top:20px"><?= hs(trans('register.desc')) ?></p>
                </div>
                <div class="card-body">
                    <?= App\Helpers\FlashMessage::render() ?>

                    <form method="POST" action="register">
                        <!-- First Name -->
                        <div class="mb-3-3">
                            <label for="first_name"  class="field-name" ><?= hs(trans('register.firstN')) ?></label>
                            <input style="padding: 5px; font-size:18px" type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <br>
                        <!-- Last Name -->
                        <div class="mb-3-3">
                            <label for="last_name"  class="field-name" ><?= hs(trans('register.lastN')) ?></label>
                            <input style="padding: 5px; font-size:18px" type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <br>
                        <!-- Email -->
                        <div class="mb-3-3">
                            <label for="email"  class="field-name" ><?= hs(trans('register.email')) ?></label>
                            <input style="padding: 5px; font-size:18px" type="email" class="form-control" id="email" name="email" required>

                        </div>
                        <br>
                        <!-- Phone -->
                        <div class="mb-3-3">
                            <label for="phone"  class="field-name" ><?= hs(trans('register.phone')) ?></label>
                            <input style="padding: 5px; font-size:18px" type="text" class="form-control" id="phone" name="phone" required>

                        </div>
                        <br>
                        <!-- Password -->
                        <div class="">
                            <label for="password"  class="field-name" ><?= hs(trans('login.password')) ?></label>
                            <input style="padding: 5px; font-size:18px" type="password" class="form-control" id="password" name="password" required>

                            <div class="form-text" style="color: black">
                                <?= hs(trans('register.minimum')) ?>
                            </div>
                        </div>
                        <br>
                        <!-- Confirm Password -->
                        <div class="mb-3-3" style="padding-bottom:20px">
                            <label for="confirm_password"  class="field-name" ><?= hs(trans('register.pass')) ?></label>
                            <input style="padding: 5px; font-size:18px" type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <br>
                        <input type="hidden" name="role" value="customer">

                        <div class="d-grid gap-2" style="place-items: center;">
                            <button type="submit" class="authentication-btn"><?= hs(trans('register.register')) ?></button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <p style="color: black;"><?= hs(trans('register.acc')) ?> <a class="authentication-link" href="login"><?= hs(trans('register.registerLink')) ?></a></p>
                    </div>
                </div>
            </div>

</div>

