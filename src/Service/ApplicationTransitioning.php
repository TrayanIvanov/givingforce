<?php

namespace App\Service;

use App\Entity\Application;

class ApplicationTransitioning
{
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
}
