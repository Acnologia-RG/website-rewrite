<?php
function contributors( $echo=FALSE ) {
    if ($echo != FALSE) $echo = TRUE;
    if (have_rows('contributors')) : ob_start(); ?>
        <section class="contributors-container">
            <h2>Contributors</h2>
            <ul class="contributors">
                <?php while (have_rows('contributors')): the_row(); ?>
                    <li class="contributor">
                        <?php
                        $userInfo = array(
                            'username' => get_sub_field('contributor_name'),
                            'avatar' => wp_get_attachment_image(get_sub_field('contributor_image')),
                            'link' => get_sub_field('contributor_link'),
                            'role' => get_sub_field('contributor_role'),
                        );
                        if (get_sub_field('contributor_discord_user')) {
                            $userObject = new User((int)get_sub_field('contributor_discord_id'));
                            $userInfo['username'] = $userObject->username;
                            $userInfo['avatar'] = '<img alt="' . $userObject->username . '" src="' . $userObject->avatarURL . '">';
                        }
                        ?>
                        <?php if ($userInfo['link']) echo '<a target="_blank" href="' . $userInfo['link'] . '">'; ?>
                        <?php echo $userInfo['avatar']; ?>
                        <h3>
                            <?php echo $userInfo['username']; ?>
                        </h3>
                        <p>
                            <?php echo $userInfo['role']; ?>
                        </p>
                        <?php if ($userInfo['link']) echo '</a>'; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
    <?php endif;
    $output = ob_get_clean();
    if ($echo) echo $output;
    return $output;
}