<?php

namespace App\Service;

use App\Entity\Application;

class ApplicationTransitioning
{
    public const TRANSITION_FORWARD = 'forward';
    public const TRANSITION_BACKWARD = 'backward';

    public function transitForward(Application $application): bool
    {
        return $application->getCreatedAt() < new \DateTime('-1 day')
            && $application->getCharity()->getCountry()->getCode() === 'GBR'
            && $application->getCharity()->getIsApproved() === true
            && $application->getStage() !== Application::STAGE_PAID;
    }

    public function transitBackward(Application $application): bool
    {
        return $application->getCharity()->getCountry()->getCode() === 'GBR';
    }

    public function nextStage(string $stage): string
    {
        if ($stage === Application::STAGE_ORGANISATION_APROVAL) {
            return Application::STAGE_ALLOW_TO_PROCEED;
        }
        if ($stage === Application::STAGE_ALLOW_TO_PROCEED) {
            return Application::STAGE_PAID;
        }

        return '';
    }

    public function previousStage(string $stage): string
    {
        if ($stage === Application::STAGE_PAID) {
            return Application::STAGE_ALLOW_TO_PROCEED;
        }
        if ($stage === Application::STAGE_ALLOW_TO_PROCEED) {
            return Application::STAGE_ORGANISATION_APROVAL;
        }

        return '';
    }
}
