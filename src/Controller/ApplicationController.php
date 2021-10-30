<?php

namespace App\Controller;

use App\Entity\Application;
use App\Form\ApplicationType;
use App\Repository\ApplicationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends AbstractController
{
    public function list(ApplicationRepository $applicationRepo): Response
    {
        $applications = $applicationRepo->findBy([], ['createdAt' => 'desc']);

        return $this->render('applications/list.html.twig', [
            'applications' => $applications,
        ]);
    }

    public function create(Request $request): Response
    {
        $application = new Application();

        $form = $this->createForm(ApplicationType::class, $application);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Application $application */
            $application = $form->getData();
            $application->setStage($application->getCharity()->getIsApproved() ? Application::STAGE_ALLOW_TO_PROCEED : Application::STAGE_ORGANISATION_APROVAL);
            $application->setCreatedAt(new DateTime('-1 hour'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($application);
            $entityManager->flush();

            return $this->redirectToRoute('applications_list');
        }

        return $this->renderForm('applications/create.html.twig', [
            'form' => $form,
        ]);
    }
}
