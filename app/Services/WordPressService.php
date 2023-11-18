<?php

namespace App\Services;

use App\Exceptions\ToastException;

class WordPressService {

    private function buildUrl($website, $endpoint)
    {
        // Remove any query parameters or anchor links from the website
        $website = strtok($website, '?#');

        // Trim trailing slashes from the website and leading slash from the endpoint
        $website = rtrim($website, '/');
        $endpoint = ltrim($endpoint, '/');

        // Build the URL using Laravel's url() helper
        $url = url($website . '/' . $endpoint);

        return $url;
    }

    private function makeRequest($website, $endpoint, $username, $app_password) {
        $url = $this->buildUrl($website, $endpoint);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $app_password);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode != 200) {
            if(isset($data['message'])) {
                throw new ToastException($data['message']);
            }
        }

        return $data;
    }

    public function getTags($website, $username, $app_password) {
        return $this->makeRequest($website, '/?rest_route=/wp/v2/tags', $username, $app_password);
    }

    public function getCategories($website, $username, $app_password) {
        return $this->makeRequest($website, '/?rest_route=/wp/v2/categories', $username, $app_password);
    }

    public function getAuthors($website, $username, $app_password) {
        return $this->makeRequest($website, '/?rest_route=/wp/v2/users', $username, $app_password);
    }

    public function getStatuses($website, $username, $app_password) {
        return $this->makeRequest($website, '/?rest_route=/wp/v2/statuses', $username, $app_password);
    }


}
