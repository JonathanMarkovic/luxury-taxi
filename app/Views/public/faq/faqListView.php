<?php

use App\Helpers\ViewHelper;

$page_title = 'List of FAQs';
ViewHelper::loadCustomerHeader($page_title, 'faq');
$faqs = $data['faq'];
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1><?= hs(trans('faq.title')) ?></h1>
        <p><?= hs(trans('faq.description')) ?></p>
    </div>
</section>
<br><br><br><br><br><br>
<div class="page-content">
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <!-- FAQ 1 -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse1"
                    aria-expanded="false"
                    aria-controls="flush-collapse1">
                    <?= hs(trans('faq.q1')) ?>
                </button>
            </h2>
            <div id="flush-collapse1"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <?= hs(trans('faq.a1')) ?>
                </div>
            </div>
        </div>

        <!-- FAQ 2 -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse2"
                    aria-expanded="false"
                    aria-controls="flush-collapse2">
                    <?= hs(trans('faq.q2')) ?>
                </button>
            </h2>
            <div id="flush-collapse2"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <?= hs(trans('faq.a2')) ?>
                </div>
            </div>
        </div>

        <!-- FAQ 3 -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse3"
                    aria-expanded="false"
                    aria-controls="flush-collapse3">
                    <?= hs(trans('faq.q3')) ?>
                </button>
            </h2>
            <div id="flush-collapse3"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <?= hs(trans('faq.a3')) ?>
                </div>
            </div>
        </div>

        <!-- FAQ 4 -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse4"
                    aria-expanded="false"
                    aria-controls="flush-collapse4">
                    <?= hs(trans('faq.q4')) ?>
                </button>
            </h2>
            <div id="flush-collapse4"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <?= hs(trans('faq.a4')) ?>
                </div>
            </div>
        </div>

        <!-- FAQ 5 -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse5"
                    aria-expanded="false"
                    aria-controls="flush-collapse5">
                    <?= hs(trans('faq.q5')) ?>
                </button>
            </h2>
            <div id="flush-collapse5"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <?= hs(trans('faq.a5')) ?>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br><br><br><br>
    <div class="question-form-container">
        <div class="question-form-desc">
            <h1 class="question-form-header">Question?</h1>
            <p class="question-form-body">
                <?= hs(trans('faq.Qdesc')) ?>
            </p>
        </div>
        <!-- Ask Question Form -->
        <div class="question-form">
            <div class="mb-4">
                <?= App\Helpers\FlashMessage::render() ?>
            </div>
            <form class="form-floating" action="<?= APP_USER_URL ?>/faqs/question" method="POST">
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInputGrid" name="email">
                    <label for="floatingInputGrid"><?= hs(trans('faq.email')) ?></label>
                </div>
                <br>
                <div class="form-floating">
                    <textarea class="form-control" id="floatingTextarea2" style="height: 100px;" name="message"></textarea>
                    <label for="floatingInputGrid">Message</label>
                </div>
                <br>
                <button type="submit" class="all-cars-link"><?= hs(trans('faq.submit')) ?></button>
            </form>
        </div>
    </div>
</div>


<?php

ViewHelper::loadCustomerFooter();

?>
