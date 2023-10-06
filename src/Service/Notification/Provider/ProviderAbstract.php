<?php

namespace App\Service\Notification\Provider;

abstract class ProviderAbstract
{
    public const PROVIDER_KEY = 0;

    public const PROVIDER_NAME = 'undefined';

    /**
     * get provider key
     *
     * @return int
     */
    public function getKey(): int
    {
        return static::PROVIDER_KEY;
    }

    /**
     * get provider name
     *
     * @return string
     */
    public function getName(): string
    {
        return static::PROVIDER_NAME;
    }
}