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
    $store = $_SESSION['store'];
    $cart = $store->getCart();
    global $currencyCode;
    global $currencySymbol;
    if ( isset( $cart ) && !empty( $cart ) ) : ?>
        <section class="checkout">
            <table>
                <thead>
                    <tr>
                        <th>
                            Icon
                        </th>
                        <th>
                            Product Name
                        </th>
                        <th>
                            Product Description
                        </th>
                        <th>
                            Product Price
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $cart as $product ) : ?>
                        <tr>
                            <td>
                                <svg viewBox="0 0 149.652 103.551">
                                    <use xlink:href="#<?= $product['icon'] ?>"></use>
                                </svg>
                            </td>
                            <td>
                                <?= $product['name'] ?>
                            </td>
                            <td>
                                <?= $product['description'] ?>
                            </td>
                            <td>
                                <?= $currencySymbol ?>
                                <?= $product['price'] ?>
                                (<?= $currencyCode ?>)
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <form method="POST" action="<?= site_url() ?>/receipt">
                <input class="button" type="submit" name="submit" value="Pay Now">
            </form>
        </section>
    <?php endif;

endwhile; // End of the loop.
?>

<?php get_footer(); ?>
