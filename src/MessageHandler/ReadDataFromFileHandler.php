<?php

namespace App\MessageHandler;

use App\Message\ReadDataFromFile;
use App\Repository\Upload\FileRepository;
use App\Service\Upload\Converter\Converter;
use App\Service\Upload\Converter\Exception\UnsupportedConverterFileTypeException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ReadDataFromFileHandler implements MessageHandlerInterface
{

    private FileRepository $fileRepository;
    private Converter $converter;
    private string $userUploadsDir;

    public function __construct(
        FileRepository $fileRepository,
        Converter $converter,
        string $userUploadsDir,
    ) {
        $this->fileRepository = $fileRepository;
        $this->converter = $converter;
        $this->userUploadsDir = $userUploadsDir;
    }

    /**
     * read data from file handler
     *
     * @param ReadDataFromFile $readDataFromFile
     *
     * @return void
     */
    public function __invoke(ReadDataFromFile $readDataFromFile)
    {
        $file = $readDataFromFile->getFile();

        try {
            $this->converter->convert($file);
        } catch (UnsupportedConverterFileTypeException $e) {

        }

        $exifData = exif_read_data($this->userUploadsDir . $file->getFileSrc(), null, true, true);
        $fileAdditionalData = [];
        if (isset($exifData['GPS'])) {
            $latitude = $this->convertExifGpsData($exifData['GPS']['GPSLatitude']);
            $longitude = $this->convertExifGpsData($exifData['GPS']['GPSLongitude']);
            $fileAdditionalData['latitude'] = $latitude;
            $fileAdditionalData['longitude'] = $longitude;
        }
        if (isset($exifData['IFD0'])) {
            $date = $exifData['IFD0']['DateTime'] ?? null;
            if (!$date) {
                $fileAdditionalData['createdAt'] = new \DateTime($date);
            }
        }

        $file->setAdditionalData($fileAdditionalData);
        $this->fileRepository->save($file, true);
    }

    /**
     * convert exif gps longitude and latitude
     *
     * @param array $data
     *
     * @return float
     */
    private function convertExifGpsData(array $data): float
    {
        $a = explode('/', $data[0])[0];
        $b = explode('/', $data[1])[0];
        $c = explode('/', $data[2])[0];
        $cDivision = explode('/', $data[2])[1];

        $aConverted = intval($a);
        $bConverted = intval($b) / 60;
        $cConverted = intval($c) / intval($cDivision) / 3600;

        return $aConverted + $bConverted + $cConverted;
    }
}