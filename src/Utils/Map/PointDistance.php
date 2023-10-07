<?php

namespace App\Utils\Map;

class PointDistance
{

    /**
     * calculate distance between points
     *
     * @param array $pointA
     * @param array $pointB
     *
     * @return float
     * @throws \Exception
     */
    public function calculateDistanceBetweenPoints(array $pointA, array $pointB): float
    {
        $latitudePointA = $pointA['lat'] ?? null;
        $longitudePointA = $pointA['lng'] ?? null;

        $latitudePointB = $pointB['lat'] ?? null;
        $longitudePointB = $pointB['lng'] ?? null;

        if (
            $latitudePointA === null ||
            $longitudePointA === null ||
            $latitudePointB === null ||
            $longitudePointB === null
        ) {
            throw new \Exception('invalid point coordinates given');
        }

        $theta = $longitudePointA - $longitudePointB;
        $dist = sin(deg2rad($latitudePointA)) * sin(deg2rad($latitudePointB)) +  cos(deg2rad($latitudePointA)) * cos(deg2rad($latitudePointB)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);

        return $dist;
    }
}