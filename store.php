<?php
/**
 * HoroBot Store - store.php
 */

/**
 * HoroBot / PayPal API Settings
 */
require __DIR__ . '/store/settings.php';

/**
 * Empty the Cart Value
 */
unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Store | Horobot - A Discord Bot</title>
</head>
<body><?= var_dump($_SESSION['payment'],FALSE) ?>
    <h1>Premium Packages</h1>
    <ul>
        <?php
            foreach ( $productsArray as $id => $product ) {
                ?>
                    <li>
                        <form method="post" action="payment-details.php">
                            <h3><strong>$<?= $product['price'] ?>(USD)</strong> <?= $product['name'] ?></h3>
                            <p>
                                <?= $product['description'] ?>
                                <br>
                                <input type="hidden" name="id" value="<?= base64_encode( $id ) ?>">
                                <input type="submit" name="submit" value="Purchase">
                            </p>
                        </form>
                    </li>
                <?php
            }
        ?>
    </ul>
</body>
</html>
