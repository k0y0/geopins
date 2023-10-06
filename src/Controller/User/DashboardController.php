<?php

namespace App\Controller\User;

use App\Entity\User\User;
use App\Service\Questionnaire\QuestionnaireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route(path: '/', name: 'user_dashboard')]
    public function dashboard(
        Request $request,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();


        if (!$this->isGranted('ROLE_ADMIN')) {
        }

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
        ]);
    }

}