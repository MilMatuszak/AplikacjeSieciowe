<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header>
    <a href="<?php echo home_url(); ?>" class="site-logo">
        <?php if (has_custom_logo()): ?>
            <div class="logo-img"><?php the_custom_logo(); ?></div>
        <?php endif; ?>
        <span class="logo-text">Lacrosse <span>Polska</span></span>
    </a>
    <nav>
        <?php wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
        ]); ?>
    </nav>
</header>