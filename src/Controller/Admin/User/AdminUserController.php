<?php

namespace App\Controller\Admin\User;

use App\Entity\User\User;
use App\Form\User\UserFilterType;
use App\Repository\User\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    const ITEMS_PER_PAGE = 20;

    /**
     * users list
     *
     * @Route("/admin/users", name="admin_users_list")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function list(
        Request $request,
        UserRepository $userRepository,
    ): Response
    {
        $data = [
            'id' => null,
            'createdDateFrom' => null,
            'createdDateTo' => null,
            'verified' => null,
        ];
        $resetPagination = false;

        $form = $this->createForm(UserFilterType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($form->get('submit')->isClicked()) {
                $resetPagination = true;
            }
        }
        $page = (!$resetPagination && isset($data['page']) ? $data['page'] : 1);
        $count = $userRepository->findByMultiParameters(true, $data, []);
        $results = $userRepository->findByMultiParameters(false, $data, ['i.id' => 'DESC'] , (($page -1) * self::ITEMS_PER_PAGE), self::ITEMS_PER_PAGE);

        return $this->render('admin/users/list.html.twig', [
            'users' => $results,
            'form' => $form->createView(),
            'itemsCount' => $count,
            'currentPage' => $page,
            'paginationCount' => (int) ceil($count / self::ITEMS_PER_PAGE),
        ]);
    }

    /**
     * get info about user
     *
     * @Route("/admin/users/{user}}", name="admin_users_info")
     *
     * @param User $user
     *
     * @return Response
     */
    public function info(User $user): Response
    {
        return $this->render('admin/users/info.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * @Route("/admin/users/verify-resend/{user}", name="admin_users_resend_verify_email")
     */
    public function resendVerifyUserEmail(
        Request $request,
        User $user,
        EmailVerifier $emailVerifier,
        string $mailProviderDefaultEmail,
        string $mailProviderDefaultName
    ): Response {
        if (!$user->isVerified()) {
            // generate a signed url and email it to the user
            $emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address($mailProviderDefaultEmail, $mailProviderDefaultName))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            $this->addFlash('success', 'Verification email was sent successfully');
        } else {
            $this->addFlash('success', 'This user has already finished verification process.');
        }

        return $this->redirectToRoute('admin_users_list');
    }

}