<?php get_header(); ?>

<main class="wrap">
    
    <section class="content-area content-thin">
        
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article class="article-loop">
            <header>
            <h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                By: INDEX <?php the_author(); ?>
            </header>
        <?php the_excerpt(); ?>
        </article>
<?php endwhile; ?>
<?php endif; ?>
        
    </section>
    
</main>



<?php get_footer(); ?>