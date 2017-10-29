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

    <?php if( have_rows( 'contributors' ) ): ?>
        <section class="contributors-container">
            <h2>Contributors</h2>
            <ul class="contributors">
                <?php while( have_rows( 'contributors' ) ): the_row(); ?>
                    <li class="contributor">
                        <?php
                            $userInfo = array(
                                'username'  => get_sub_field( 'contributor_name' ),
                                'avatar'    => wp_get_attachment_image( get_sub_field( 'contributor_image' ) ),
                                'link'      => get_sub_field( 'contributor_link' ),
                                'role'      => get_sub_field( 'contributor_role' ),
                            );
                            if ( get_sub_field( 'contributor_discord_user' )  ) {
                                $userObject = new User( (int)get_sub_field( 'contributor_discord_id' ) );
                                $userInfo['username'] = $userObject->username;
                                $userInfo['avatar'] = '<img alt="' . $userObject->username . '" src="' . $userObject->avatarURL . '">';
                            }
                        ?>
                        <?php if ( $userInfo['link'] ) echo '<a target="_blank" href="' . $userInfo['link'] . '">'; ?>
                            <?php echo $userInfo['avatar']; ?>
                            <h3>
                                <?php echo $userInfo['username']; ?>
                            </h3>
                            <p>
                                <?php echo $userInfo['role']; ?>
                            </p>
                        <?php if ( $userInfo['link'] ) echo '</a>'; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php
endwhile; // End of the loop.
?>

<?php get_footer(); ?>
