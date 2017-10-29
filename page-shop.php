<?php
get_header();
?>

<?php
while ( have_posts() ) : the_post();
    ?>

    <?php if ( has_post_thumbnail() ): ?>
        <section class="feature">
            <?php the_post_thumbnail(); ?>
            <a class="button-hollow" href="#">Learn More</a>
        </section>
    <?php endif; ?>

    <article>
        <?php the_content(); ?>
    </article>

    <?php
endwhile; // End of the loop.
?>

<?php get_footer(); ?>
