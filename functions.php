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


<?php
// Definir constantes del repositorio
// THEME_GITHUB_REPO: URL del repositorio de GitHub que contiene el tema.
// THEME_GITHUB_ZIP_URL: URL directa al archivo ZIP que contiene la versión más reciente del tema.
// Asegúrate de cambiar 'tuusuario' por tu nombre de usuario de GitHub y 'tutema' por el nombre de tu repositorio.
define('THEME_GITHUB_REPO', 'https://github.com/oregoom/hdelabiblia');
define('THEME_GITHUB_ZIP_URL', 'https://github.com/oregoom/hdelabiblia/archive/refs/heads/main.zip'); // Cambia 'main' por la rama correspondiente si no usas 'main'.

// Obtener la versión actual del tema desde el archivo style.css
// Esta función utiliza la API de WordPress 'wp_get_theme()' para acceder a los metadatos del tema,
// y extrae específicamente la versión definida en el archivo style.css.
function get_current_theme_version() {
    $theme_data = wp_get_theme(); // Obtiene los datos del tema actual (como nombre, versión, etc.)
    return $theme_data->get('Version'); // Retorna la versión actual del archivo style.css
}

// Función para verificar si hay una actualización disponible en GitHub.
// Esta función es la clave para comprobar si el tema actual tiene una nueva versión en GitHub.
// Compara la versión obtenida de style.css con la versión más reciente de GitHub.
function check_for_github_updates($transient) {
    // Si no hay temas instalados que se estén verificando, salimos.
    if (empty($transient->checked)) {
        return $transient;
    }

    // Obtener la versión actual del tema (desde style.css)
    $current_version = get_current_theme_version();

    // Obtener la versión más reciente publicada en GitHub
    $remote_version = get_github_remote_version();
    
    // Si la versión en GitHub es mayor que la versión actual, configuramos la actualización.
    if (version_compare($current_version, $remote_version, '<')) {
        // Obtiene los datos del tema actual desde style.css.
        $theme_data = wp_get_theme();
        // Establecemos los datos de respuesta para la actualización, incluyendo la nueva versión y el enlace para descargar el ZIP.
        $transient->response[$theme_data->get_stylesheet()] = array(
            'new_version' => $remote_version, // Nueva versión encontrada en GitHub.
            'url'         => THEME_GITHUB_REPO, // URL del repositorio del tema en GitHub.
            'package'     => THEME_GITHUB_ZIP_URL // Enlace directo al ZIP para descargar el tema.
        );
    }
    
    // Retorna el objeto $transient con los detalles de actualización si corresponde.
    return $transient;
}
// El filtro 'pre_set_site_transient_update_themes' permite modificar el objeto de actualizaciones de temas.
// Añadimos la función check_for_github_updates al filtro para que WordPress sepa que debe verificar GitHub.
add_filter('pre_set_site_transient_update_themes', 'check_for_github_updates');

// Función para obtener la versión más reciente del tema desde GitHub.
// Esta función realiza una solicitud HTTP a la página de lanzamientos del repositorio de GitHub para
// obtener la versión más reciente del tema disponible.
function get_github_remote_version() {
    // Realizamos una solicitud GET a la página de lanzamientos del repositorio en GitHub.
    $response = wp_remote_get(THEME_GITHUB_REPO . '/releases/latest');

    // Si hay un error en la solicitud, retornamos la versión actual del tema.
    if (is_wp_error($response)) {
        return get_current_theme_version(); // En caso de error, usamos la versión actual del tema.
    }

    // Obtenemos el cuerpo de la respuesta.
    $body = wp_remote_retrieve_body($response);
    // Buscamos la versión del último lanzamiento usando una expresión regular.
    preg_match('/tag\/v([0-9.]+)/', $body, $matches);
    
    // Si encontramos un número de versión válido, lo retornamos.
    if (!empty($matches[1])) {
        return $matches[1]; // Retorna la versión extraída desde GitHub (por ejemplo, v1.1.13).
    }

    // Si no se puede obtener la versión remota, retorna la versión actual del tema.
    return get_current_theme_version();
}

// Mostrar un mensaje en la página de administración de temas cuando hay una actualización disponible.
// Esta función se ejecuta cuando se detecta una nueva versión y se muestra un mensaje en la interfaz de administración.
function github_theme_update_message($data, $response) {
    // Verifica si el tema actual es el que está siendo actualizado.
    if (isset($response['theme']) && $response['theme'] === wp_get_theme()->get_stylesheet()) {
        // Muestra un mensaje informando que hay una nueva versión disponible en GitHub.
        echo '<p><strong>Una nueva versión del tema está disponible en GitHub.</strong></p>';
    }
}
// El hook 'in_theme_update_message' permite mostrar un mensaje en la página de administración de temas.
// Añadimos nuestra función github_theme_update_message para mostrar un mensaje cuando haya una actualización.
add_action('in_theme_update_message', 'github_theme_update_message', 10, 2);
