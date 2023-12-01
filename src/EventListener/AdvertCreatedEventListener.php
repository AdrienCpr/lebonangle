<?php

namespace App\EventListener;

use App\Controller\AdvertNotificationController;
use App\Entity\Advert;
use App\Repository\AdminUserRepository;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class AdvertCreatedEventListener
{
    private AdvertNotificationController $mailerController;
    private AdminUserRepository $adminUserRepository;

    public function __construct(
        AdvertNotificationController $mailerController,
        AdminUserRepository $adminUserRepository
    ) {
        $this->mailerController = $mailerController;
        $this->adminUserRepository = $adminUserRepository;
    }

    public function postPersist(Advert $advert, LifecycleEventArgs $event): void
    {
        $adminUsers = $this->adminUserRepository->findAll();

        foreach ($adminUsers as $adminUser) {
            $this->mailerController->sendAdvertNotificationEmail($adminUser->getEmail(), $advert);
        }
    }
}