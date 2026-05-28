<?php get_header(); ?>

<div class="section">
    <h1 class="section-title">
        <?php if (is_category()): ?>
            Kategoria: <span><?php single_cat_title(); ?></span>
        <?php else: ?>
            Najnowsze <span>Aktualności</span>
        <?php endif; ?>
    </h1>
    <div class="section-divider"></div>

    <!-- FILTRY KATEGORII -->
    <div class="category-filter">
        <a href="<?php echo get_permalink(get_page_by_path('aktualnosci')); ?>" class="cat-btn <?php echo !is_category() ? 'active' : ''; ?>">Wszystkie</a>
        <?php
        $categories = get_categories(['hide_empty' => true]);
        foreach ($categories as $cat):
            if ($cat->name == 'Bez kategorii') continue;
        ?>
            <a href="<?php echo get_category_link($cat->term_id); ?>" class="cat-btn <?php echo is_category($cat->term_id) ? 'active' : ''; ?>">
                <?php echo esc_html($cat->name); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="news-grid">
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
            <div class="news-card">
                <?php if (has_post_thumbnail()): ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium_large'); ?>
                    </a>
                <?php endif; ?>
                <div class="news-card-body">
                    <div class="news-card-date"><?php echo get_the_date('d F Y'); ?> — <?php the_category(', '); ?></div>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php the_excerpt(); ?></p>
                </div>
            </div>
        <?php endwhile; endif; ?>
    </div>
</div>

<?php get_footer(); ?>