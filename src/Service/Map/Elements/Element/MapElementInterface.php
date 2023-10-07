<?php

namespace App\Service\Map\Elements\Element;

interface MapElementInterface
{
    /**
     * get coordinates
     *
     * @return string
     */
    public function getCoordinates(): string;

    /**
     * has popup
     *
     * @return bool
     */
    public function hasPopup(): bool;

    /**
     * get popup
     *
     * @return string
     */
    public function getPopup(): string;

    /**
     * @return bool
     */
    public function hasThumbnail(): bool;

    /**
     * @return string
     */
    public function getThumbnail(): string;
}