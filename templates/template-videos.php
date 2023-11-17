<?php
/*
 * Template name: Página de Videos
 * Template Post Type: page
 */
?>
<?php get_header(); ?>

<main class="container">    
    <section class="content-area content-full-width my-3">    
        <article class="article-full mb-5">
            
            <header class="text-center">
                <h1 class="fw-bold h1 lh-lg"><?php the_title(); ?></h1>
            </header>
            
            <div class="my-4">

                <div class="row">

                    <?php            
                    $query_videos_hb = new WP_Query( array (
                            'post_type' => array( 'post', 'page' ),
                            'post_status' => 'publish',
                            //'orderby' => 'rand',
                            'posts_per_page' => 40,
                            'paged' => get_query_var('paged') ? get_query_var('paged') : 1, // Paginación
                            'meta_query' => array(
                                array(
                                    'key' => 'hb_idyoutube_post',
                                    'value' => '', // Valor a buscar
                                    'compare' => '!=', // Comparación: NOT EQUAL
                                )
                            )
                            ));            
                    ?>

                    <?php if($query_videos_hb->have_posts()){ ?>

                    <?php while($query_videos_hb->have_posts()) : $query_videos_hb->the_post();
                    
                        if(get_post_meta(get_the_ID(), 'hb_idyoutube_post', true)){ ?>
                            
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <a href="<?php the_permalink(); ?>">
                                    <img src="https://i.ytimg.com/vi/<?php echo get_post_meta(get_the_ID(), 'hb_idyoutube_post', true); ?>/maxresdefault.jpg" class="img-fluid rounded-3 shadow">
                                    <?php //the_post_thumbnail('post-thumbnail', array( 'class' => "img-fluid" )); ?>
                                </a>

                                <div class="pt-3 m-0 pb-4" style="font-family: Raleway, sans-serif;">
                                    <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                                        <h2 class="p-0 m-0" style="line-height: 0.5em;">
                                            <span class="fs-5 fw-bold"><?php the_title(); ?></span>
                                        </h2>
                                    </a>
                                </div>
                            </div>
                            
                        <?php } 
                        
                    endwhile; ?>
                     
                    <?php
                        $big = 999999999; // necesita un número improbable

                        $pages = paginate_links(array(
                            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $query_videos_hb->max_num_pages,
                            'type'  => 'array',
                            'prev_next' => true,
                            'prev_text' => __('&laquo; Anterior'),
                            'next_text' => __('Siguiente &raquo;'),
                        ));

                        if (is_array($pages)) {
                            $current_page = (get_query_var('paged') == 0) ? 1 : get_query_var('paged');

                            echo '<nav aria-label="Page navigation" class="py-5"><ul class="pagination justify-content-center">';

                            foreach ($pages as $page) {
                                if (strpos($page, 'current') !== false) {
                                    echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $page . '</span></li>';
                                } else {
                                    echo '<li class="page-item">' . str_replace('page-numbers', 'page-link', $page) . '</li>';
                                }
                            }

                            echo '</ul></nav>';
                        } ?>

                    <?php wp_reset_postdata(); ?>      

                    <?php } ?>

                </div>
            
            </div>
            
            <div class="fs-5 fw-normal lh-base">                
                <?php the_content(); ?>                
            </div>
            
        </article>
    </section>
    
    <?php //get_sidebar(); ?>
    
</main>

<?php get_footer(); ?>

