<?php get_header(); ?>

<div class="container my-lg-5 mt-4">    
    
    <div class="page-header pt-lg-5">
        <h1 class="page-title text-center h1 fw-bold mb-4" style="font-family: 'Salsa',display; font-size: 52px;"><span style="color: #F76305;">Buscar</span> más Historías</h1>

        <div class="mb-4 mx-lg-5 px-lg-5"><div class="mx-lg-5 px-lg-5"><div class="mx-lg-5 px-lg-5">
            <?php echo do_shortcode('[ivory-search id="9107" title="AJAX Search Form HB"]'); ?>
        </div></div></div>

<div class="my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <h2 class="h3 mb-4">Resultados de búsqueda para: <?php echo get_search_query(); ?></h2>
            
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="row mb-2">
                        
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="col-3 col-md-1">
                                <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" class="img-fluid rounded" alt="<?php the_title(); ?>">
                            </div>
                            <div class="col-9 col-md-11">
                                <h2 class="h5"><a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a></h2>
                                <p><?php echo wp_trim_words(get_the_excerpt(), 26, '...'); ?></p>
                            </div>
                        <?php else : ?>
                            <div class="col">
                                <h2 class="h5"><a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a></h2>
                                <p><?php echo wp_trim_words(get_the_excerpt(), 26, '...'); ?></p>
                            </div>
                        <?php endif; ?>  
                        
                    </div>
                <?php endwhile; 
                    global $wp_query;
                    $big = 999999999; // necesita un número improbable

                    $pages = paginate_links(array(
                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format' => '?paged=%#%',
                        'current' => max(1, get_query_var('paged')),
                        'total' => $wp_query->max_num_pages,
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
            
            <?php else : ?>
                <p>No se encontraron resultados.</p>
            <?php endif; ?>
                
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
                <?php dynamic_sidebar( 'sidebar-1' ); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

    </div>
    
</div>
    
<?php get_footer(); ?>
