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
    $cart = $_SESSION['store']->getCart();
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
            <div class="paypal-actions">
                <div id="paypal-button"></div>
                <span class="button paypal-button">Paypal Checkout</span>
                <script src="https://www.paypalobjects.com/api/checkout.js"></script>
                <script>
                    var CREATE_PAYMENT_URL  = '<?= get_the_permalink( get_page_by_title( 'Checkout' )->ID ) ?>?createPayment=TRUE';
                    var EXECUTE_PAYMENT_URL = '<?= get_the_permalink( get_page_by_title( 'Checkout' )->ID ) ?>?executePayment=TRUE';

                    paypal.Button.render({

                        env: 'sandbox',
                        commit: true, // Show a 'Pay Now' button

                        payment: function() {
                            return paypal.request.post(CREATE_PAYMENT_URL).then(function(data) {
                                console.log('Data:');
                                console.log(data);
                                console.log('Data ID:');
                                console.log(data.id);
                                return data.id;
                            });
                        },

                        onAuthorize: function(data) {
                            return paypal.request.post(EXECUTE_PAYMENT_URL, {
                                paymentID: data.paymentID,
                                payerID:   data.payerID
                            }).then(function() {
                                // Remove Purchase Button
                                var paypalButton = document.getElementById( 'paypal-button' );
                                paypalButton.parentElement.removeChild( paypalButton );

                                // Create Success Elements/Text
                                var paypalMessageOverlay = document.createElement( 'DIV' );
                                var paypalMessageContainer = document.createElement( 'ARTICLE' );
                                var paypalMessageTitle = document.createElement( 'H2' );
                                var paypalMessageTitleText = document.createTextNode( 'PayPal Transaction Details' );
                                var paypalMessageParagraph = document.createElement( 'P' );
                                var paypalMessageParagraphText = document.createTextNode( 'Your payment was successful, thank you very much!' );
                                var paypalMessageButton = document.createElement( 'A' );
                                var paypalMessageButtonText = document.createTextNode( 'Okay' );

                                // Prepare Success Elements/Text
                                paypalMessageTitle.appendChild( paypalMessageTitleText );
                                paypalMessageContainer.appendChild( paypalMessageTitle );
                                paypalMessageParagraph.appendChild( paypalMessageParagraphText );
                                paypalMessageContainer.appendChild( paypalMessageParagraph );
                                paypalMessageButton.appendChild( paypalMessageButtonText );
                                paypalMessageContainer.appendChild( paypalMessageButton );
                                paypalMessageOverlay.appendChild( paypalMessageContainer );

                                // Add ID to Container
                                paypalMessageOverlay.setAttribute( 'id', 'paypal-message' );

                                // Add Link and Class to Button
                                paypalMessageButton.setAttribute( 'href', '<?= get_the_permalink( get_page_by_title( 'Shop' )->ID ) ?>' );
                                paypalMessageButton.setAttribute( 'class', 'button' );

                                // Add Animation Classes
                                paypalMessageOverlay.setAttribute( 'class', 'fade-in' );
                                paypalMessageContainer.setAttribute( 'class', 'slide-in-from-top' );

                                // Add Event Listener to Button
                                paypalMessageButton.addEventListener( 'click', function(){
                                    window.location = '<?= $url ?>';
                                });

                                // Add Message to HTML Document (wait 1 second)
                                setTimeout(function(){
                                    document.body.appendChild( paypalMessageOverlay );
                                }, 1000);
                            });
                        }

                    }, '#paypal-button');
                </script>
            </div>
        </section>
    <?php endif;
endwhile; // End of the loop.
?>

<?php get_footer(); ?>
