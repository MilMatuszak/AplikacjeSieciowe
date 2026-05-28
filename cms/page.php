<?php get_header(); ?>

<div class="section">
    <h1 class="section-title"><?php the_title(); ?></h1>
    <div class="section-divider"></div>

    <?php if (is_page('kontakt')): ?>
        <div class="contact-page">
            <div class="contact-page-info">
                <h3>Email</h3>
                <p><i class="fa-solid fa-envelope"></i> kontak@lacrossepolska.pl</p>
                <h3>Facebook</h3>
                <p><i class="fa-brands fa-facebook"></i> <a href="https://facebook.com/PolskaFederacjaLacrosse" target="_blank">PolskaFederacjaLacrosse</a></p>
                <h3>Instagram</h3>
                <p><i class="fa-brands fa-instagram"></i> <a href="https://instagram.com/polandlacrosse" target="_blank">@polandlacrosse</a></p>
                <h3>Lokalizacja</h3>
                <p><i class="fa-solid fa-location-dot"></i> ul. Wodospadów 1, Katowice</p>
            </div>
            <div class="contact-page-form">
                <?php if (have_posts()): while (have_posts()): the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; endif; ?>
            </div>
        </div>

       
   <!-- MAPA -->
<div class="contact-map">
    <iframe
        src=https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2549.5!2d19.0216!3d50.2498!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4716ce4f3c3c3c3c%3A0x0!2sul.%20Wodospad%C3%B3w%201%2C%20Katowice!5e0!3m2!1spl!2spl!4v1
        width="100%"
        height="400"
        style="border:0;"
        allowfullscreen=""
        loading="lazy">
    </iframe>
</div>
    <?php else: ?>
        <div class="about-text">
            <?php if (have_posts()): while (have_posts()): the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>