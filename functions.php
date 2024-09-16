<?php

// Incluir Bootstrap CSS 5.3
function hb_bootstrap_css() {
    wp_enqueue_style( 'bootstrap_css', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', 
        array(), 
        '5.3'
    );
        
    // Estilos del tema
    wp_enqueue_style( 'estilo-tema-padre', get_template_directory_uri() . '/css/hb-style.css', 
        array(), 
        filemtime(get_template_directory() . '/css/hb-style.css') // Uso de filemtime para asegurar actualización de estilos
    );
}
add_action( 'wp_enqueue_scripts', 'hb_bootstrap_css' );

// Incluir Bootstrap JS y dependencia Popper
function hb_bootstrap_js() {
    wp_enqueue_script( 'popper_js', 
        'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js', 
        array(), 
        '2.11.8', 
        true
    ); 
    wp_enqueue_script( 'bootstrap_js', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js', 
        array('jquery', 'popper_js'), 
        '5.3.2', 
        true
    ); 
}
add_action( 'wp_enqueue_scripts', 'hb_bootstrap_js' );

// Incluir Normalize.css
function hb_add_normalize_CSS() {
    wp_enqueue_style( 'normalize-styles', 'https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css');
}
add_action('wp_enqueue_scripts', 'hb_add_normalize_CSS');

// Registrar Menú de Navegación
function hb_add_main_nav() {
    register_nav_menu('header-menu', __( 'Menú Superior' ));
}
add_action( 'init', 'hb_add_main_nav' );

// Agregar clase adicional a los enlaces de navegación
function hb_add_additional_class_on_a($classes, $item, $args) {
    if (isset($args->add_a_class)) {
        $classes['class'] = $args->add_a_class;
    }
    return $classes;
}
add_filter('nav_menu_link_attributes', 'hb_add_additional_class_on_a', 1, 3);

// Soporte para logo personalizado
add_theme_support( 'custom-logo' );

// Agregar clase personalizada al logo
add_filter( 'get_custom_logo_image_attributes', function($custom_logo_attr, $custom_logo_id, $blog_id) {
    $custom_logo_attr['class'] = 'hb-your-custom-class-logo img-fluid';
    return $custom_logo_attr;
}, 10, 3);

// Soporte para imágenes destacadas
add_theme_support('post-thumbnails');

// Incluir shortcodes
include_once 'inc/shortcode.php';



