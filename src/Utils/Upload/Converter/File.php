<?php

namespace App\Utils\Upload\Converter;

class File
{
    public string $filePath;

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * get file handle
     *
     * @return false|resource
     */
    public function getFile(): mixed
    {
        return fopen($this->filePath, 'r');
    }
}