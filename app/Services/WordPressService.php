<?php

namespace App\Services;

# curl -X GET -H "Content-Type: application/json" -u 'abodandnazim:q5oJBwUR64ZW26orJmMLj2ov' https://luggagenboxes.com/wp-json/wp/v2/tags

class WordPressService {
    public static function makeRequest($url, $username, $app_password) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $app_password);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode == 200) {
            $data = json_decode($response, true);
            // Handle the data here
        } else {
            // Handle the error here
        }

        curl_close($ch);

        return $data;
    }

    public static function getTags($website, $username, $app_password) {
        $url = $website . '/wp-json/wp/v2/tags';
        return self::makeRequest($url, $username, $app_password);
    }

    public static function getCategories($website, $username, $app_password) {
        $url = $website . '/wp-json/wp/v2/categories';
        return self::makeRequest($url, $username, $app_password);
    }

    public static function getUsers($website, $username, $app_password) {
        $url = $website . '/wp-json/wp/v2/users';
        return self::makeRequest($url, $username, $app_password);
    }

    public static function getStatus($website, $username, $app_password) {
        $url = $website . '/wp-json/wp/v2/statuses';
        return self::makeRequest($url, $username, $app_password);
    }
}
