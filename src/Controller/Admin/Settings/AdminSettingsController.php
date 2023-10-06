<?php

namespace App\Controller\Admin\Settings;

use App\Form\Settings\SettingsFormType;
use App\Service\Settings\SettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSettingsController extends AbstractController
{
    /**
     * @Route("/admin/settings/logs", name="admin_settings_logs")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function logsList(Request $request): Response
    {
        return $this->render('admin/settings/logs.html.twig', [
        ]);
    }

    /**
     * @Route("/admin/settings/settings", name="admin_settings_settings")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function settingsList(
        Request $request,
        SettingsService $settingsService
    ): Response {
        $settings = $settingsService->getSettings();

        $form = $this->createForm(SettingsFormType::class, [], ['settings' => $settings]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            foreach ($data as $name => $value) {
                try {
                    $settingsService->setSetting($name, $value);
                } catch (\Exception $e) {
                    $this->addFlash('error', $e->getMessage());
                }
            }
            $this->addFlash('success', 'Settings updated');
        }

        return $this->render('admin/settings/settings.html.twig', [
            'data' => $settings,
            'form' => $form->createView(),
        ]);
    }
}