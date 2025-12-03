<?php

use App\Helpers\ViewHelper;

$page_title = 'List of FAQs';
ViewHelper::loadCustomerHeader($page_title, 'faq');
$faqs = $data['faq'];
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Find Answers About Our Services</h1>
        <p>Excellence is part of our transparency. Learn more about how we drive you in style.</p>
    </div>
</section>

<div class="page-content">
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <?php foreach ($faqs as $index => $faq): ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#flush-collapse<?= $index ?>"
                        aria-expanded="false"
                        aria-controls="flush-collapse<?= $index ?>">
                        <?= htmlspecialchars($faq['question']) ?>
                    </button>
                </h2>
                <div id="flush-collapse<?= $index ?>"
                    class="accordion-collapse collapse"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <?= htmlspecialchars($faq['answer']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <br>
    <br>
    <div class="question-form-container">
        <div class="question-form-desc">
            <h1 class="question-form-header">Questions?</h1>
            <p class="question-form-body">
                Send us a message if you need any additional information, and we'll get back to you as soon as possible!
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
                    <label for="floatingInputGrid">Email</label>
                </div>
                <br>
                <div class="form-floating">
                    <textarea class="form-control" id="floatingTextarea2" style="height: 100px;" name="message"></textarea>
                    <label for="floatingInputGrid">Message</label>
                </div>
                <br>
                <button type="submit" class="all-cars-link">Submit</button>
            </form>
        </div>
    </div>
</div>


<?php

ViewHelper::loadCustomerFooter();

?>
