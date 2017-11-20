<?php

// Enforce object type: Store
$_SESSION['store'] = convertObjectToObject( $_SESSION['store'], 'Store' );

// Page/Post logic must execute in scope of WordPress Loop
add_action( 'wp_enqueue_scripts', function(){
    global $post;
    global $productsArray;
    $shopPage = get_page_by_title( 'Shop' );
    $checkoutPage = get_page_by_title( 'Checkout' );
    $receiptPage = get_page_by_title( 'Receipt' );

    // Handle Request from PayPal
    if ( $post->ID == $checkoutPage->ID ) {
        /**
         * Output Buffering
         *
         * Buffers the entire WP process, capturing the final output for manipulation.
         */

        ob_start();

        add_action('shutdown', function() {
            $final = '';
            $levels = ob_get_level();
            for ($i = 0; $i < $levels; $i++) {
                $final .= ob_get_clean();
            }
            // Apply any filters to the final output
            echo apply_filters('final_output', $final);
        }, 0);

        add_filter('final_output', function($output) {
            return ( $json = Store::storeActions() ) ? $json : $output;
        });
    }

    // Handle Cart Submission ( Shop to Checkout )
    if ( ( $post->ID === $shopPage->ID ) && ( (string)$_POST['shop'] === 'gem' ) && isset( $_POST['submit'] ) ) {
        $id = $_POST['id'];
        $store = new Store;
        $store->setCart( array( $id => $productsArray[$id] ) );
        header( 'Location: ' . site_url() . '/' . $checkoutPage->post_name, TRUE, 302 );
        exit();
    }

    // Handle Cart Submission ( Checkout to Receipt )
    if ( ( $post->ID === $checkoutPage->ID ) && ( (string)$_POST['shop'] !== 'gem' ) && isset( $_POST['submit'] ) ) {
        header( 'Location: ' . site_url() . '/' . $receiptPage->post_name, TRUE, 302 );
        exit();
    }

    // Handle PayPal response 'executePayment'
    if ( ( $post->ID === $checkoutPage->ID ) && ( $_GET['executePayment'] === 'TRUE' ) ) {

    }

    // Handle PayPal response 'cancelPayment'
    if ( ( $post->ID === $checkoutPage->ID ) && ( $_GET['cancelPayment'] === 'TRUE' ) ) {
        unset( $_SESSION['store'], $_SESSION['store-cart'], $_SESSION['store-status'] );
        header( 'Location: ' . site_url() . '/' . $shopPage->post_name, TRUE, 302 );
        exit();
    }

    // Handle Corrupt/Empty Cart on Checkout
    if ( ( $post->ID === $checkoutPage->ID  ) && !( $_SESSION['store'] instanceof Store ) ) {
        unset( $_SESSION['store'], $_SESSION['store-cart'], $_SESSION['store-status'] );
        header( 'Location: ' . site_url() . '/' . $shopPage->post_name, TRUE, 302 );
        exit();
    }
});