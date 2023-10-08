<?php

namespace App\Service\Upload\Converter;


use App\Entity\Upload\File;
use App\Repository\Upload\FileRepository;
use App\Service\Upload\Converter\Converters\ConverterInterface;
use App\Service\Upload\Converter\Exception\UnsupportedConverterFileTypeException;

class Converter
{
    const IMAGE_DEFAULT_EXTENSION = 'jpg';

    private FileRepository $fileRepository;
    private string $userUploadsDir;

    public function __construct(
        FileRepository $fileRepository,
        string $userUploadsDir
    ) {
        $this->fileRepository = $fileRepository;
        $this->userUploadsDir = $userUploadsDir;
    }

    /**
     * convert file to specified format
     *
     * @param File $file
     * @param string|null $toFormat (default: null)
     *
     * @return void
     * @throws UnsupportedConverterFileTypeException
     */
    public function convert(File $file, ?string $toFormat = null): void
    {
        if ($toFormat === null) {
            $toFormat = $this->guessToFormat($file);
        }

        if (!$toFormat) {
            throw new UnsupportedConverterFileTypeException('Cannot convert ' . $file->getExtension() . ' to ' . $toFormat . '. Unsupported file type.');
        }
        $converter = $this->getConverter($file, $toFormat);

        if (!$converter) {
            throw new UnsupportedConverterFileTypeException('Cannot convert ' . $file->getExtension() . ' to ' . $toFormat . '. Unsupported file type.');
        }
        $convertedFileBinary = $converter->convert($this->userUploadsDir . $file->getFileSrc());

        file_put_contents($this->userUploadsDir . $file->getFileSrc(), $convertedFileBinary);
        $file->setExtension($toFormat);
        $file->setFileSize(filesize($this->userUploadsDir . $file->getFileSrc()));
        $this->fileRepository->save($file, true);
    }

    /**
     * guess what format of file is
     *
     * @param File $file
     *
     * @return string|null
     * @throws \Exception
     */
    private function guessToFormat(File $file): ?string
    {
        $mimeType = mime_content_type($this->userUploadsDir . $file->getFileSrc());

        if ($mimeType === false) {
            return null;
        }

        $type = explode('/', $mimeType);
        return match ($type[0]) {
            'image' => self::IMAGE_DEFAULT_EXTENSION,
            default => throw new \Exception('Unexpected value'),
        };
    }

    /**
     * get converter
     *
     * @param File $file
     * @param string|null $toFormat
     *
     * @return ConverterInterface|null
     */
    private function getConverter(File $file, ?string $toFormat): ?ConverterInterface
    {
        $converterNamespace = 'App\Service\Upload\Converter\Converters\\';
        $converterClassName = $converterNamespace . ucfirst(strtolower($file->getExtension())) . 'Converter';
        $toFormatClassName = $converterNamespace . 'To' . ucfirst(strtolower($toFormat)) . 'ConverterInterface' ;

        if (!class_exists($converterClassName)) {
            return null;
        }
        $converterClass = new $converterClassName();
        if (!$converterClass instanceof $toFormatClassName) {
            return null;
        }

        return $converterClass;
    }
}