<?php

namespace App\Http\Controllers;

class Fetcher
{

    public function get(string $link = '', array $params = [])
    {
        $url = $link . '?' . http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        return json_decode($response, true);
    }

}
