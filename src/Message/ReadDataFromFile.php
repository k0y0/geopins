<?php

namespace App\Message;

use App\Entity\Upload\File;

class ReadDataFromFile
{
    private File $file;

    /**
     * @param File $file
     * @return ReadDataFromFile
     */
    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }
}