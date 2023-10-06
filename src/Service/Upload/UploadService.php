<?php

namespace App\Service\Upload;

use App\Entity\Upload\File;
use App\Entity\User\User;
use App\Message\ReadDataFromFile;
use App\Repository\Upload\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadService
{
    private FileRepository $fileRepository;
    private string $userUploadsDir;
    private MessageBusInterface $messageBus;

    public function __construct(
        FileRepository $fileRepository,
        MessageBusInterface $messageBus,
        string $userUploadsDir,
    ) {
        $this->fileRepository = $fileRepository;
        $this->messageBus = $messageBus;
        $this->userUploadsDir = $userUploadsDir;

        if (!is_dir($this->userUploadsDir)) {
            mkdir($this->userUploadsDir);
        }
    }

    /**
     * uploads a file
     *
     * @param UploadedFile $upload
     * @param User $user
     *
     * @return void
     */
    public function upload(UploadedFile $upload, User $user): void
    {
        $extension = $upload->guessExtension();
        $name = $upload->getClientOriginalName();

        $newFileName = $this->generateFileName($upload, $user);

        $file = new File();
        $file->setUser($user);
        $file->setExtension($extension);
        $file->setFileName($name);
        $file->setFileSize($upload->getSize());
        $file->setCreatedAt(new \DateTimeImmutable());
        $file->setFileSrc($newFileName);

        $upload->move($this->userUploadsDir, $newFileName);

        $message = (new ReadDataFromFile())
            ->setFile($file);
        $this->messageBus->dispatch($message);

        $this->fileRepository->save($file, true);
    }

    /**
     * generate unique file name
     *
     * @param UploadedFile $upload
     * @param User $user
     *
     * @return string
     */
    private function generateFileName(UploadedFile $upload, User $user): string
    {
        $random = uniqid();
        $filename = $upload->getFilename();
        $userId = $user->getId();

        $uniqueName = md5($random . $filename . $userId);
        while (file_exists($this->userUploadsDir . $uniqueName)) {
            $random = uniqid();
            $uniqueName = md5($random . $filename . $userId);
        }

        return $uniqueName;
    }

    /**
     * remove file
     *
     * @param File $file
     *
     * @return void
     */
    public function removeFile(File $file): void
    {
        if (file_exists($this->userUploadsDir . $file->getFileSrc())) {
            unlink($this->userUploadsDir . $file->getFileSrc());
        }

        $this->fileRepository->remove($file, true);
    }
}
