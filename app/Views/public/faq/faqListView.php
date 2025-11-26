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
</div>


<?php

ViewHelper::loadCustomerFooter();

?>
