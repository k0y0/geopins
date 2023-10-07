<?php

namespace App\Service\Map\Elements\Factory;

use App\Entity\Upload\File;
use App\Service\Map\Elements\Element\MapElementInterface;
use App\Service\Map\Elements\Element\Pin;

class MapElementFactory
{
    /**
     * create map element
     *
     * @param File $file
     *
     * @return MapElementInterface
     * @throws \Exception
     */
    public function createFromFile(File $file): MapElementInterface
    {
        return match ($file->getExtension()) {
            'jpg', 'png' => Pin::createFromFile($file),
            default => throw new \Exception('unsupported file extension'),
        };
    }
}