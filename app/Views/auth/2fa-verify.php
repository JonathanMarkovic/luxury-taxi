<?php

use App\Helpers\ViewHelper;

$page_title = 'Login';
ViewHelper::loadCustomerLogin($page_title, 'login');
?>

<div class="login-container">
    <div class="card">
        <div class="card-header">
            <h3 class=""><?= hs(trans('2fa.title')) ?></h3>
            <p class="" style="color:black;font-weight:light; font-size:18px; padding-top:20px"><?= hs(trans('2fa.desc')) ?></p>
        </div>
        <div class="card-body">
            <?= App\Helpers\FlashMessage::render() ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

           <form method="POST" action="<?= '/' . APP_ROOT_DIR_NAME . '/2fa/verify' ?>">
        <div class="form-group">
            <label for="code"><?= hs(trans('2fa.text')) ?>:</label>
            <input type="text"
                id="code"
                name="code"
                pattern="[0-9]{6}"
                maxlength="6"
                required
                autofocus
                placeholder="000000"
                style="font-size: 2em; letter-spacing: 10px; text-align: center;">
        </div>


        <button type="submit" class="btn btn-primary" style="width: 100%;"><?= hs(trans('2fa.verify')) ?></button>
    </form>

    <div style="margin-top: 20px; text-align: center;">
        <form method="GET" action="<?= '/' . APP_ROOT_DIR_NAME . '/logout' ?>">
            <button type="submit" class="btn-link"><?= hs(trans('2fa.cancel')) ?></button>
        </form>
    </div>


        </div>
    </div>

</div>


<style>
    .form-group {
        margin: 20px 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input[type="text"] {
        width: 100%;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .btn {
        padding: 15px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-link {
        background: none;
        border: none;
        cursor: pointer;
        text-decoration: underline;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 4px;
    }
</style>

<?php require __DIR__ . '/../common/footer.php'; ?>
