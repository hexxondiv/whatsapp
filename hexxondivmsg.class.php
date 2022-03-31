<?php

namespace Hexxondiv;

/**
 * Class WhatsAppApi
 * @package Hexxondiv
 */
class WhatsAppApi
{
    protected $token = '';
    protected $instance_id = '';

    /**
     * Ultramsg constructor.
     * @param $token
     * @param $instance_id
     */
    public function __construct($token, $instance_id)
    {
        $this->token = $token;
        $this->instance_id = $instance_id;
        if (ctype_digit($instance_id)) {
            $this->instance_id = "instance" . $instance_id;
        }
    }

    // messages
    public function getMessages($page = 1, $limit = 100, $status = "all", $sort = "asc", $referenceId = '')
    {
        $params = array("page" => $page, "limit" => $limit, "status" => $status, "sort" => $sort, 'referenceId' => $referenceId);
        return $this->sendRequest("GET", "messages", $params);
    }

    public function getMessageStatistics()
    {
        return $this->sendRequest("GET", "messages/statistics");
    }

    public function sendChatMessage($to, $body, $priority = 10, $referenceId = '')
    {
        $params = array("to" => $to, "body" => $body, "priority" => $priority, 'referenceId' => $referenceId);
        return $this->sendRequest("POST", "messages/chat", $params);
    }

    public function sendImageMessage($to, $image, $caption = "", $priority = 10, $referenceId = '')
    {
        $params = array("to" => $to, "caption" => $caption, "image" => $image, "priority" => $priority, 'referenceId' => $referenceId);
        return $this->sendRequest("POST", "messages/image", $params);
    }

    public function sendDocumentMessage($to, $filename, $document, $caption = "", $priority = 10, $referenceId = '')
    {
        $params = array("to" => $to, "filename" => $filename, "document" => $document, "caption" => $caption, "priority" => $priority, 'referenceId' => $referenceId);
        if (empty($caption))
            return $this->sendRequest("POST", "messages/document", $params);
        $params1 = array("to" => $to, "body" => $caption, "priority" => $priority, 'referenceId' => $referenceId);
        $pst = $this->sendRequest("POST", "messages/chat", $params1);
        if(isset($pst['sent'])&&$pst['sent']=='true') {
            return $this->sendRequest("POST", "messages/document", $params);
        }
        return $pst;
    }

    public function sendAudioMessage($to, $audio, $caption = "", $priority = 10, $referenceId = '')
    {
        $params = array("to" => $to, "audio" => $audio, "caption" => $caption, "priority" => $priority, 'referenceId' => $referenceId);
        if (empty($caption))
           return $this->sendRequest("POST", "messages/audio", $params);
        $params1 = array("to" => $to, "body" => $caption, "priority" => $priority, 'referenceId' => $referenceId);
        $pst = $this->sendRequest("POST", "messages/chat", $params1);
        if(isset($pst['sent'])&&$pst['sent']=='true') {
            return $this->sendRequest("POST", "messages/audio", $params);
        }
        return $pst;
    }

    public function sendVoiceMessage($to, $audio, $caption = "", $priority = 10, $referenceId = '')
    {
        $params = array("to" => $to, "audio" => $audio, "caption" => $caption, "priority" => $priority, 'referenceId' => $referenceId);
        if (empty($caption))
           return $this->sendRequest("POST", "messages/voice", $params);
        $params1 = array("to" => $to, "body" => $caption, "priority" => $priority, 'referenceId' => $referenceId);
        $pst = $this->sendRequest("POST", "messages/chat", $params1);
        if(isset($pst['sent'])&&$pst['sent']=='true') {
            return $this->sendRequest("POST", "messages/voice", $params);
        }
        return $pst;
    }

    public function sendVideoMessage($to, $video, $caption = "", $priority = 10, $referenceId = '')
    {
        $params = array("to" => $to, "video" => $video, "caption" => $caption, "priority" => $priority, 'referenceId' => $referenceId);
        return $this->sendRequest("POST", "messages/video", $params);
    }

    public function sendLinkMessage($to, $link, $caption = "", $priority = 10, $referenceId = '')
    {
        $params = array("to" => $to, "link" => $link, "caption" => $caption, "priority" => $priority, 'referenceId' => $referenceId);
        return $this->sendRequest("POST", "messages/link", $params);
    }

    public function sendContactMessage($to, $contact, $priority = 10, $referenceId = '')
    {
        $params = array("to" => $to, "contact" => $contact, "priority" => $priority, 'referenceId' => $referenceId);
        return $this->sendRequest("POST", "messages/contact", $params);
    }

    public function sendLocationMessage($to, $address, $lat, $lng, $priority = 10, $referenceId = '')
    {
        $params = array("to" => $to, "address" => $address, "lat" => $lat, "lng" => $lng, "priority" => $priority, 'referenceId' => $referenceId);
        return $this->sendRequest("POST", "messages/location", $params);
    }

    public function sendVcardMessage($to, $vcard, $priority = 10, $referenceId = '')
    {
        $params = array("to" => $to, "vcard" => $vcard, "priority" => $priority, 'referenceId' => $referenceId);
        return $this->sendRequest("POST", "messages/vcard", $params);
    }

    public function sendClearMessage($status)
    {
        $params = array("status" => $status);
        return $this->sendRequest("POST", "messages/clear", $params);
    }

    // instance

    public function getInstanceStatus()
    {
        return $this->sendRequest("GET", "instance/status");
    }

    public function getInstanceQr()
    {
        return $this->sendRequest("GET", "instance/qr");
    }

    public function getInstanceQrCode()
    {
        return $this->sendRequest("GET", "instance/qrCode");
    }

    public function getInstanceScreenshot($encoding = "")
    {
        return $this->sendRequest("GET", "instance/screenshot", array("encoding" => $encoding));
    }

    public function getInstanceMe()
    {
        return $this->sendRequest("GET", "instance/me");
    }

    public function getInstanceSettings()
    {
        return $this->sendRequest("GET", "instance/settings");
    }


    public function sendInstanceTakeover()
    {
        return $this->sendRequest("POST", "instance/takeover");
    }

    public function sendInstanceLogout()
    {
        return $this->sendRequest("POST", "instance/logout");
    }

    public function sendInstanceRestart()
    {
        return $this->sendRequest("POST", "instance/restart");
    }

    public function sendInstanceSettings($sendDelay, $webhook_url, $webhook_message_received, $webhook_message_create, $webhook_message_ack)
    {

        $params = array("sendDelay" => $sendDelay, "webhook_url" => $webhook_url, "webhook_message_received" => json_encode($webhook_message_received), "webhook_message_create" => json_encode($webhook_message_create), "webhook_message_ack" => json_encode($webhook_message_ack));
        return $this->sendRequest("POST", "instance/settings", $params);
    }

    public function sendInstanceClear()
    {
        return $this->sendRequest("POST", "instance/clear");
    }


    public function sendRequest($method, $path, $params = array())
    {

        if (!is_callable('curl_init')) {
            return array("Error" => "cURL extension is disabled on your server");
        }
        $url = "https://api.ultramsg.com/" . $this->instance_id . "/" . $path;
        $params['token'] = $this->token;
        $data = http_build_query($params);
        if (strtolower($method) == "get") $url = $url . '?' . $data;
        $curl = curl_init($url);
        if (strtolower($method) == "post") {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpCode == 404) {
            return array("Error" => "instance not found or pending please check you instance id");
        }
        $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        curl_close($curl);

        if (strpos($contentType, 'application/json') !== false) {
            return json_decode($body, true);
        }
        return $body;
    }


}