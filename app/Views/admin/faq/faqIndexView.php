<?php

use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'FAQ ';
$faqs = $data['faq'];


//TODO: we need to load an admin-specific header
ViewHelper::loadAdminHeader($page_title);
?>


<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <!-- Flash Messages Display Area -->
 <div class="mb-4">
            <?= App\Helpers\FlashMessage::render() ?>
        </div>

    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">FAQ</h1>

    </div>
<div>
<!--ADD this to route and redirect to faqCreateView.php -->
    <a class="btn btn-primary" href="<?= APP_ADMIN_URL?>/faq/add"> Create New FAQ</a>
</div>

    <br>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Question</th>
                <th>Answer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($faqs as $key => $faq) { ?>
                <tr>
                    <td><?= htmlspecialchars($faq["faq_id"], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($faq["question"], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($faq["answer"], ENT_QUOTES, 'UTF-8') ?></td>


                    <td>
                        <!-- ADD route and redirect to faqEditView.php-->
                        <a class="btn btn-success" href="<?= APP_ADMIN_URL ?>/faq/edit/<?= $faq['faq_id'] ?>"> Edit</a>

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $faq['faq_id'] ?>"> Delete</button>
                    </td>

                </tr>
                <div class="modal fade" id="deleteModal<?= $faq['faq_id']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel<?= $faq['faq_id']?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="deleteModalLabel<?= $faq['faq_id'] ?>">Attention!</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Would you really like to remove this from the table?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                                <a href="faq/delete/<?= $faq['faq_id'] ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            ?>
        </tbody>
    </table>
</main>

<?php
ViewHelper::loadJsScripts();
//TODO: we need to load an admin-specific footer
ViewHelper::loadAdminFooter();
?>
