<?php

use App\Helpers\ViewHelper;

$page_title = 'List of FAQs';
ViewHelper::loadCustomerHeader($page_title);
$faqs = $data['faq'];
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Find Answers About Our Services</h1>
        <p>Excellence is part of our transparency. Learn more about how we drive you in style.</p>
    </div>
</section>

<?php

ViewHelper::loadCustomerHeader($page_title);

?>
