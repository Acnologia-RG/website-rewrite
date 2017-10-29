<?php
include_once __DIR__ . '/../settings/general.php';
class User {
    public $id;
    public $username;
    public $discriminator;
    public $avatar;
    public $avatarURL;

    public function __construct(int $id) {
        global $discordToken;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/v6/users/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $headers = array();
        $headers[] = "Authorization: Bot $discordToken";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        $result = json_decode($result);

        $this->id = (int) $result->id;
        $this->username = (String) $result->username;
        $this->discriminator = (int) $result->discriminator;
        $this->avatar = (String) $result->avatar;
        $this->avatarURL = "https://cdn.discordapp.com/avatars/$this->id/$this->avatar.png";
    }
}
