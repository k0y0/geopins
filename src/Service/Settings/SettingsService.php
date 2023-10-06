<?php

namespace App\Service\Settings;

use App\Entity\Settings\Settings;
use App\Repository\Settings\SettingsRepository;
use App\Utils\Settings\SettingsCollection;

class SettingsService
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * @var SettingsCollection
     */
    private $settingsCollection;

    public function __construct(
        SettingsRepository $settingsRepository,
        SettingsCollection $settingsCollection
    )
    {
        $this->settingsRepository = $settingsRepository;
        $this->settingsCollection = $settingsCollection;
    }

    /**
     * get setting by key
     *
     * @param string $setting
     *
     * @return string|null
     */
    public function getSetting(string $setting): ?string
    {
        $setting = $this->settingsRepository->findOneBy(['name' => $setting]);
        return $setting?->getValue();
    }

    /**
     * get settings
     *
     * @return array
     */
    public function getSettings(): array
    {
        $config = $this->settingsCollection->getData();

        $settings = [];
        $data = $this->settingsRepository->findAll();
        foreach ($config as $name => $setting) {
            $settingData = $setting;
            $settingData['name'] = $name;
            foreach ($data as $item) {
                if ($item->getName() === $name) {
                    $settingData['value'] = $item->getValue();
                    break;
                }
            }
            $settings[] = $settingData;
        }

        return $settings;
    }

    /**
     * set setting
     *
     * @param string $settingName
     * @param string|null $value (default: null)
     *
     * @return void
     */
    public function setSetting(string $settingName, ?string $value = null): void
    {
        $setting = $this->settingsRepository->findOneBy(['name' => $settingName]);
        if (!$setting) {
            $setting = $this->settingsCollection->getSettingConfig($settingName);
            if (!$setting) {
                throw new \InvalidArgumentException('Setting not found');
            }
            $setting = new Settings();
            $setting->setName($settingName);
        }
        $setting->setValue($value);
        $this->settingsRepository->save($setting, true);
    }
}