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
  					'1.0'
  					 );
}
add_action( 'wp_enqueue_scripts', 'hb_bootstrap_css');

// Incluir Bootstrap JS y dependencia popper
function hb_bootstrap_js() {
	wp_enqueue_script( 'popper_js', 
  					'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js', 
  					array(), 
  					'2.11.8', 
  					true); 
	wp_enqueue_script( 'bootstrap_js', 
  					'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js', 
  					array('jquery','popper_js'), 
  					'5.3.2', 
  					true); 
}
add_action( 'wp_enqueue_scripts', 'hb_bootstrap_js');

// This function enqueues the Normalize.css for use. The first parameter is a name for the stylesheet, the second is the URL. Here we
// use an online version of the css file.
function hb_add_normalize_CSS() {
   wp_enqueue_style( 'normalize-styles', "https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css");
}
add_action('wp_enqueue_scripts', 'hb_add_normalize_CSS');

// Register a new navigation menu
function hb_add_main_nav() {
  register_nav_menu('header-menu',__( 'Menú Superior' ));
}
// Hook to the init action hook, run our navigation menu function
add_action( 'init', 'hb_add_main_nav' );

function hb_add_additional_class_on_a($classes, $item, $args)
{
    if (isset($args->add_a_class)) {
        $classes['class'] = $args->add_a_class;
    }
    return $classes;
}

add_filter('nav_menu_link_attributes', 'hb_add_additional_class_on_a', 1, 3);

add_theme_support( 'custom-logo' );

add_filter( 'get_custom_logo_image_attributes', function(  
$custom_logo_attr, $custom_logo_id, $blog_id  )
{
    $custom_logo_attr['class'] = 'hb-your-custom-class-logo img-fluid';

    return $custom_logo_attr;
} ,10,3);

add_theme_support('post-thumbnails');

include_once 'inc/shortcode.php';


/*
 * Página de Administración
 */

add_action('admin_menu', 'HBiblia_agregar_menu_principal');

function HBiblia_agregar_menu_principal() {
    add_menu_page(
        'Tema HBiblia', // Título de la página
        'Tema HBiblia', // Título del menú
        'manage_options', // Capacidad requerida
        'admin-hbiblia', // Slug del menú
        'HBiblia_escritorio_page', // Función que renderiza la página del escritorio
        'dashicons-admin-generic', // Icono del menú
        3 // Posición en el menú
    );
    
    // Crea el submenú 'Generales'
    add_submenu_page(
        'admin-hbiblia', // Slug del menú principal
        'Generales', // Título de la página
        'Generales', // Título del menú
        'manage_options', // Capacidad requerida
        'admin-hbiblia', // Slug del submenú (igual al menú principal para que actúe como la página de inicio del menú)
        'HBiblia_escritorio_page' // Función para renderizar la página
    );
}

function HBiblia_escritorio_page() {
    ?>
    <div class="wrap">
        <h1>Bienvenido a Tema HBiblia</h1>
        <p>Configuraciones generales del tema HBiblia.</p>
    </div>
    <?php
}


// Submenú para Google Analytics
add_action('admin_menu', 'HBiblia_agregar_submenu_analytics');

function HBiblia_agregar_submenu_analytics() {
    add_submenu_page(
        'admin-hbiblia', // Slug del menú principal
        'Configuración de Google Analytics', // Título de la página
        'Google Analytics', // Título del menú
        'manage_options', // Capacidad requerida
        'hbiblia-google-analytics', // Slug del submenú
        'HBiblia_google_analytics_page' // Función que renderiza la página
    );
}

function HBiblia_google_analytics_page() {
    // ... Código de la página de Google Analytics ... ?>
        
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
    </div><?php
}

add_action('admin_init', 'HBiblia_registrar_opciones');

function HBiblia_registrar_opciones() {
    register_setting('HBiblia-settings-group', 'google_analytics_id');
}


add_action('wp_head', 'HBiblia_insertar_google_analytics');

function HBiblia_insertar_google_analytics() {
    if (current_user_can('administrator')) {
        // No inserta Google Analytics para los administradores
        return;
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


// Submenú para Google AdSense
add_action('admin_menu', 'HBiblia_agregar_submenu_adsense');

function HBiblia_agregar_submenu_adsense() {
    add_submenu_page(
        'admin-hbiblia', // Slug del menú principal
        'Configuración de AdSense', // Título de la página
        'Google AdSense', // Título del menú
        'manage_options', // Capacidad requerida
        'hbiblia-adsense', // Slug del submenú
        'HBiblia_adsense_page' // Función que renderiza la página
    );
}

function HBiblia_adsense_page() {
    // ... Código de la página de AdSense ... ?>
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
    </div><?php
}

add_action('admin_init', 'HBiblia_registrar_opciones_adsense');

function HBiblia_registrar_opciones_adsense() {
    register_setting('HBiblia-adsense-settings-group', 'adsense_code');
}

add_action('wp_head', 'HBiblia_insertar_adsense');

function HBiblia_insertar_adsense() {
    $adsense_code = get_option('adsense_code');
    if (!empty($adsense_code)) {
        echo $adsense_code; // Asegúrate de que el código de AdSense esté correctamente escapado
    }
}


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

