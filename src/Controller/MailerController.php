<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(string $emailTo, MailerInterface $mailer): Response
    {
        try {
            $email = (new Email())
                ->from('lebonangle@lebonangle.com')
                ->to($emailTo)
                ->subject('Annonce LeBonAngle - Votre annonce a été publiée!')
                ->html(
                    '<p>Votre annonce sur LeBonAngle a été publiée avec succès!</p>'
                );

            $mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            return new Response($exception->getMessage());
        }

        return new Response("L'e-mail a été envoyé avec succès");
    }
}
