<?php

use IvanBerger\ServerTimeZone\ServerTimeZone;
use IvanBerger\ServerTimeZone\IPProvider;
use IvanBerger\ServerTimeZone\TimeServiceException;

it('returns real IP from real services', function () {
    $provider = new IPProvider([
        'https://ifconfig.me/ip',
        'http://icanhazip.com',
        'https://api.ipify.org',
    ]);

    $ip = $provider->detectIP();

    expect($ip)->not->toBeNull()
        ->and(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE))
        ->not->toBeFalse();
});

it('returns null from invalid services', function () {
    $provider = new IPProvider([
        'http://invalid-service1.local',
    ]);

    $ip = $provider->detectIP();

    expect($ip)->toBeNull();
});

it('tests real usage', function () {
    try {
        $ip = '62.197.144.67';
        $result = new ServerTimeZone($ip)->getCurrentDateTime();
        expect($result)->toBeInstanceOf(DateTimeInterface::class);
        expect($result->getTimezone()->getName())->toBe('Africa/Algiers');
    } catch (TimeServiceException $e) {
        expect($e->getMessage())->toContain('Failed to get current time');
    }
});