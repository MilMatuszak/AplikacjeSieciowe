<?php
if (!is_front_page()) {
    wp_redirect(home_url());
    exit;
}
?>
<?php get_header(); ?>

<!-- HERO -->
<section class="hero">
    <h2>Lacrosse <span>Polska</span></h2>
    <p>Poznaj sport, który podbija Polskę</p>
    <a href="<?php echo get_permalink(get_page_by_path('aktualnosci')); ?>" class="hero-btn">Aktualności</a>
</section>

<!-- AKTUALNOŚCI -->
<section class="section">
    <h2 class="section-title">Najnowsze <span>Aktualności</span></h2>
    <div class="section-divider"></div>
    <div class="news-grid">
        <?php
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 3,
        ];
        $query = new WP_Query($args);
        if ($query->have_posts()):
            while ($query->have_posts()): $query->the_post(); ?>
                <div class="news-card">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium_large'); ?>
                        </a>
                    <?php endif; ?>
                    <div class="news-card-body">
                        <div class="news-card-date"><?php echo get_the_date('d F Y'); ?></div>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php the_excerpt(); ?></p>
                    </div>
                </div>
        <?php endwhile;
        wp_reset_postdata();
        endif; ?>
    </div>
    <a href="<?php echo get_permalink(get_page_by_path('aktualnosci')); ?>" class="btn">Zobacz wszystkie</a>
</section>

<!-- O LACROSSE -->
<section class="about-section">
    <div class="about-inner">
        <h2 class="section-title">O <span>Lacrosse</span></h2>
        <div class="section-divider"></div>
        <div class="about-text">
            <p>Lacrosse to jeden z najstarszych sportów zespołowych na świecie, wywodzący się z tradycji rdzennych mieszkańców Ameryki Północnej. W Polsce lacrosse dynamicznie się rozwija od ponad dekady.</p>
            <p>Polska Federacja Lacrosse (PFL) została założona w 2008 roku i od tego czasu aktywnie wspiera zarówno męskie, jak i żeńskie drużyny lacrosse, organizując mecze, turnieje i szkolenia.</p>
            <a href="<?php echo get_permalink(get_page_by_path('o-lacrosse')); ?>" class="btn">Dowiedz się więcej</a>
        </div>
    </div>
</section>


<!-- GALERIA -->
<section class="section">
    <h2 class="section-title">Galeria <span>Zdjęć</span></h2>
    <div class="section-divider"></div>
    <div class="albums-grid">
        <?php
        $albums = new WP_Query([
            'post_type'      => 'album',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        if ($albums->have_posts()):
            while ($albums->have_posts()): $albums->the_post(); ?>
                <div class="album-card">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('medium_large'); ?>
                        <?php else: ?>
                            <div class="album-placeholder">📷</div>
                        <?php endif; ?>
                        <div class="album-info">
                            <h3><?php the_title(); ?></h3>
                        </div>
                    </a>
                </div>
            <?php endwhile;
            wp_reset_postdata();
        endif; ?>
    </div>
    <a href="<?php echo get_permalink(get_page_by_path('galeria')); ?>" class="btn">Zobacz galerię</a>
</section>
<!-- KONTAKT -->
<section class="contact-section">
    <div class="contact-inner">
        <h2 class="section-title">Kontakt <span>z nami</span></h2>
        <div class="section-divider"></div>
        <div class="contact-info">
            <div class="contact-item">
                <h4>Email</h4>
                <p>kontakt@lacrossepolska.pl</p>
            </div>
            <div class="contact-item">
                <h4>Lokalizacja</h4>
                <p>Polska</p>
            </div>
            <div class="contact-item">
                <h4>Social Media</h4>
                <p>Facebook<br>Instagram</p>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>