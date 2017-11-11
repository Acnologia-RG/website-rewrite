<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php if ( has_post_thumbnail() ) : ?>
    <section class="feature">
        <?php the_post_thumbnail( 'feature' ); ?>
        <a class="button-hollow" href="#content-area">Learn More</a>
    </section>
    <?php endif; ?>


    <?php $columns = columns(); ?>
    <article id="content-area"<?php echo ( $columns ) ? ' class="contains-columns"' : ''; ?>>
        <?php echo ( $columns ) ? $columns : get_the_content(); ?>
    </article>

    <?php contributors( TRUE ); ?>

<?php endwhile; // End of the loop ?>

<?php get_footer(); ?>
