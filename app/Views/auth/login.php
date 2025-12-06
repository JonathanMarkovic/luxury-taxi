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

                    <form method="POST" action="login">
                        <!-- Email -->
                        <div class="mb-3-1">
                            <label for="identifier" style="color: black;font-weight: bold; font-size: 18px; padding-bottom: 8px;">Email</label>
                            <input type="text" class="form-control" id="identifier" name="identifier" placeholder="Email">

                        </div>
                        <br>
                        <div class="mb-3-2">
                            <label for="password" style="color: black;font-weight: bold; font-size: 18px; padding-bottom: 8px;">Password</label>
                            <input
                            placeholder="Password"
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required
                            >

                        </div>
                        <br>
                        <div class="d-grid gap-2">
                            <button type="submit" class="authentication-btn">Login</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <p style="color: black; font-size: 20px; padding-bottom: 10em;">Don't have an account? <a class="authentication-link" href="register">Register here</a></p>
                    </div>
                </div>
            </div>

</div>


