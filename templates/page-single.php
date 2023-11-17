<?php
/*
Template Name: Página Single
*/

get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

    if(get_post_meta(get_the_ID(), 'hb_idyoutube_post', true)){ ?>

        <div class="container-sm px-0 p-sm-3">
            <div class="ratio ratio-16x9 shadow-sm">
                <iframe src="https://www.youtube.com/embed/<?php echo get_post_meta(get_the_ID(), 'hb_idyoutube_post', true); ?>?rel=0" title="YouTube video" allowfullscreen></iframe>
            </div>
        </div>    

    <?php } ?>



<main class="container">    
    <section class="content-area content-full-width my-3 mx-xxl-5 px-xxl-5">    
        <article class="article-full px-xl-5 mx-xl-5 px-lg-5">
            
            <header class="text-center">
                <h1 class="fw-bold h1 lh-lg"><?php the_title(); ?></h1>
            </header>
            
            <div class="fs-5 fw-normal lh-base">                
                <?php the_content(); ?>                
            </div>
            
            <div class="row text-center py-5" id="next-historias-footer">
                <?php if(get_post_meta(get_the_ID(), 'url_de_historia_anterior', true)){ ?>
                    
                    <div class="col">
                        <div class="list-group">
                            <a href="<?php echo get_post_meta(get_the_ID(), 'url_de_historia_anterior', true); ?>" class="list-group-item list-group-item-action list-group-item-warning rounded-0 fs-6 fw-medium py-3">
                                &laquo; Historia Anterior
                            </a>                                
                        </div>
                    </div>

                <?php } ?>
                <?php if(get_post_meta(get_the_ID(), 'url_de_siguiente_historia', true)){ ?>
                    
                    <div class="col">
                        <div class="list-group">
                            <a href="<?php echo get_post_meta(get_the_ID(), 'url_de_siguiente_historia', true); ?>" class="list-group-item list-group-item-action list-group-item-info rounded-0 fs-6 fw-medium py-3">
                                Siguiente Historia &raquo;
                            </a>                                
                        </div>
                    </div>

                <?php } ?>
            </div>
            
        </article>
    </section>
    
    <?php //get_sidebar(); ?>
    
</main>

<?php endwhile; endif; ?>

<?php get_footer(); ?>