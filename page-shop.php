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
    <svg>
        <use xlink:href="#gem-orange"></use>
    </svg>

    <?php // Display Products
    global $productsArray;
    if ( isset( $productsArray ) && !empty( $productsArray ) ) : ?>
    <section class="products">
        <ul>
            <?php foreach ( $products as $product ) : ?>
                <li>
                    <form method="POST" action="#">
                        <h3>
                            <?= $product['name'] ?>
                            <?= $product['price'] ?>
                            (EUR)
                        </h3>
                        <p>
                            <aside>
                                <img src="" alt="Fox Gem" title="Fox Gem">
                            </aside>
                        </p>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif;

endwhile; // End of the loop.
?>

<?php get_footer(); ?>
