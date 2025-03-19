<?php

namespace IvanBerger\ServerTimeZone;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;

/**
 * Class ServerTimeZone
 *
 * This class provides functionality to get the current server time based on the provided IP address.
 */
class ServerTimeZone
{
    /**
     * @var string|null $ip The IP address to get the time for, can be null.
     */
    public function __construct(private readonly ?string $ip = null)
    {
    }

    /**
     * Gets the current server time.
     *
     * @return DateTimeInterface The current server time as a DateTimeImmutable object.
     * @throws TimeServiceException If there is an error getting the current time.
     */
    public function getCurrentDateTime(): DateTimeInterface
    {
        try {
            $timeData = new TimeProvider()->getTimeByIp($this->ip);
            return new DateTimeImmutable($timeData['datetime'])->setTimezone(new DateTimeZone($timeData['timezone']));
        } catch (Exception $e) {
            throw new TimeServiceException('Failed to get current time: ' . $e->getMessage(), 0, $e);
        }
    }
}