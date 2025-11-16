<?php

use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'Edit FAQ';

$faq = $data['faq'];

//TODO: we need to load an admin-specific header
ViewHelper::loadAdminHeader($page_title);
?>
<!-- here we need to render the form for editing the selected item. -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h2>FAQ Editing</h2>
<br>
    <form id="updateForm<?= $faq['faq_id'] ?>" class="col-md-12" method="POST" action="<?= APP_ADMIN_URL ?>/faq/update/<?= $faq['faq_id'] ?>">
        <input type="hidden" name="faq_id" value="<?= $faq['faq_id'] ?>">

        <div class="col-md-6">
            <label for="inputQuestion" class="form-label">Question</label>
            <input type="text" name="question" class="form-control" id="inputQuestion" value="<?= $faq['question'] ?>">
        </div>

        <div class="col-md-6">
            <label for="inputAnswer" class="form-label">Answer</label>
            <input type="text" name="answer" class="form-control" id="inputAnswer" value="<?= $faq['answer'] ?>">
        </div>
        <br>


        <div class="col-12">
            <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#saveModal<?= $faq['faq_id'] ?>"> Save</a>
            <a href="<?= APP_ADMIN_URL ?>/faq" class="btn btn-danger">Cancel</a>
        </div>
    </form>
    <!-- Modal -->
                <div class="modal fade" id="saveModal<?= $faq['faq_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="saveModalLabel<?= $faq['faq_id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="saveModalLabel<?= $faq['faq_id'] ?>">Attention!</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Would you really like to update this information?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                                <button type="submit" form="updateForm<?= $faq['faq_id'] ?>" class="btn btn-success">Update</button>


                            </div>
                        </div>
                    </div>
                </div>
</main>
<?php
ViewHelper::loadJsScripts();
//TODO: we need to load an admin-specific footer
ViewHelper::loadAdminFooter();
?>
