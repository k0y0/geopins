<?php

namespace App\Service\Map\Elements\Element;

use App\Entity\Upload\File;

class Pin implements MapElementInterface
{
    private const HAS_POPUP = true;
    private const HAS_THUMBNAIL = true;

    public string $name;

    public string $longitude;

    public string $latitude;
    private string $filename;

    /**
     * create Pin from file entity
     *
     * @param File $file
     *
     * @return self
     * @throws \Exception
     */
    public static function createFromFile(File $file): self
    {
        $pin = new static();

        $additionalData = $file->getAdditionalData();
        $latitude = $additionalData['latitude'] ?? null;
        $longitude = $additionalData['longitude'] ?? null;

        if (!$latitude || !$longitude) {
            throw new \Exception('cannot create pin from given file');
        }
        $pin->latitude = $additionalData['latitude'];
        $pin->longitude = $additionalData['longitude'];
        $pin->name = $file->getFileName();
        $pin->filename = $file->getFileSrc();

        return $pin;
    }

    /**
     * get coordinates
     *
     * @return string
     */
    public function getCoordinates(): string
    {
        return  $this->latitude . ', ' . $this->longitude;
    }

    /**
     * @return bool
     */
    public function hasPopup(): bool
    {
        return $this::HAS_POPUP;
    }

    /**
     * get popup content
     *
     * @return string
     */
    public function getPopup(): string
    {
        return $this->name;
    }

    /**
     * has thumbnail
     *
     * @return bool
     */
    public function hasThumbnail(): bool
    {
        return $this::HAS_THUMBNAIL;
    }

    /**
     * get fileName
     *
     * @return string
     */
    public function getThumbnail(): string
    {
        return $this->filename;
    }
}