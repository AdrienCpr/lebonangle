<?php

namespace App\Controller;

use App\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AdvertNotificationController extends AbstractController
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendAdvertNotificationEmail(string $email,Advert $advert): void
    {
        $email = (new Email())
            ->from('lebonangle@gmail.com')
            ->to($email)
            ->subject('New Advertisement Notification')
            ->html(
                $this->renderEmailTemplate('advert_notification/index.html.twig', ['advert' => $advert])
            );

        $this->mailer->send($email);
    }

    private function renderEmailTemplate(string $template, array $data): string
    {
        return $this->twig->render($template, $data);
    }
}