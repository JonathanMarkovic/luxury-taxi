<?php

use App\Helpers\ViewHelper;

$page_title = 'Login';
ViewHelper::loadCustomerLogin($page_title, 'login');
?>
<div class="login-container">
    <div class="card">
        <div class="card-header">
            <h3><?= hs(trans('2fa.qrtitle')) ?>
            </h3>

        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="setup-steps">
            <h3><?= hs(trans('2fa.instruction')) ?>:</h3>
                <ol>
                    <li><?= hs(trans('2fa.instr1')) ?></li>
                    <li><?= hs(trans('2fa.instr2')) ?></li>
                    <li><?= hs(trans('2fa.instr3')) ?></li>
                </ol>
        </div>

        <div class="qr-code" style="text-align: center; margin: 20px 0; background: white; padding: 20px;">
            <!-- QR code displays as an image using data URI -->
            <img src="<?= $qrCodeDataUri ?? '' ?>" alt=<?= hs(trans('2fa.alt')) ?>>
        </div>

        <div class="manual-entry" style="background: #f5f5f5; padding: 15px; margin: 20px 0;">
            <p><strong><?= hs(trans('2fa.cantScan')) ?> </strong><?= hs(trans('2fa.manual')) ?>:</p>
            <code style="font-size: 1.2em; letter-spacing: 2px;"><?= htmlspecialchars($secret ?? '') ?></code>
        </div>

        <form method="POST" action="<?= '/' . APP_ROOT_DIR_NAME . '/2fa/verify-and-enable' ?>">
            <div class="form-group">
                <label for="code"><?= hs(trans('2fa.code')) ?>:</label>
                <input type="text"
                    id="code"
                    name="code"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    required
                    autofocus
                    placeholder="<?= hs(trans('2fa.verifPlaceholder')) ?>"
                    style="font-size: 1.5em; letter-spacing: 5px; text-align: center;">
            </div>

            <div  class="mt-3 text-center">
            <button type="submit" class="btn btn-primary"><?= hs(trans('2fa.verifyButton')) ?></button>
            <a href="<?= '/' . APP_ROOT_DIR_NAME . '/' ?>" class="btn btn-secondary"><?= hs(trans('2fa.cancelbtn')) ?></a>
            </div>
        </form>
        <br><br>
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

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        margin-right: 10px;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
</style>

<?php require __DIR__ . '/../common/footer.php'; ?>
