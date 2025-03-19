<?php

use IvanBerger\ServerTimeZone\IPProvider;

it('can be instantiated with empty services array', function () {
    $provider = new IPProvider();
    expect($provider)->toBeInstanceOf(IPProvider::class);
});

it('can be instantiated with services array', function () {
    $services = ['http://service1.com', 'http://service2.com'];
    $provider = new IPProvider($services);
    expect($provider)->toBeInstanceOf(IPProvider::class);
});

it('returns null for invalid IP', function () {
    $provider = new IPProvider();
    $reflection = new ReflectionClass($provider);
    $method = $reflection->getMethod('fetchIP');
    $method->setAccessible(true);

    $result = $method->invoke($provider, 'http://invalid-service.com');
    expect($result)->toBeNull();
});
