<?php
/*
Template Name: Divi
*/

get_header(); ?>

<div id="et-main-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <main id="main-content" class="site-main" role="main">
                    <?php
                    // Verificar si Divi Builder está activo
                    if ( function_exists( 'et_pb_is_pagebuilder_used' ) && et_pb_is_pagebuilder_used( get_the_ID() ) ) {
                        // Si el constructor de Divi está habilitado, mostramos el contenido usando el Builder
                        while ( have_posts() ) :
                            the_post();
                            the_content(); // Mostrar contenido generado por Divi
                        endwhile;
                    } else {
                        // Si no, usar el loop estándar de WordPress
                        while ( have_posts() ) :
                            the_post();
                            get_template_part('template-parts/content', 'page'); // Mostrar el contenido de la página
                        endwhile;
                    }
                    ?>
                </main>
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
</div> <!-- #et-main-area -->

<?php get_footer(); ?>

