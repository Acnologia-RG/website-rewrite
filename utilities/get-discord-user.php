<?php
/**
 * Get Discord User Function - get-discord-user.php
 */

/**
 * Include Dependency
 */
include_once __DIR__ . '/../settings/general.php';

/**
 *  Get Discord User
 *
 * @summary Gets the username and icon of a user (by ID)
 * @param $discordUserId (string) ID of the user you are querying for
 * @return array Data from the user:
 *               'username' => (string) Username of Discord Account
 *               'avatar'   => (string) URI of user's Discord Account avatar image
 */
function getDiscordUser( $discordUserId ) {
    $user = FALSE;

    $discordUserId = str_replace( '.', '', $discordUserId ); // Remove unwanted characters
    if ( is_numeric( $discordUserId ) && !empty( $discordUserId ) ) {
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, "https://discordapp.com/api/v6/users/{$discordUserId}" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "GET" );

        $headers = array();
        $headers[] = "Authorization: Bot {$discordToken}";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec( $ch );
        if ( curl_errno( $ch ) ) {
            echo 'Error:' . curl_error( $ch );
        }
        curl_close( $ch );

        $result = json_decode( $result );
        var_dump($result);
        $user = array(
            'username'  => $result->username,
            'avatar'    => "https://cdn.discordapp.com/avatars/{$result->id}/{$result->avatar}.png",
        );
    }

    return $user;
}
