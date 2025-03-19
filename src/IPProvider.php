<?php

namespace IvanBerger\ServerTimeZone;

/**
 * Class IPProvider
 *
 * Detect the IP address using various services.
 */
class IPProvider
{
    /**
     * @var array $services List of services to use for IP detection.
     */
    public function __construct(protected array $services = [
        'https://ifconfig.me/ip',
        'http://icanhazip.com',
        'https://api.ipify.org',
    ])
    {
    }

    /**
     * Detects the IP address by querying the provided services.
     *
     * @return string|null The detected IP address or null if no valid IP was found.
     */
    public function detectIP(): ?string
    {
        foreach ($this->services as $service) {
            $ip = $this->fetchIP($service);
            if ($ip) {
                return $ip;
            }
        }
        return null;
    }

    /**
     * Fetches the IP address from a given service.
     *
     * @param string $serviceUrl The service URL to query for the IP address.
     * @return string|null The fetched IP address or null if invalid.
     */
    private function fetchIP(string $serviceUrl): ?string
    {
        $ip = $this->fetchWithCurl($serviceUrl) ?? $this->fetchWithFileGetContents($serviceUrl);
        return $ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ? $ip : null;
    }

    /**
     * Fetches the IP address using cURL.
     *
     * @param string $service The service URL to query for the IP address.
     * @return string|null The fetched IP address or null if cURL is not available or fails.
     */
    private function fetchWithCurl(string $service): ?string
    {
        if (!function_exists('curl_init')) {
            return null;
        }

        $ch = curl_init($service);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $ip = trim(curl_exec($ch));
        curl_close($ch);

        return $ip ?: null;
    }

    /**
     * Fetches the IP address using file_get_contents.
     *
     * @param string $service The service URL to query for the IP address.
     * @return string|null The fetched IP address or null if file_get_contents is not available or fails.
     */
    private function fetchWithFileGetContents(string $service): ?string
    {
        if (!function_exists('file_get_contents')) {
            return null;
        }

        $ip = trim(@file_get_contents($service));
        return $ip ?: null;
    }
}