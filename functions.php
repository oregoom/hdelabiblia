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


//ACTUALIZAR TEMA DESDE GITHUB
  // set_site_transient('update_themes', null);
 /**
 * Enganchar la función para verificar actualizaciones en el filtro pre_set_site_transient_update_themes
 */
add_filter('pre_set_site_transient_update_themes', 'mi_tema_verificar_actualizacion');

/**
 * Verifica si hay una actualización disponible para el tema desde GitHub.
 *
 * @param object $transient Datos transitorios de actualización de temas.
 * @return object Datos transitorios modificados con información de actualización.
 */
function mi_tema_verificar_actualizacion($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    // Configura el slug del tema y la URL base de GitHub.
    $tema_slug = 'hdelabiblia'; // Reemplazar con el slug de tu tema
    $usuario_github = 'oregoom'; // Reemplazar con tu usuario de GitHub
    $repositorio_github = 'hdelabiblia'; // Reemplazar con el nombre de tu repositorio

    // Obtener la versión remota desde GitHub
    $url_version = "https://raw.githubusercontent.com/{$usuario_github}/{$repositorio_github}/master/style.css";
    $response = wp_remote_get($url_version);

    if (is_wp_error($response)) {
        // Manejar error en la solicitud
        return $transient;
    }

    $contenido_style_css = wp_remote_retrieve_body($response);
    $version_remota = '0.0.0';
    
    // Extraer la versión del tema del contenido de style.css
    if (preg_match('/Version: (.*)/i', $contenido_style_css, $match)) {
        $version_remota = trim($match[1]);
    }

    // Obtener la versión actual del tema
    $tema_datos = wp_get_theme($tema_slug);
    $version_local = $tema_datos->get('Version');

    // Comparar versión local con la remota
    if (version_compare($version_local, $version_remota, '<')) {
        $transient->response[$tema_slug] = [
            'theme'       => $tema_slug,
            'new_version' => $version_remota,
            'url'         => "https://github.com/{$usuario_github}/{$repositorio_github}",
            'package'     => "https://github.com/{$usuario_github}/{$repositorio_github}/archive/master.zip",
        ];
    }

    return $transient;
}
