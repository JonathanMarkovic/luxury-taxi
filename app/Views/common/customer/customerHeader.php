<?php

use App\Helpers\SessionManager;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/luxury-taxi/assets/css/clientSideStyles.css">
    <link rel="stylesheet" href="/luxury-taxi/assets/css/reservation.css">

    <!-- Needs to be switched off sandbox if this app goes into production -->
    <!-- This is the sandbox script -->
    <script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>

    <!-- This is the production script -->
    <!-- <script type="text/javascript" src="https://web.squarecdn.com/v1/square.js"></script> -->

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <div>Solaf</div>
                <span>Performance</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'home' ? 'active' : '' ?>" href="<?= APP_BASE_URL ?>/home"><?= hs(trans('nav.home')) ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'find-reservation' ? 'active' : '' ?>" href="<?= APP_USER_URL ?>/reservations"><?= hs(trans('nav.reservation')) ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'cars' ? 'active' : '' ?>" href="<?= APP_USER_URL ?>/cars"><?= hs(trans('nav.cars')) ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'faq' ? 'active' : '' ?>" href="<?= APP_USER_URL ?>/faqs">FAQ</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#footer-section"><?= hs(trans('nav.contact')) ?></a></li>
                </ul>
                <div class="d-flex gap-3">
                    <ul class="navbar-nav mx-auto">
                        <!-- If Admin, link to go back to admin panel -->
                        <?php if (SessionManager::get('user_role') === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= APP_ADMIN_URL ?>/dashboard">Admin Panel</a>
                            </li>
                        <?php endif; ?>

                        <?php
                        // dd(SessionManager::get('user'));
                        if (!SessionManager::get('is_authenticated')) { ?>
                            <li>
                                <a class="nav-link <?= $current_page === 'login' ? 'active' : '' ?>" href="<?= APP_BASE_URL ?>/login"><?= hs(trans('nav.login')) ?></a>
                            </li>
                            <li>
                                <a class="nav-link <?= $current_page === 'register' ? 'active' : '' ?>" href="<?= APP_BASE_URL ?>/register"><?= hs(trans('nav.signup')) ?></a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'change-password' ? 'active' : '' ?>" href="<?= APP_USER_URL ?>/changePassword"><?= hs(trans('nav.changePass')) ?></a>
                            </li>
                            <li>
                                <a class="nav-link" href="<?= APP_BASE_URL ?>/logout"><?= hs(trans('nav.logout')) ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div>
                    <div class="language-switcher">
                        <?php
                        // Get current locale from global translator
                        global $translator;
                        $currentLocale = $translator->getLocale();
                        $availableLocales = $translator->getAvailableLocales();
                        ?>

                        <?php foreach ($availableLocales as $locale): ?>
                            <?php if ($locale !== $currentLocale): ?>
                                <a href="?lang=<?= hs($locale) ?>" class="lang-link">
                                    <?= $locale === 'en' ? 'English' : 'Français' ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
