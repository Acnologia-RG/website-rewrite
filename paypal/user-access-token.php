<?php
/**
 * User Access Token - user-access-token.php
 */
if (  !isset( $_SESSION['token'] ) || ( ($currentTime - $_SESSION['token']->creation_time) >= $_SESSION['token']->expires_in ) ) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $userTokenURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);

    $headers = array();
    $headers[] = "Accept: application/json";
    $headers[] = "Accept-Language: en_US";
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        $json = json_decode( $result );
        $_SESSION['token'] = $json;
        $_SESSION['token']->creation_time = $currentTime;
        echo $result;
    }

    curl_close ($ch);
}
