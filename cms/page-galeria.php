<?php get_header(); ?>

<div class="section">
    <h1 class="section-title">Galeria <span>Zdjęć</span></h1>
    <div class="section-divider"></div>

    <div class="albums-grid">
        <?php
        $albums = new WP_Query([
            'post_type'      => 'album',
            'posts_per_page' => 20,
            'post_status'    => 'publish',
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
</div>

<?php get_footer(); ?>