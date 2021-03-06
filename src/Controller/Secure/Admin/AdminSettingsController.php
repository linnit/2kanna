<?php

namespace App\Controller\Secure\Admin;

use App\Form\SettingType;
use App\Repository\SettingRepository;
use App\Util\SettingUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminSettingsController extends AbstractController
{
    /**
     * @Route("/admin/settings", name="admin_settings")
     */
    public function index(Request $request, SettingRepository $repository, SettingUtil $util)
    {
        $settings = $repository->findBy([], ['placement' => 'ASC']);
        $forms = [];

        foreach ($settings as $setting) {
            /**
             * Because we will have multiple forms of the same entity type,
             * We have to create our form with different names.
             */
            $form = $this->get('form.factory')
                ->createNamedBuilder('setting_'.$setting->getName(), SettingType::class, $setting)
                ->getForm()
            ;

            $form->handleRequest($request);

            $AJAXRequest = $request->get('AJAX');

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($setting);
                $entityManager->flush();

                // Clear cache
                $util->clearSetting($setting->getName());

                $this->addFlash(
                    'success',
                    $setting->getLabel().' is updated'
                );
            }

            $forms[] = $form->createView();
        }

        if ($AJAXRequest) {
            return $this->render('secure/admin/settings/ajax_return.html.twig');
        }

        return $this->render('secure/admin/settings/index.html.twig', [
            'forms' => $forms,
        ]);
    }
}
