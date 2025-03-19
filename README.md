# Server Time Zone

This is a test project to retrieve the server's current time based on the provided IP address.

## Description

The project includes a `ServerTimeZone` class that provides functionality to get the current server time using the IP address. It uses an external time API to fetch the current time and timezone information.

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/rchitector/ServerTimeZone.git
    ```
2. Navigate to the project directory:
    ```sh
    cd ServerTimeZone
    ```
3. Install the dependencies using Composer:
    ```sh
    composer install
    ```

## Usage

To get the current server time based on the provided IP address, you can use the `ServerTimeZone` class:

```php
use IvanBerger\ServerTimeZone\IPProvider;
use IvanBerger\ServerTimeZone\TimeServiceException;
use IvanBerger\ServerTimeZone\ServerTimeZone;

try {
    $ipProvider = new IPProvider();
    $ip = $ipProvider->detectIP();
    echo 'Current IP detected: ' . $ip . PHP_EOL;
    $serverTime = new ServerTimeZone($ip);
    $cdt = $serverTime->getCurrentDateTime();
    echo 'Current server time detected: ' . $cdt->format('Y-m-d H:i:s P');
} catch (TimeServiceException $e) {
    echo 'Error: '.$e->getMessage();
}