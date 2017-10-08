<?php
/**
 * HoroBot Store - shop.php
 */

/**
 * PayPal PHP API Libraries & Settings
 */
require __DIR__ . '/bootstrap.php';

/**
 * Empty the Cart Value
 */
unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Store | Horobot - A Discord Bot</title>
    <meta name="description" content="HoroBot - a very capable and helpful Discord bot">
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://horobot.pw/" />
    <meta property="og:site_name" content="HoroBot" />
    <meta property="og:title" content="HoroBot - Discord Bot" />
    <meta property="og:image" content="./img/icon-small.png" />
    <meta property="og:description" content="HoroBot - a very capable and helpful Discord bot" />
    <meta name="twitter:card" value="HoroBot - a very capable and helpful Discord bot" />
    <?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <div class="container">
        <div class="row" style="margin-top: 8%">
            <hr>
        </div>
        <div class="row">
            <div class="col-md-12 products">
                <h1 class="animated fadeInLeft">Premium Packages</h1>
                <ul>
                    <?php
                    global $productsArray;
                    foreach ( $productsArray as $id => $product ) {
                        ?>
                        <li class="animated fadeInRight">
                            <form method="post" action="prepare-purchase.php">
                                <h3><strong>$<?= $product['price'] ?>(USD)</strong> <?= $product['name'] ?></h3>
                                <p>
                                    <aside>
                                        <img src="img/gem.png" alt="Fox Gem" title="Fox Gem">
                                    </aside>
                                    <?= $product['description'] ?>
                                </p>
                                <input type="hidden" name="id" value="<?= base64_encode( $id ) ?>">
                                <input type="submit" name="submit" value="Purchase">
                            </form>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="mol-md-5">
                    <h1>

                    </h1>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row" style="margin-top: 0%">
                <hr>
            </div>
            <div class="row">
                <div class="mol-md-5">
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/includes/assets.php'; ?>
</body>
</html>
