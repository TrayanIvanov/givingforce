<?php

namespace App\Controller;

use App\Entity\Application;
use App\Form\ApplicationType;
use App\Repository\ApplicationRepository;
use App\Repository\UserRepository;
use App\Service\ApplicationTransitioning;
use App\Service\UserPermissions;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function transit(
        Request $request,
        Application $application,
        UserPermissions $userPermissions,
        ApplicationTransitioning $applicationTransitioning,
        UserRepository $userRepo
    ): Response {
        $transition = $request->attributes->get('transition');
        $userId = $request->request->get('userId');

        try {
            $this->checkUser($userId, $userRepo, $userPermissions);
            $this->checkTransition($transition, $applicationTransitioning, $application);
            $newStage = $this->getNewStage($transition, $applicationTransitioning, $application);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage());
        }

        $application->setStage($newStage);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($application);
        $entityManager->flush();

        return new JsonResponse('Application transitioned.');
    }

    private function checkUser(string $userId, UserRepository $userRepo, UserPermissions $userPermissions): void
    {
        if (!$userId) {
            throw new Exception('User not provided.');
        }

        $user = $userRepo->find($userId);

        $isUserAllowed = $userPermissions->granted($user, UserPermissions::PERMISSION_MOVE_APPLICATION);
        if (!$isUserAllowed) {
            throw new Exception('User is not allowed to move applications.');
        }
    }

    private function checkTransition(string $transition, ApplicationTransitioning $applicationTransitioning, Application $application): void
    {
        $transitioningAllowed = false;

        if ($transition === ApplicationTransitioning::TRANSITION_FORWARD) {
            $transitioningAllowed = $applicationTransitioning->transitForward($application);
        }
        if ($transition === ApplicationTransitioning::TRANSITION_BACKWARD) {
            $transitioningAllowed = $applicationTransitioning->transitBackward($application);
        }

        if (!$transitioningAllowed) {
            throw new Exception('Application can not be transitioned.');
        }
    }

    private function getNewStage(string $transition, ApplicationTransitioning $applicationTransitioning, Application $application)
    {
        if ($transition === ApplicationTransitioning::TRANSITION_FORWARD) {
            $newStage = $applicationTransitioning->nextStage($application->getStage());
        }

        if ($transition === ApplicationTransitioning::TRANSITION_BACKWARD) {
            $newStage = $applicationTransitioning->previousStage($application->getStage());
        }

        return $newStage;
    }
}
