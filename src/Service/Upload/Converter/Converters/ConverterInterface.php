<?php

namespace App\Service\Upload\Converter\Converters;

interface ConverterInterface
{
    /**
     * convert to jpg
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function convert(string $filePath);
}