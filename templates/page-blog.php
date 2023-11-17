<?php
/*
Template Name: Blog
*/

get_header(); ?>

<div class="container my-5">
    
    <?php while ( have_posts() ) : the_post(); 
        if(get_post_meta(get_the_ID(), 'historias_biblicas', true)){ 
            $periodoBiblico = get_post_meta(get_the_ID(), 'historias_biblicas', true);
        } else {
            $periodoBiblico = "";
        } ?>
    
        <div class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        </div>
    <?php endwhile; 


$args = array(
    'post_type' => array( 'post', 'page' ),
    'post_status' => 'publish',
    'meta_query' => array(
                array(
                    'key' => 'categoria_de_historia',
                    'value' => $periodoBiblico, // Valor a buscar
                    'compare' => '==', // Comparación: NOT EQUAL
                )
            ),
    'posts_per_page' => -1, // Esto recupera todos los posts
);
$query = new WP_Query($args);

if ($query->have_posts()): ?>
    <div class="mt-5">
        <div class="row">
            <?php while ($query->have_posts()): $query->the_post(); ?>
                <div class="col-md-3 mb-4">
                    <a href="<?php the_permalink(); ?>" class="card-link text-decoration-none">
                        <div class="card h-100 shadow-sm">
                            <?php if (has_post_thumbnail()): ?>
                                <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top img-fluid" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php the_title(); ?></h5>
                                <p class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 13, '...'); ?></p>
                            </div>
                        </div>
                    </a>
                </div>            
            
            <?php endwhile; ?>
            
        </div>
    </div>
<?php endif;
wp_reset_postdata();

?></div><?php
get_footer();
?>
