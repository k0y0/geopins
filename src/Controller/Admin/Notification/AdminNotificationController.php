<?php

namespace App\Controller\Admin\Notification;

use App\Form\Notification\NotificationFilterType;
use App\Repository\Notification\NotificationLogRepository;
use App\Service\Notification\NotificationManager;
use App\Service\Notification\Provider\Result\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminNotificationController extends AbstractController
{
    const ITEMS_PER_PAGE = 20;

    /**
     * log list
     *
     * @Route("/admin/notification/list", name="admin_notification_list")
     *
     * @param Request $request
     * @param NotificationLogRepository $notificationLogRepository
     *
     * @return Response
     */
    public function list(
        Request $request,
        NotificationLogRepository $notificationLogRepository,
    ): Response
    {
        $data = [
            'id' => null,
            'dateFrom' => null,
            'dateTo' => null,
            'status' => null,
            'page' => null,
        ];
        $resetPagination = false;
        $options = [
            'statuses' => Result::getStatusesKeyValue()
        ];

        $form = $this->createForm(NotificationFilterType::class, $data , $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($form->get('submit')->isClicked()) {
                $resetPagination = true;
            }
        }
        $page = (!$resetPagination && isset($data['page']) ? $data['page'] : 1);

        $count = $notificationLogRepository->findByMultiParameters(true, $data, []);
        $results = $notificationLogRepository->findByMultiParameters(false, $data, ['i.id' => 'DESC'] , (($page -1) * self::ITEMS_PER_PAGE), self::ITEMS_PER_PAGE);

        return $this->render('admin/notification/list.html.twig', [
            'items' => $results,
            'form' => $form->createView(),
            'itemsCount' => $count,
            'currentPage' => $page,
            'paginationCount' => (int) ceil($count / self::ITEMS_PER_PAGE),
            'statuses' => Result::getStatuses(),
        ]);
    }


    /**
     * test sender
     * @Route("/admin/notification/test", name="admin_notification_test")
     *
     * @param NotificationManager $notificationManager
     *
     * @return Response
     */
    public function test(
        NotificationManager $notificationManager
    ): Response {
        $result = $notificationManager->sendEmailToUser($this->getUser(), 'test', 'example mail content');
        die;
    }
}