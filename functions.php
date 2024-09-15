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

/*
 * Páginas de Administración
 */

// Añadir el menú de administración
add_action('admin_menu', 'HBiblia_agregar_menu_principal');
function HBiblia_agregar_menu_principal() {
    // Menú principal
    add_menu_page(
        'Tema HBiblia',        // Título de la página
        'Tema HBiblia',        // Título del menú
        'manage_options',      // Capacidad requerida
        'admin-hbiblia',       // Slug del menú
        'HBiblia_escritorio_page', // Función que renderiza la página del escritorio
        'dashicons-admin-generic', // Icono del menú
        3                      // Posición en el menú
    );

    // Submenú 'Generales'
    add_submenu_page(
        'admin-hbiblia',         // Slug del menú principal
        'Generales',             // Título de la página
        'Generales',             // Título del menú
        'manage_options',        // Capacidad requerida
        'admin-hbiblia',         // Slug del submenú
        'HBiblia_escritorio_page' // Función para renderizar la página
    );

    // Submenú 'Google Analytics'
    add_submenu_page(
        'admin-hbiblia',               // Slug del menú principal
        'Configuración de Google Analytics', // Título de la página
        'Google Analytics',            // Título del menú
        'manage_options',              // Capacidad requerida
        'hbiblia-google-analytics',    // Slug del submenú
        'HBiblia_google_analytics_page' // Función que renderiza la página
    );

    // Submenú 'Google AdSense'
    add_submenu_page(
        'admin-hbiblia',               // Slug del menú principal
        'Configuración de AdSense',    // Título de la página
        'Google AdSense',              // Título del menú
        'manage_options',              // Capacidad requerida
        'hbiblia-adsense',             // Slug del submenú
        'HBiblia_adsense_page'         // Función que renderiza la página
    );

    // Submenú 'API Update'
    add_submenu_page(
        'admin-hbiblia',         // Slug del menú principal
        'API Update',            // Título de la página
        'API Update',            // Título del submenú
        'manage_options',        // Capacidad requerida
        'api-update',            // Slug del submenú
        'HBiblia_api_update_page' // Función que renderiza la página
    );
}

// Función para la página principal del tema (función corregida)
function HBiblia_escritorio_page() {
    ?>
    <div class="wrap">
        <h1>Bienvenido a Tema HBiblia</h1>
        <p>Configuraciones generales del tema HBiblia.</p>
    </div>
    <?php
}

// Página para la gestión de Google Analytics
function HBiblia_google_analytics_page() {
    ?>
    <div class="wrap">
        <h1>Configuración de Google Analytics</h1>
        <form method="post" action="options.php">
            <?php settings_fields('HBiblia-settings-group'); ?>
            <?php do_settings_sections('HBiblia-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">ID de Google Analytics:</th>
                    <td><input type="text" name="google_analytics_id" value="<?php echo esc_attr(get_option('google_analytics_id')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'HBiblia_registrar_opciones');
function HBiblia_registrar_opciones() {
    register_setting('HBiblia-settings-group', 'google_analytics_id');
}

// Insertar el código de Google Analytics en wp_head
add_action('wp_head', 'HBiblia_insertar_google_analytics');
function HBiblia_insertar_google_analytics() {
    if (current_user_can('administrator')) {
        return; // No insertar Google Analytics para administradores
    }

    $google_analytics_id = get_option('google_analytics_id');
    if (!empty($google_analytics_id)) {
        echo "<!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src='https://www.googletagmanager.com/gtag/js?id=" . esc_attr($google_analytics_id) . "'></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '" . esc_attr($google_analytics_id) . "');
        </script>";
    }
}

// Página para la gestión de Google AdSense
function HBiblia_adsense_page() {
    ?>
    <div class="wrap">
        <h1>Configuración de AdSense</h1>
        <form method="post" action="options.php">
            <?php settings_fields('HBiblia-adsense-settings-group'); ?>
            <?php do_settings_sections('HBiblia-adsense-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Código de AdSense:</th>
                    <td><textarea name="adsense_code" rows="5" cols="50"><?php echo esc_textarea(get_option('adsense_code')); ?></textarea></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'HBiblia_registrar_opciones_adsense');
function HBiblia_registrar_opciones_adsense() {
    register_setting('HBiblia-adsense-settings-group', 'adsense_code');
}

// Insertar el código de Google AdSense en wp_head
add_action('wp_head', 'HBiblia_insertar_adsense');
function HBiblia_insertar_adsense() {
    $adsense_code = get_option('adsense_code');
    if (!empty($adsense_code)) {
        echo $adsense_code; // Asegúrate de que el código de AdSense esté correctamente escapado
    }
}

// Función para la página de actualización de GitHub
function HBiblia_api_update_page() {
    // Obtener el token, la URL del repositorio y la URL del archivo ZIP desde la base de datos
    $github_token = get_option('github_access_token');
    $github_repo = get_option('github_repo_url');
    $github_zip_url = get_option('github_zip_url');

    ?>
    <div class="wrap">
        <h1>API Update</h1>
        <form method="post" action="options.php">
            <?php settings_fields('HBiblia-api-settings-group'); ?>
            <?php do_settings_sections('HBiblia-api-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">GitHub Access Token:</th>
                    <td><input type="text" name="github_access_token" value="<?php echo esc_attr($github_token); ?>" placeholder="Ingrese su token de GitHub aquí" />
                    <p class="description">Ejemplo: <code>ghp_xxx...xx</code></p></td>
                </tr>
                <tr valign="top">
                    <th scope="row">GitHub Repo URL:</th>
                    <td><input type="text" name="github_repo_url" value="<?php echo esc_attr($github_repo); ?>" />
                    <p class="description">Ingrese la URL del repositorio de GitHub, por ejemplo: <code>https://github.com/tuusuario/tutema</code></p></td>
                </tr>
                <tr valign="top">
                    <th scope="row">GitHub ZIP URL:</th>
                    <td><input type="text" name="github_zip_url" value="<?php echo esc_attr($github_zip_url); ?>" />
                    <p class="description">Ingrese la URL directa al archivo ZIP del tema en GitHub, por ejemplo: <code>https://github.com/tuusuario/tutema/archive/refs/heads/main.zip</code></p></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Registrar los campos del token de GitHub y las URLs en la base de datos
add_action('admin_init', 'HBiblia_registrar_opciones_github');
function HBiblia_registrar_opciones_github() {
    register_setting('HBiblia-api-settings-group', 'github_access_token');
    register_setting('HBiblia-api-settings-group', 'github_repo_url');
    register_setting('HBiblia-api-settings-group', 'github_zip_url');
}

// Función para obtener el token de GitHub desde la base de datos
function get_github_access_token() {
    return get_option('github_access_token');
}

// Función para obtener la URL del repositorio desde la base de datos
function get_github_repo_url() {
    return get_option('github_repo_url');
}

// Función para obtener la URL del archivo ZIP desde la base de datos
function get_github_zip_url() {
    return get_option('github_zip_url');
}

// Función corregida para obtener la versión actual del tema
function get_current_theme_version() {
    $theme_data = wp_get_theme(); // Obtiene los datos del tema actual (como nombre, versión, etc.)
    return $theme_data->get('Version'); // Retorna la versión actual del archivo style.css
}

// Verificación de actualización de temas en GitHub
if (!function_exists('get_github_remote_version')) {
    function get_github_remote_version() {
        $token = get_github_access_token();
        $repo_url = get_github_repo_url();

        if (empty($token) || empty($repo_url)) {
            return get_current_theme_version(); // Si no hay token o URL, no se puede comprobar
        }

        $response = wp_remote_get($repo_url . '/releases/latest', array(
            'headers' => array(
                'Authorization' => 'token ' . $token,
            ),
        ));

        if (is_wp_error($response)) {
            return get_current_theme_version(); // Usa la versión actual en caso de error
        }

        $body = wp_remote_retrieve_body($response);
        preg_match('/tag\/v([0-9.]+)/', $body, $matches);
        
        if (!empty($matches[1])) {
            return $matches[1]; // Retorna la versión remota
        }

        return get_current_theme_version(); // Si no se encuentra la versión remota
    }
}

// Verificación y actualización del tema desde GitHub
add_filter('pre_set_site_transient_update_themes', 'check_for_github_updates');
function check_for_github_updates($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    $current_version = get_current_theme_version();
    $remote_version = get_github_remote_version();
    $zip_url = get_github_zip_url();

    if (version_compare($current_version, $remote_version, '<') && !empty($zip_url)) {
        $theme_data = wp_get_theme();
        $transient->response[$theme_data->get_stylesheet()] = array(
            'new_version' => $remote_version,
            'url'         => get_github_repo_url(),
            'package'     => $zip_url
        );
    }

    return $transient;
}

add_action('in_theme_update_message', 'github_theme_update_message', 10, 2);
function github_theme_update_message($data, $response) {
    if (isset($response['theme']) && $response['theme'] === wp_get_theme()->get_stylesheet()) {
        echo '<p><strong>Una nueva versión del tema está disponible en GitHub.</strong></p>';
    }
}
