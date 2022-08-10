<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Setting;
use App\Form\Admin\SettingType;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/setting')]
class SettingController extends AbstractController
{
    #[Route('/', name: 'app_admin_setting_index', methods: ['GET'])]
    public function index(SettingRepository $settingRepository): Response
    {
        return $this->render('admin/setting/index.html.twig', [
            'settings' => $settingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_setting_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SettingRepository $settingRepository): Response
    {
        $setting = new Setting();
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settingRepository->add($setting, true);

            return $this->redirectToRoute('app_admin_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/setting/new.html.twig', [
            'setting' => $setting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_setting_show', methods: ['GET'])]
    public function show(Setting $setting): Response
    {
        return $this->render('admin/setting/show.html.twig', [
            'setting' => $setting,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_setting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Setting $setting, SettingRepository $settingRepository): Response
    {
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settingRepository->add($setting, true);

            return $this->redirectToRoute('app_admin_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/setting/edit.html.twig', [
            'setting' => $setting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_setting_delete', methods: ['POST'])]
    public function delete(Request $request, Setting $setting, SettingRepository $settingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$setting->getId(), $request->request->get('_token'))) {
            $settingRepository->remove($setting, true);
        }

        return $this->redirectToRoute('app_admin_setting_index', [], Response::HTTP_SEE_OTHER);
    }
}
