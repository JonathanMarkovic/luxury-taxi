<!-- Footer -->
<footer class="footer" id="footer-section">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Solaf Performance</h3>
            <p><?= hs(trans('footer.description')) ?></p>
        </div>
        <div class="footer-section">
            <h3><?= hs(trans('footer.HelpfulLinks')) ?></h3>
            <a href="<?= APP_BASE_URL ?>/home"><?= hs(trans('nav.home')) ?></a>
            <a href="<?= APP_USER_URL ?>/cars"><?= hs(trans('nav.cars')) ?></a>
            <a href="<?= APP_USER_URL ?>/reservations"><?= hs(trans('nav.reservation')) ?></a>
            <a href="<?= APP_USER_URL ?>/faqs">FAQ</a>
        </div>
        <div class="footer-section">
            <h3><?= hs(trans('nav.contact')) ?></h3>
            <p><i class="bi bi-telephone"></i> +1 514 967 7575</p>
            <p><i class="bi bi-envelope"></i> solafperformance@gmail.com</p>
            <div class="social-icons">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
