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





class Geko_Theme_Update_Checker {

    public function __construct() {
        // Engancha la función de comprobación de actualización al filtro de WordPress
        add_filter( 'pre_set_site_transient_update_themes', [ $this, 'check_for_update' ] );
    }

    public function check_for_update( $transient ) {
        // Verificamos si ya se ha consultado el estado de los temas
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        // Obtener los datos del tema actual
        $theme_data = wp_get_theme( wp_get_theme()->template );
        $theme_slug = sanitize_key( $theme_data->get_template() ); // Limpiar el slug del tema

        // Eliminar '-master' del slug si está presente
        $theme_uri_slug = untrailingslashit( str_replace( '-master', '', $theme_slug ) );

        // Intentamos obtener la versión remota desde la caché
        $remote_version = get_transient( 'geko_theme_remote_version' );
        
        // Si no hay versión en caché, obtenemos la versión desde GitHub
        if ( false === $remote_version ) {
            // Hacer una solicitud HTTP para obtener el archivo style.css desde GitHub
            $response = wp_remote_get( "https://raw.githubusercontent.com/oregoom/{$theme_uri_slug}/master/style.css" );

            // Verificar si hubo errores en la solicitud HTTP
            if ( is_wp_error( $response ) ) {
                return $transient; // Si hay un error, regresamos sin modificar el transitorio
            }

            // Obtener el contenido del archivo style.css
            $style_css = wp_remote_retrieve_body( $response );

            // Verificar que el contenido no esté vacío
            if ( empty( $style_css ) ) {
                return $transient; // Si el archivo está vacío, regresamos sin cambios
            }

            // Buscar la versión en el archivo style.css
            if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( 'Version', '/' ) . ':(.*)$/mi', $style_css, $match ) && $match[1] ) {
                $remote_version = _cleanup_header_comment( $match[1] );
                
                // Guardar la versión remota en un transitorio para evitar consultas repetidas
                set_transient( 'geko_theme_remote_version', $remote_version, 12 * HOUR_IN_SECONDS ); // Caché por 12 horas
            } else {
                // Si no encontramos la versión en el archivo style.css, dejamos la versión remota en "0.0.0"
                $remote_version = '0.0.0';
            }
        }

        // Comparamos las versiones del tema local y la remota
        if ( version_compare( $theme_data->version, $remote_version, '<' ) ) {
            // Si la versión remota es mayor, se configura el transitorio para notificar la actualización
            $transient->response[ $theme_slug ] = array(
                'theme'       => $theme_slug,
                'new_version' => $remote_version,
                'url'         => "https://github.com/oregoom/{$theme_uri_slug}",
                'package'     => "https://github.com/oregoom/{$theme_uri_slug}/archive/master.zip",
            );
        }

        return $transient; // Devolver el transitorio actualizado
    }
}

// Instanciar la clase para activar la funcionalidad
new Geko_Theme_Update_Checker();
