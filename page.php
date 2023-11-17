<?php get_header(); ?>


<!-- Contenido Principal (Main) -->
<main class="container py-5">
    <div class="row">
        <?php 
        if (have_posts()) : 
            while (have_posts()) : the_post();
                // Asegúrate de que la imagen destacada está disponible
                if (has_post_thumbnail()) : ?>
                    <div class="col-md-4">
                        <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                    </div>
                <?php endif; ?>

                <div class="col-md-8 fs-5">
                    <h1 class="h1 mb-3 display-4"><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                </div>
            <?php endwhile;
        endif;
        ?>
    </div>
</main>


<?php get_footer(); ?>