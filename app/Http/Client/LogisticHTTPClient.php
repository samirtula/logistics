<?php

declare(strict_types=1);

namespace App\Http\Client;

use RuntimeException;

class LogisticHTTPClient
{
    public function get(string $url): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if ($response === false) {
            throw new RuntimeException('cURL Error: ' . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Failed to decode JSON response: ' . json_last_error_msg());
        }

        return $data;
    }
}
