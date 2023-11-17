<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php get_header(); ?>


<aside id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
    <?php //dynamic_sidebar( 'sidebar' ); ?>
<div class="container">

    <div class="mb-4">
        
        <h3 class="h4" style="font-family: Raleway, sans-serif;">

            Más historias

        </h3>

        <div class="row pt-3">

                <?php 
                //Post que no deben de mostrar en la consulta
                $NOT_post[] = get_the_ID();

                if(is_single()){ //Si es Index de Post

                    //Consulta que pertenece a una categoria específica 
                    $hb_query_1 = new WP_Query( array( 
                            'cat' => $ID_cat,
                            'orderby' => 'rand',
                            'post_status' => 'publish',
                            'posts_per_page' => 2,
                            'post__not_in' => $NOT_post
                        ));   

                    while ($hb_query_1->have_posts()) : $hb_query_1->the_post(); 

                        if(has_post_thumbnail()){ $NOT_post[] = get_the_ID(); ?>

                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6 mb-4">

                                <div class="mb-3 card border-0 shadow-sm text-center h-100">
                
                                    <a href="<?php echo the_permalink(); ?>"><img src="<?php the_post_thumbnail_url();?>" class="rounded-top"></a>
                
                                    <div class="card-body pt-lg-3 pt-2 pb-0">
                                        <h4 class="card-title h6 mb-2" style="line-height: 1.3em; font-family: Raleway, sans-serif; font-weight: 700; color: #2a3b47;">
                                            <a href="<?php the_permalink(); ?>" class="text-dark"><?php the_title(); ?></a>
                                        </h4>
                                        
                                        <?php //Funcion para extraer 100 caracteres
                                            // hb_excerpt_100_caracteres(get_the_excerpt()); ?>
                                        
                                    </div>
                
                                </div>

                            </div>

                        <!--<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 pb-3"><a class="text-dark" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?><h3 class="h6 pt-2" style="font-family: Raleway, sans-serif;"><strong><?php the_title(); ?></strong></h3></a></div>-->

                        <?php }                    

                    endwhile;

                    wp_reset_postdata(); 
                    
                } ?>

                <?php 
                //Consulta general
                $hb_query_2 = new WP_Query( array( 
                        'orderby' => 'rand',
                        'post_status' => 'publish',
                        'posts_per_page' => 2,
                        'post__not_in' => $NOT_post
                    ));   

                while ($hb_query_2->have_posts()) : $hb_query_2->the_post(); 

                    if(has_post_thumbnail()){ ?>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6 mb-4">

                            <div class="mb-3 card border-0 shadow-sm text-center h-100">

                                <a href="<?php echo the_permalink(); ?>"><img src="<?php the_post_thumbnail_url();?>" class="rounded-top"></a>

                                <div class="card-body pt-lg-3 pt-2 pb-0">
                                    <h4 class="card-title h6 mb-2" style="line-height: 1.3em; font-family: Raleway, sans-serif; font-weight: 700; color: #2a3b47;">
                                        <a href="<?php the_permalink(); ?>" class="text-dark"><?php the_title(); ?></a>
                                    </h4>
                                    
                                    <?php //Funcion para extraer 100 caracteres
                                        //hb_excerpt_100_caracteres(get_the_excerpt()); ?>
                                    
                                </div>

                            </div>

                        </div>

                    <!--<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 pb-3">
                        <a class="text-dark" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?><h3 class="h6 pt-2" style="font-family: Raleway, sans-serif;"><strong><?php the_title(); ?></strong></h3></a>
                    </div>-->

                    <?php }                    

                endwhile;

                wp_reset_postdata(); ?>

        </div>
        
    </div>


    <div class="mb-5 pb-5">
        
        <h3 class="h4" style="font-family: Raleway, sans-serif;">

            Historias del Antiguo Testamento

        </h3>

        <div class="row pt-3 pb-2"><?php 


                    //Consulta que pertenece a una categoria específica 
                    $hb_query_at = new WP_Query( array( 
                            'meta_value' => 'AT',
                            'post_type' => 'page',
                            'orderby' => 'rand',
                            'post_status' => 'publish',
                            'posts_per_page' => 4,
                        ));   

                    while ($hb_query_at->have_posts()) : $hb_query_at->the_post(); 

                        if(has_post_thumbnail()){ ?>

                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6 mb-4">

                                <div class="mb-3 card border-0 shadow-sm text-center h-100">
                
                                    <a href="<?php echo the_permalink(); ?>"><img src="<?php the_post_thumbnail_url();?>" class="rounded-top"></a>
                
                                    <div class="card-body pt-lg-3 pt-2 pb-0">
                                        <h4 class="card-title h6 mb-2" style="line-height: 1.3em; font-family: Raleway, sans-serif; font-weight: 700; color: #2a3b47;">
                                            <a href="<?php the_permalink(); ?>" class="text-dark"><?php the_title(); ?></a>
                                        </h4>
                                        
                                        <?php //Funcion para extraer 100 caracteres
                                            //hb_excerpt_100_caracteres(get_the_excerpt()); ?>
                                        
                                    </div>
                
                                </div>

                            </div>

                        <?php }                    

                    endwhile;

                    wp_reset_postdata(); ?>

        </div>

        <div class="text-center mb-2">

            <a href="<?php echo esc_url(home_url().'/antiguo-testamento/'); ?>" class="btn rounded-pill text-white" style="padding-left: 30px; padding-right: 30px; background-color: #FA6002;">Más historias aquí</a>
                
        </div>
        
    </div>



</div>
    
</aside>


<?php get_footer(); ?>