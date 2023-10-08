<?php

namespace App\Service\Upload\Converter\Exception;

class UnsupportedConverterFileTypeException extends \Exception {
    protected $message = 'Unsupported converter file type.';
}