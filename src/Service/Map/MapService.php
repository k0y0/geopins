<?php

namespace App\Service\Map;

use App\Entity\User\User;
use App\Service\Map\Elements\Element\MapElementInterface;
use App\Service\Map\Elements\Factory\MapElementFactory;
use App\Utils\Map\PointDistance;

class MapService
{
    const DEFAULT_CENTER_ZONE = '50.02320694, 19.11199954';
    const DEFAULT_ZOOM_LEVEL = 6;

    private MapElementFactory $mapElementFactory;

    public function __construct(
        MapElementFactory $mapElementFactory
    ) {
        $this->mapElementFactory = $mapElementFactory;
    }

    /**
     * get map elements
     *
     * @param User $user
     *
     * @return MapElementInterface[]
     */
    public function getMapElements(User $user): array
    {
        $files = $user->getFiles();

        $mapElements = [];
        foreach ($files as $file) {
            try {
                $mapElements[] = $this->mapElementFactory->createFromFile($file);
            } catch (\Exception $e) {}
        }

        return $mapElements;
    }

    /**
     * get center zone of map
     *
     * @param MapElementInterface[] $mapElements
     *
     * @return string
     */
    public function getCenterZone(array $mapElements): string
    {
        if (empty($mapElements)) {
            return $this::DEFAULT_CENTER_ZONE;
        }
        $elementsCount = count($mapElements);

        $latSum = 0;
        $lngSum = 0;
        foreach ($mapElements as $mapElement) {
            $coordinates = $mapElement->getCoordinates();
            $latLng = explode(',', $coordinates);
            $lat = (float)$latLng[0];
            $lng = (float)$latLng[1];

            $latSum += $lat;
            $lngSum += $lng;

        }
        $centerLat = ($latSum) / $elementsCount;
        $centerLng = ($lngSum) / $elementsCount;

        return $centerLat . ', ' .  $centerLng;
    }

    /**
     * get zoom level
     *
     * @param MapElementInterface[] $mapElements
     * @param string $centerZone
     *
     * @return int
     * @throws \Exception
     */
    public function getZoomLevel(array $mapElements, string $centerZone): int
    {
        if (empty($mapElements)) {
            return self::DEFAULT_ZOOM_LEVEL;
        }

        $pointDistance = new PointDistance();
        $highestDistance = null;

        $centerLatLng = explode(',', $centerZone);
        $centerLat = (float)$centerLatLng[0];
        $centerLng = (float)$centerLatLng[1];

        $centerZoneArray = ['lat' => $centerLat, 'lng' => $centerLng];

        foreach ($mapElements as $mapElement) {
            $coordinates = $mapElement->getCoordinates();
            $latLng = explode(',', $coordinates);
            $lat = (float)$latLng[0];
            $lng = (float)$latLng[1];

            $pointArray = [
                'lat' => $lat,
                'lng' => $lng,
            ];
            $distanceToCenter = $pointDistance->calculateDistanceBetweenPoints($centerZoneArray, $pointArray);
            if ($highestDistance === null || $highestDistance < $distanceToCenter) {
                $highestDistance = $distanceToCenter;
            }
        }
        switch ($highestDistance) {
            case $highestDistance >= 16:
                return 2;
            case $highestDistance >= 8:
                return 3;
            case $highestDistance >= 4:
                return 4;
            case $highestDistance >= 3:
                return 5;
            case $highestDistance >= 2:
                return 6;
            case $highestDistance >= 1.3:
                return 7;
            case $highestDistance >= 0.7:
                return 8;
            case $highestDistance >= 0.4:
                return 9;
            default:
                return self::DEFAULT_ZOOM_LEVEL;
        }
    }
}