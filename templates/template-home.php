<?php
/*
Template Name: Home
*/

get_header(); ?>

<div class="container my-lg-5 mt-4">
    
    <?php while ( have_posts() ) : the_post(); ?>
    
        <div class="page-header pt-lg-5">
            <h1 class="page-title text-center h1 fw-bold mb-4" style="font-family: 'Salsa',display; font-size: 52px;"><span style="color: #F76305;">Historias</span> de la Biblia para leer</h1>
            
            <div class="mb-4 mx-lg-5 px-lg-5"><div class="mx-lg-5 px-lg-5"><div class="mx-lg-5 px-lg-5">
                <?php echo do_shortcode('[ivory-search id="112" title="AJAX Search Form HB"]'); ?>
            </div></div></div>
            
            <div class="py-lg-5 px-lg-5 mx-lg-5 text-center fs-5 text">
                <p>Bienvenido a <strong>Historiasdelabiblia.org</strong>, donde descubrirás las historias bíblicas desde los más conocidos hasta aquellos menos explorados, cada historia te espera para revelar su mensaje y enseñanza.</p>
            </div>
            
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        </div>
    <?php endwhile; ?>

</div>
    
<?php get_footer(); ?>
