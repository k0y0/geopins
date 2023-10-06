<?php

namespace App\MessageHandler;

use App\Message\ReadDataFromFile;
use App\Repository\Upload\FileRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ReadDataFromFileHandler implements MessageHandlerInterface
{

    private FileRepository $fileRepository;
    private string $userUploadsDir;

    public function __construct(
        FileRepository $fileRepository,
        string $userUploadsDir,
    ) {
        $this->fileRepository = $fileRepository;
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

        $exifData = exif_read_data($this->userUploadsDir . $file->getFileSrc(), null, true, true);
        $fileAdditionalData = [];
        if (isset($exifData['GPS'])) {
            $latitude = $this->convertExifGpsData($exifData['GPS']['GPSLatitude']);
            $longitude = $this->convertExifGpsData($exifData['GPS']['GPSLongitude']);
            $fileAdditionalData['latitude'] = $latitude;
            $fileAdditionalData['longitude'] = $longitude;
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