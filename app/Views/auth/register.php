<?php

use App\Helpers\ViewHelper;

$page_title = 'Login';
ViewHelper::loadCustomerHeader($page_title, 'register');
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Create Account</h3>
                </div>
                <div class="card-body">
                    <?= App\Helpers\FlashMessage::render() ?>

                    <form method="POST" action="register">
                        <!-- First Name -->
                        <div class="form-floating">
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                            <label for="first_name">First Name</label>
                        </div>
                        <br>
                        <!-- Last Name -->
                        <div class="form-floating">
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <label for="last_name">Last Name</label>
                        </div>
                        <br>
                        <!-- Email -->
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" required>
                            <label for="email">Email</label>
                        </div>
                        <br>
                        <!-- Phone -->
                        <div class="form-floating">
                            <input type="text" class="form-control" id="phone" name="phone" required>
                            <label for="phone">Phone Number</label>
                        </div>
                        <br>
                        <!-- Password -->
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <label for="password">Password</label>
                            <div class="form-text" style="color: white">
                                * Minimum 8 characters and 1 number.
                            </div>
                        </div>
                        <br>
                        <!-- Confirm Password -->
                        <div class="form-floating">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <label for="confirm_password">Confirm Password</label>
                        </div>
                        <br>
                        <input type="hidden" name="role" value="customer">

                        <div class="d-grid gap-2">
                            <button type="submit" class="authentication-btn">Register</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <p style="color: white;">Already have an account? <a class="authentication-link" href="login">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
ViewHelper::loadCustomerFooter();
?>
