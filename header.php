<!DOCTYPE html>
<html lang="en-US">
<head>
    <title><?php the_title(); ?> | <?php bloginfo( 'name' ); ?></title>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body>
    <header>
        <h1>
            <?php bloginfo( 'name' ); ?>
            <small>
                <?php bloginfo( 'description' ); ?>
            </small>
        </h1>
        <nav>
            <button>Website Navigation</button>
            <?php wp_nav_menu( array( 'theme_location' => 'header_menu' ) ); ?>
        </nav>
    </header>
    <main>