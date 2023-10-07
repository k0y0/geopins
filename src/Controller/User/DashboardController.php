<?php

namespace App\Controller\User;

use App\Service\Map\MapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route(path: '/', name: 'user_dashboard')]
    public function dashboard(
        Request $request,
        MapService $mapService,
    ): Response {
        $mapElements = $mapService->getMapElements($this->getUser());

        $centerZone = $mapService->getCenterZone($mapElements);
        $zoomLevel = $mapService->getZoomLevel($mapElements, $centerZone);

        return $this->render('user/dashboard.html.twig', [
            'mapElements' => $mapElements,
            'centerZone' => $centerZone,
            'zoomLevel' => $zoomLevel,
        ]);
    }
}