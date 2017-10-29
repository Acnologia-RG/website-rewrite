<?php
include_once __DIR__ . '/../settings/general.php';
class Channel {
    public $id;
    public $type;

    public function __construct(Integer $recipient) {
        global $discordToken;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/v6/users/@me/channels");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"recipient_id":"' . $recipient . '"}');
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Authorization: Bot $discordToken";
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        $result = json_decode($result);
        $this->id = $result->id;
        $this->type = $result->type;
        if ($this->type != 1) {
            throw new Exception("This isn't a DM channel.");
        }
    }

    function sendMessage(String $content, String $embed) {
        global $discordToken;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/v6/channels/$this->id/messages");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"content":"' . $content . '", "embed":"' . $embed . '"}');
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Authorization: Bot $discordToken";
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
    }
}
