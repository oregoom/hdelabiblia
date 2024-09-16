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



// Agregar el token de GitHub al proceso de descarga
add_filter('http_request_args', 'add_github_token_to_download', 10, 2);
function add_github_token_to_download($args, $url) {
    $token = get_option('github_access_token'); // Obtén el token de GitHub

    if (strpos($url, 'github.com/repos') !== false && !empty($token)) {
        // Agregar el encabezado de autorización si se trata de una URL de GitHub
        $args['headers']['Authorization'] = 'token ' . $token;
    }

    return $args;
}

// Después de la instalación, renombrar la carpeta del tema descargado
add_filter('upgrader_post_install', 'rename_theme_folder_after_update', 10, 3);
function rename_theme_folder_after_update($response, $hook_extra, $result) {
    // Verificar si estamos actualizando un tema
    if (isset($hook_extra['type']) && $hook_extra['type'] === 'theme') {
        $correct_theme_dir = WP_CONTENT_DIR . '/themes/hdelabiblia'; // Ruta de la carpeta correcta
        $downloaded_theme_dir = $result['destination']; // Ruta de la carpeta descargada

        // Si la carpeta descargada tiene un nombre incorrecto, renombrarla
        if ($downloaded_theme_dir !== $correct_theme_dir) {
            // Borrar la carpeta original vacía
            if (is_dir($correct_theme_dir)) {
                wp_delete_file($correct_theme_dir);
            }

            // Renombrar la carpeta con el sufijo al nombre correcto
            rename($downloaded_theme_dir, $correct_theme_dir);

            // Actualizar la ruta del tema en el resultado de la instalación
            $result['destination'] = $correct_theme_dir;
        }
    }
    return $response;
}

// Comprobar actualizaciones y descargar el archivo desde GitHub
add_filter('pre_set_site_transient_update_themes', 'check_for_github_updates');
function check_for_github_updates($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    $current_version = get_current_theme_version();  // Obtener la versión actual del tema
    $remote_version = get_github_remote_version();   // Obtener la versión remota de GitHub

    // Obtener la URL del archivo ZIP desde la base de datos
    $zip_url = get_github_zip_url();  // O generar automáticamente a partir de user/repo

    // Verificar si la versión remota es mayor que la local y si hay una URL ZIP válida
    if (version_compare($current_version, $remote_version, '<') && !empty($zip_url)) {
        $theme_data = wp_get_theme();

        // Asignar la actualización al objeto $transient
        $transient->response[$theme_data->get_stylesheet()] = array(
            'new_version' => $remote_version,
            'url'         => get_github_repo_url() . '/releases/latest',
            'package'     => $zip_url
        );
    }

    return $transient;
}

// Función para obtener la versión remota desde GitHub
function get_github_remote_version() {
    $token = get_github_access_token();  // Obtener el token de GitHub
    $user = get_option('github_user');   // Usuario de GitHub desde el campo de la base de datos
    $repo = get_option('github_repo');   // Repositorio de GitHub desde el campo de la base de datos

    $repo_url = "https://api.github.com/repos/{$user}/{$repo}/releases/latest"; // URL del último release

    if (empty($token)) {
        error_log("Token de GitHub está vacío");
        return get_current_theme_version();
    }

    // Realizar la solicitud a GitHub para obtener la versión más reciente
    $response = wp_remote_get($repo_url, array(
        'headers' => array(
            'Authorization' => 'token ' . $token,
            'Accept'        => 'application/vnd.github.v3+json',
        ),
    ));

    // Verificar si la solicitud tuvo éxito
    if (is_wp_error($response)) {
        error_log("Error en la respuesta de GitHub: " . $response->get_error_message());
        return get_current_theme_version();
    }

    // Decodificar la respuesta JSON
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Verificar si están presentes los campos 'tag_name' y 'zipball_url'
    if (isset($data['tag_name']) && isset($data['zipball_url'])) {
        // Eliminar el prefijo "v" si está presente en 'tag_name'
        $remote_version = ltrim($data['tag_name'], 'v');  // Eliminar 'v' del inicio de la versión
        
        // Guardar la URL del ZIP en la base de datos para usarla luego
        update_option('github_zip_url', $data['zipball_url']);
        return $remote_version;
    }

    error_log("No se encontró 'tag_name' o 'zipball_url' en la respuesta de GitHub");
    return get_current_theme_version(); // Retorna la versión actual si falla
}
