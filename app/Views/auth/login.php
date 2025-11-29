<?php

use App\Helpers\ViewHelper;

$page_title = 'Login';
ViewHelper::loadCustomerHeader($page_title, 'login');
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Login</h3>
                </div>
                <div class="card-body">
                    <?= App\Helpers\FlashMessage::render() ?>

                    <form method="POST" action="login">
                        <!-- <div class="mb-3">
                            <label for="identifier" class="form-label">Email</label>
                            <input
                                type="text"
                                class="form-control"
                                id="identifier"
                                name="identifier"
                                placeholder="Enter your email or username"
                                required>
                        </div> -->
                        <!-- Email -->
                        <div class="form-floating">
                            <input type="text" class="form-control" id="identifier" name="identifier">
                            <label for="identifier">Email</label>
                        </div>
                        <br>
                        <div class="form-floating">
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required
                            >
                            <label for="password">Password</label>
                        </div>
                        <br>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <p style="color: white;">Don't have an account? <a class="authentication-link" href="register">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
ViewHelper::loadCustomerFooter();
?>
