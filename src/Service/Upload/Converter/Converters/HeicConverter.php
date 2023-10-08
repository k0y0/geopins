<?php

namespace App\Service\Upload\Converter\Converters;

use Maestroerror\HeicToJpg;

class HeicConverter implements ToJpgConverterInterface
{
    /**
     * convert to jpg
     *
     * @param string $filePath
     *
     * @return HeicToJpg|mixed
     */
    public function convert(string $filePath)
    {
        if (!HeicToJpg::isHeic($filePath)) {
            return false;
        }

        $jpg = HeicToJpg::convert($filePath);
        return $jpg->get();
    }
}