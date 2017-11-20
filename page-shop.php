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
            <h2>Gems</h2>
            <ul>
                <?php foreach ( $productsArray as $id => $product ) : ?>
                    <li class="gem">
                        <form method="POST" action="<?= get_the_permalink() ?>">
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
        <section class="products">
            <h2>Premium Subscription</h2>
            <ul>
                <li class="premium">
                    <form method="POST" action="<?= get_the_permalink() ?>">
                        <h3>Premium</h3>
                        <aside>
                            <img src="<?= get_template_directory_uri() . '/assets/img/shop/premium.png' ?>" alt="Premium">
                        </aside>
                        <p>
                            Premium! Learn about it
                            <a href="#">
                                here.
                            </a>
                        </p>
                        <input type="hidden" name="id" value="0">
                        <input type="hidden" name="shop" value="premium">
                        <input class="button" type="submit" name="submit" value="Purchase">
                    </form>
                </li>
            </ul>
        </section>
        <?php if ( $itemList = file_get_contents( get_template_directory_uri() . '/data/items.json' ) ) : ?>
            <section id="product-item-list" class="products">
                <h2>Items</h2>
                <?php $itemList = json_decode( $itemList ); ?>
                <form id="item-filter" action="">
                    <label for="item-type">
                        Type
                        <select name="item-type" id="item-type">
                            <?php
                            $itemTypes = array();
                            foreach ( $itemList as $item ) { $itemTypes[] = $item->type; }
                            $itemTypes = array_unique( $itemTypes );
                            $itemTypes = array_sort( $itemTypes );
                            ?>
                            <option value="ALL">All</option>
                            <?php foreach ( $itemTypes as $itemType ) : ?>
                                <option value="<?= $itemType ?>"><?= ucwords( strtolower( $itemType ) ) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label for="item-category">
                        Category
                        <select disabled name="item-category" id="item-category">
                            <option value="ALL">All</option>
                            <?php
                                $itemCategories = array();
                                foreach ( $itemList as $item ) { $itemCategories[] = $item->category; }
                                $itemCategories = array_unique( $itemCategories );
                                $itemCategories = array_sort( $itemCategories );
                            ?>
                            <?php foreach ( $itemCategories as $itemCategory ) : ?>
                                <option value="<?= $itemCategory ?>"><?= ucwords( strtolower( $itemCategory ) ) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label for="item-search">
                        Search
                        <input type="search" id="item-search" list="item-search-list" placeholder="Search for item...">
                        <datalist name="item-search-list" id="item-search-list">
                            <?php foreach ( $itemList as $item ) : ?>
                                <option value="<?= $item->name ?>" data-item-name="<?= $item->name ?>" data-item-type="<?= $item->type ?>" data-item-category="<?= $item->category ?>"><?= $item->name ?></option>
                            <?php endforeach; ?>
                        </datalist>
                    </label>
                </form>
                <ul data-item-page="1">
                    <?php foreach ( $itemList as $item ) : ?>
                        <?php if ( $item->forSale === TRUE ) : ?>
                            <li class="item" data-item-id="<?= $item->id ?>" data-item-name="<?= $item->name ?>" data-item-type="<?= $item->type ?>" data-item-category="<?= $item->category ?>">
                                <form method="POST" action="<?= get_the_permalink() ?>">
                                    <h3><?= $item->name ?></h3>
                                    <aside>
                                        <img src="<?= site_url( '/images' . $item->path ) ?>" alt="<?= $item->name ?>">
                                    </aside>
                                    <p>
                                        <?= $item->gems ?> Gems
                                        |
                                        <?= $item->coins ?> Coins
                                    </p>
                                    <input type="hidden" name="id" value="<?= $item->id ?>">
                                    <input type="hidden" name="shop" value="item">
                                    <input class="button" type="submit" name="submit" value="Pay Gems">
                                    <input class="button" type="submit" name="submit" value="Pay Coins">
                                </form>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>
    <?php endif;

endwhile; // End of the loop.
?>

<script src="//code.jquery.com/jquery-1.11.3.js"></script>
<script type="text/javascript">
    (function($){
        var $productItemList    = $('#product-item-list'),
            $itemType           = $('#item-type'),
            $itemCategory       = $('#item-category'),
            $itemSearch         = $('#item-search'),
            $items              = $('.products .item'),
            $itemButtons        = $('.products .item [type="submit"]');

        // Change Visible Types:
        $itemType.on( 'change', function(e) {
            if ( $itemType.val() == 'ALL' ) {
                $items.show();
            } else {
                $items.each( function(e) {
                    if ( $(this).attr('data-item-type') == $itemType.val() ) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });

        // Text Search:
        $itemSearch.on( 'keypress', function(e) {
            $items.each(function(e) {
                if ( ( ( $(this).attr('data-item-type') == $itemType.val() ) || $itemType.val() == 'ALL' ) && ( $(this).attr('data-item-name').toLowerCase().indexOf($itemSearch.val().toLowerCase()) !== -1 ) ) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        } );

//        itemPagination();

        // Pagination Function:
//        function itemPagination() {
//            var itemsPerPage        = 8,
//                numberOfItems       = $items.is(':visible').length,
//                totalNumberOfItems  = $items,
//                pages               = numberOfItems / itemsPerPage,
//                currentItems        = $productItemList.attr('data-item-page') * itemsPerPage;
//
//            $( '.item:visible:first-child' );
//
//            $productItemList.remove('#product-item-list-pagination');
//            $productItemList.append('<nav id="product-item-list-pagination"><ul>');
//            for ( i=1; i<pages; i++ ) {
//                $productItemList.append('<li class="pagination-button button">' + i + '</li>');
//            }
//            $productItemList.append('</ul></nav>');
//        }
    })(jQuery)
</script>

<?php get_footer(); ?>
