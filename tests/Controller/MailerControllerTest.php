<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerControllerTest extends WebTestCase
{
    public function testSendEmail(): void
    {
        $client = static::createClient();

        $mailer = $this->createMock(MailerInterface::class);
        $client->getContainer()->set('Symfony\Component\Mailer\MailerInterface', $mailer);

        $userEmail = 'test@example.com';

        $mailerController = $client->getContainer()->get('App\Controller\MailerController');

        $email = (new Email())
            ->from('test@gmail.com')
            ->to($userEmail)
            ->subject('test subject')
            ->text('test text')
            ->html('<p>test</p>');

        $mailer->expects($this->once())
            ->method('send')
            ->with($this->equalTo($email));

        $response = $mailerController->sendEmail($mailer, $userEmail);
        $this->assertEquals('Email sent successfully!', $response->getContent());
    }
}
