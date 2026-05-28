<?php get_header(); ?>

<div class="section">
    <h1 class="section-title"><?php the_title(); ?></h1>
    <div class="section-divider"></div>

    <?php if (have_posts()): while (have_posts()): the_post();
        // Pobierz wszystkie obrazki z treści wpisu
        $post_id = get_the_ID();
        $images = get_attached_media('image', $post_id);
    ?>

        <?php if (!empty($images)): ?>
            <div class="gallery-grid">
                <?php foreach ($images as $image):
                    $full_url = wp_get_attachment_url($image->ID);
                    $thumb = wp_get_attachment_image($image->ID, 'medium_large');
                    echo '<a href="' . esc_url($full_url) . '" data-lightbox="album-' . $post_id . '" data-title="' . esc_attr($image->post_title) . '">' . $thumb . '</a>';
                endforeach; ?>
            </div>
        <?php else: ?>
            <p>Brak zdjęć w tym albumie.</p>
        <?php endif; ?>

    <?php endwhile; endif; ?>

    <a href="<?php echo get_permalink(get_page_by_path('galeria')); ?>" class="btn" style="margin-top:40px;display:inline-block;">← Wróć do galerii</a>
</div>

<?php get_footer(); ?>