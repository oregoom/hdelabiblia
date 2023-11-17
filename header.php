<!doctype html>
<html <?php language_attributes(); ?>>
    
    <head>
        
        <!--<title><?php bloginfo('name'); ?> &raquo; <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>-->
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Salsa&display=swap" rel="stylesheet">

        
        <?php wp_head(); ?>
    </head>
    <body>
        
        <header>            
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container">
                    <!--<a class="navbar-brand" href="#">Historias de la Biblia</a>-->
                    <?php if ( function_exists( 'the_custom_logo' ) ) {	the_custom_logo(); } ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <?php wp_nav_menu( array( 'header-menu' => 'header-menu',
                                'container' => '',
                                'menu_class' => 'navbar-nav ms-auto mb-2 mb-lg-0',
                                'add_a_class'   => 'nav-link')
                                ); ?>
<!--                      <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                      </form>-->
                    </div>
                  </div>
            </nav>            
        </header>
       