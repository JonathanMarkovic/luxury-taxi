<?php

use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'Create category';


//TODO: we need to load an admin-specific header
ViewHelper::loadAdminHeader($page_title);
?>
<!-- here we need to render the form for editing the selected item. -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

    <h2>Category Creating</h2>
<br>
    <form id="createForm" class="col-md-12" method="POST" action="<?= APP_ADMIN_URL ?>/faq/create">

        <div class="col-md-6">
            <label for="inputQuestion" class="form-label">Question</label>
            <input type="text" name="question" class="form-control" id="inputQuestion">
        </div>

        <div class="col-md-6">
            <label for="inputAnswer" class="form-label">Answer</label>
            <input type="text" name="answer" class="form-control" id="inputAnswer" >
        </div>
        <br>
<br>


        <div class="col-12">
            <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#saveModal"> Create</a>
            <a href="<?= APP_ADMIN_URL ?>/faq" class="btn btn-danger">Cancel</a>
        </div>
    </form>
    <!-- Modal -->
                <div class="modal fade" id="saveModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="saveModalLabel">Attention!</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Would you really like to create this information?

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                                <button type="submit" form="createForm" class="btn btn-success">Create</button>


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
