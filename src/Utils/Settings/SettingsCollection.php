<?php

namespace App\Utils\Settings;

use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SettingsCollection
{
    const DEFAULT_INITIAL_FORM = 'default_initial_form';
    const DEFAULT_PERIODIC_FORM = 'default_periodic_form';

    private array $data = [
        self::DEFAULT_INITIAL_FORM => [
            'placeholder' => 'int.',
            'label' => 'Default initial form',
            'formFieldType' => NumberType::class,
        ],
        self::DEFAULT_PERIODIC_FORM => [
            'placeholder' => 'int.',
            'label' => 'Default periodic form',
            'formFieldType' => NumberType::class,
        ],
    ];

    /**
     * get data
     *
     * @return array[]|null
     */
    public function getData(): ?array
    {
        return $this->data ?? null;
    }

    /**
     * get single setting configuration
     *
     * @param string $name
     *
     * @return array|null
     */
    public function getSettingConfig(string $name): ?array
    {
        return $this->data[$name] ?? null;
    }
}