<?php

namespace IvanBerger\ServerTimeZone;

use RuntimeException;

/**
 * Class TimeProvider
 *
 * Getting the current time based on the provided IP address.
 */
class TimeProvider
{
    /**
     * @var string $apiBaseUrl The base URL of the time API.
     */
    public function __construct(private readonly string $apiBaseUrl = 'http://worldtimeapi.org/api/ip/')
    {
    }

    /**
     * Gets the current time and timezone by IP address.
     *
     * @param string|null $ip The IP address to get the time for, can be null.
     * @return array An associative array containing 'datetime' and 'timezone'.
     * @throws RuntimeException If the API request fails or the response format is invalid.
     */
    public function getTimeByIp(?string $ip = null): array
    {
        $url = $this->apiBaseUrl . $ip;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new RuntimeException('Time API request failed: ' . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['datetime'], $data['timezone'])) {
            throw new RuntimeException('Invalid API response format');
        }

        return [
            'datetime' => $data['datetime'],
            'timezone' => $data['timezone'],
        ];
    }
}