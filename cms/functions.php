<?php
function lacrosse_setup() {
    register_nav_menus([
        'primary' => 'Menu główne',
        'footer'  => 'Menu stopki',
    ]);
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'lacrosse_setup');

function lacrosse_scripts() {
    // Wyłącz domyślne style WordPress
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');

    // Załaduj nasz styl
    wp_enqueue_style('lacrosse-style', get_stylesheet_uri());

    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'lacrosse_scripts', 20);

function lacrosse_lightbox() {
    wp_enqueue_style('lightbox-css', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css');
    wp_enqueue_script('lightbox-js', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js', ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', 'lacrosse_lightbox');

function lacrosse_register_album() {
    register_post_type('album', [
        'labels' => [
            'name'          => 'Albumy',
            'singular_name' => 'Album',
            'add_new'       => 'Dodaj album',
            'add_new_item'  => 'Dodaj nowy album',
            'edit_item'     => 'Edytuj album',
            'all_items'     => 'Wszystkie albumy',
        ],
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-format-gallery',
        'supports'      => ['title', 'editor', 'thumbnail'],
        'rewrite'       => ['slug' => 'albumy'],
        'show_in_rest'  => true,
    ]);
}
add_action('init', 'lacrosse_register_album');