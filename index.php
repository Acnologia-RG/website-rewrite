<?php get_header(); ?>

<?php
while ( have_posts() ) : the_post();
?>

    <section class="feature">
        <?php the_post_thumbnail( 'feature' ); ?>
        <a class="button-hollow" href="#">Learn More</a>
    </section>

    <article>
        <?php the_content(); ?>
    </article>

<?php
endwhile; // End of the loop.
?>

<?php get_footer(); ?>
