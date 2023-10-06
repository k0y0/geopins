<?php

namespace App\Service\Notification\Provider\Collection;

use App\Service\Notification\Provider\Mail\Mailer\Mailer;
use App\Service\Notification\Provider\ProviderAbstract;

class ProviderCollection
{
    /**
     * @var array
     */
    private $data = [];

    public function __construct(
        Mailer $mailer,
    ) {
        $this->data[$mailer->getKey()] = $mailer;
    }

    /**
     * get provider by key
     *
     * @param int $key
     *
     * @return ProviderAbstract|null
     */
    public function getProvider(int $key): ?ProviderAbstract
    {
        return $this->data[$key] ?? null;
    }

    /**
     * get provider by name
     *
     * @param string $name
     *
     * @return ProviderAbstract|null
     */
    public function getProviderByName(string $name): ?ProviderAbstract
    {
        foreach ($this->data as $provider) {
            if (mb_strtolower($provider->getName()) == mb_strtolower($name)) {
                return $provider;
            }
        }
        return null;
    }

    /**
     * get provider list
     *
     * @return ProviderAbstract[]
     */
    public function getProviderList(): array
    {
        return $this->data;
    }
}