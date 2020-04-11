<?php

namespace Lib\Curl;
use Exception;

class Curl
{
    public $agent = 'Mozilla/5.0';
    public $last_http_code;
    private $headers = [];

    public function setHeader($key, $value)
    {
        $this->headers[$key] = "{$key}: {$value}";
    }

    public function getJson($url)
    {
        $curl = $this->getCurl($url);
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response, true);
        if($json===false)
            throw new Exception("Cant parse json response for ".$response);

        return $json;
    }

    public function post($url, $data=null)
    {
        if(is_array($data))
            $data = http_build_query($data);

        $curl = $this->getCurl($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($curl);

        $this->last_http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        return $response;
    }

    public function postJson($url, $data=null)
    {
        $json = json_decode($this->post($url, $data), true);
        if($json===false)
            throw new Exception("Cant parse json response");
        return $json;
    }

    private function getCurl($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->agent);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        return $curl;
    }

}
