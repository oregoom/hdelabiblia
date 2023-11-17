<?php

/*
 * =================================================================================
 * Shortcode para mostrar historias de Cada Personajes Bíblico del AT
 */
function id_hb_shortcode_per_at($ID_Cat){ 
    
    ob_start ();


    $shortcode_query = new WP_Query( array(
                            'cat' => $ID_Cat['id_cat'],
                            'order'   => 'ASC',
                            'posts_per_page' => -1
    ));

    if($shortcode_query->have_posts()){ ?>

    <div class="row py-4"><?php

        while($shortcode_query->have_posts()) : $shortcode_query->the_post();

            if(has_post_thumbnail()){ ?>
        
                <div class="col-md-6 mb-4">
                    <a href="<?php the_permalink(); ?>" class="card-link text-decoration-none">
                        <div class="card h-100 shadow-sm">
                            <?php if (has_post_thumbnail()): ?>
                                <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php the_title(); ?></h5>
                                <p class="card-text fs-6"><?php echo wp_trim_words(get_the_excerpt(), 13, '...'); ?></p>
                            </div>
                        </div>
                    </a>
                </div><?php                    

            }

        endwhile;
        $shortcode_query->reset_postdata();?>

    </div><?php

        }
    
    return ob_get_clean ();
    
}
add_shortcode('id_hb_shortcode_per_at', 'id_hb_shortcode_per_at');



/*
 * =================================================================================
 * Shortcode para mostrar historias destacadas en Home
 */
function hb_id_post_destacado_shortcode_home($atts){ 
    
    ob_start ();
    
    // Extraer los atributos del shortcode
    $atts = shortcode_atts(array(
        'id_post' => '', // IDs por defecto como una cadena vacía
    ), $atts);

    // Convertir la cadena de IDs en un array
    $post_ids = explode(',', $atts['id_post']);
    
    // Lista de IDs de posts y páginas que quieres obtener
//    $post_ids = array(1, 2, 3, 4, 5); // Reemplaza con los IDs de tus posts y páginas

    $shortcode_query = new WP_Query( array(
                            'order'   => 'ASC',
                            'post_type' => array('post', 'page'), // Incluir tanto posts como páginas
                            'post__in' => $post_ids, // Array de IDs de posts y páginas
                            'orderby' => 'post__in', // Ordenar por el orden de los IDs en el array
                            'posts_per_page' => -1, // Para asegurar que no se limiten los resultados
    ));

    if($shortcode_query->have_posts()){ ?>

    <div class="row py-4"><?php

        while($shortcode_query->have_posts()) : $shortcode_query->the_post();

            if(has_post_thumbnail()){ ?>
        
                <div class="col-md-3 mb-4">
                    <a href="<?php the_permalink(); ?>" class="card-link text-decoration-none">
                        <div class="card h-100 shadow-sm">
                            <?php if (has_post_thumbnail()): ?>
                                <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top img-fluid" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php the_title(); ?></h5>
                                <p class="card-text fs-6"><?php echo wp_trim_words(get_the_excerpt(), 13, '...'); ?></p>
                            </div>
                        </div>
                    </a>
                </div><?php                    

            }

        endwhile;
        $shortcode_query->reset_postdata();?>

    </div><?php

        }
    
    return ob_get_clean ();
    
}
add_shortcode('hb_id_post_destacado_shortcode_home', 'hb_id_post_destacado_shortcode_home');


/*
 * =================================================================================
 * Shortcode para mostrar historias Para Niños, Adultos y Jóvenes
 */
function hb_id_post_shortcode_home($atts){ 
    
    ob_start ();
    
    // Extraer los atributos del shortcode
    $atts = shortcode_atts(array(
        'id_post' => '', // IDs por defecto como una cadena vacía
    ), $atts);

    // Convertir la cadena de IDs en un array
    $post_ids = explode(',', $atts['id_post']);
    
    // Lista de IDs de posts y páginas que quieres obtener
//    $post_ids = array(1, 2, 3, 4, 5); // Reemplaza con los IDs de tus posts y páginas

    $shortcode_query = new WP_Query( array(
                            'order'   => 'ASC',
                            'post_type' => array('post', 'page'), // Incluir tanto posts como páginas
                            'post__in' => $post_ids, // Array de IDs de posts y páginas
                            'orderby' => 'post__in', // Ordenar por el orden de los IDs en el array
                            'posts_per_page' => -1, // Para asegurar que no se limiten los resultados
    ));

    if($shortcode_query->have_posts()){ ?>

    <div class="row py-4"><?php

        while($shortcode_query->have_posts()) : $shortcode_query->the_post();

            if(has_post_thumbnail()){ ?>
        
                <div class="col-6 col-md-3 mb-4">
                    <a href="<?php the_permalink(); ?>" class="card-link text-decoration-none">
                        <div class="card h-100 shadow-sm">
                            <?php if (has_post_thumbnail()): ?>
                                <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php the_title(); ?></h5>
                                <p class="card-text fs-6"><?php echo wp_trim_words(get_the_excerpt(), 13, '...'); ?></p>
                            </div>
                        </div>
                    </a>
                </div><?php                    

            }

        endwhile;
        $shortcode_query->reset_postdata();?>

    </div><?php

        }
    
    return ob_get_clean ();
    
}
add_shortcode('hb_id_post_shortcode_home', 'hb_id_post_shortcode_home');