<?php

namespace App\Controller\Upload;

use App\Entity\Upload\File;
use App\Form\Upload\UploadFormType;
use App\Service\Upload\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    const defaultRoute = 'files';

    #[Route(path: '/files', name: 'files')]
    public function dashboard(
        Request $request,
    ): Response {
        $data = [];

        $form = $this->createForm(UploadFormType::class, $data);
        $files = $this->getUser()->getFiles();

        return $this->render('upload/upload.html.twig', [
            'form' => $form->createView(),
            'files' => $files,
        ]);
    }

    #[Route(path: '/files/upload-file', name: 'process_upload')]
    public function process(
        Request $request,
        UploadService $uploadService
    ): Response {
        $file = $request->files->get('file');

        if (empty($file)) {
            return new Response('Niepoprawny plik', Response::HTTP_BAD_REQUEST);
        }

        try {
            $uploadService->upload($file, $this->getUser());
            return new Response('Plik dodany!', Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response('Wystąpił błąd podczas uploadu. Spróbuj ponownie. (exception: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route(path: '/files/remove/{file}', name: 'remove_file')]
    public function remove(
        Request $request,
        UploadService $uploadService,
        ?File $file = null,
    ): Response {
        $submitToken = $request->request->get('token');
        if (!$file || $file->getUser() !== $this->getUser() || !$this->isCsrfTokenValid('delete-file', $submitToken)) {
            $this->addFlash('error', 'Nie znaleziono pliku');
            return $this->redirectToRoute(self::defaultRoute);
        }

        try {
            $uploadService->removeFile($file);
            $this->addFlash('success', 'Usunięto plik');
            return $this->redirectToRoute(self::defaultRoute);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Wystąpił błąd podczas usuwania pliku');
            return $this->redirectToRoute(self::defaultRoute);
        }
    }

    #[Route(path: '/files/thumbnail/{fileName}', name: 'file_get_thumbnail')]
    public function getThumbnail(
        string $fileName,
        string $userUploadsDir,
    ): Response {
        $pathToFile = $userUploadsDir . $fileName;
        if (!file_exists($pathToFile)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        return new BinaryFileResponse($pathToFile);
    }
}