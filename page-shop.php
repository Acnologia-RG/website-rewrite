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

    <?php // Display Products
    global $productsArray;
    global $currencySymbol;
    global $currencyCode;
    if ( isset( $productsArray ) && !empty( $productsArray ) ) : ?>
    <section class="products">
        <ul>
            <?php foreach ( $productsArray as $id => $product ) : ?>
                <li>
                    <form method="POST" action="<?= site_url() ?>/checkout">
                        <h3>
                            <?= $product['name'] ?>
                            <?= $currencySymbol ?>
                            <?= $product['price'] ?>
                            (<?= $currencyCode ?>)
                        </h3>
                        <aside>
                            <svg viewBox="0 0 149.652 103.551">
                                <use xlink:href="#<?= $product['icon'] ?>"></use>
                            </svg>
                        </aside>
                        <p>
                            <?= $product['description'] ?>
                        </p>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="shop" value="gem">
                        <input class="button" type="submit" name="submit" value="Purchase">
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif;

endwhile; // End of the loop.
?>

<?php get_footer(); ?>
