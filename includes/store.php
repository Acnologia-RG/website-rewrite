<?php

if ( ( (string)$_POST['shop'] === 'gem' ) && isset( $_POST['submit'] ) ) {
    global $productsArray;
    $id = $_POST['id'];
    $store = new Store;
    $store->setCart( array( $productsArray[$id] ) );
}
