<?php get_header(); ?>

<div class="single-post">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
        <div class="single-post-header">
            <div class="news-card-date"><?php echo get_the_date('d F Y'); ?> — <?php the_category(', '); ?></div>
            <h1><?php the_title(); ?></h1>
        </div>
        <?php if (has_post_thumbnail()): ?>
            <div class="single-post-image">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>
        <div class="post-content">
            <?php the_content(); ?>
        </div>
    <?php endwhile; endif; ?>
    <a href="<?php echo home_url(); ?>" class="btn" style="margin-top:40px;display:inline-block;">← Wróć do strony głównej</a>
</div>

<?php get_footer(); ?>