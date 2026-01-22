<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MailController extends AbstractController
{
    #[Route('/send-mail', name: 'send_mail')]
    public function sendMail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('admin@localhost')
            ->to('test@gecat.ga')
            ->subject('Hello Email!')
            ->text('Sending emails is fun again!');

        $mailer->send($email);

        return new Response('Email sent!');
    }

    #[Route('/send-mail-html', name: 'send_mail_html')]
    public function sendMailHtml(MailerInterface $mailer): Response
    {
        // Exemple de données fictives
        $courrier = [
            'numero' => 'C-2026-001',
            'objet' => 'Demande de rendez-vous',
            'dateSignature' => new \DateTimeImmutable(),
            'type' => 'Entrant',
        ];

        $username = "Jean Dupont";

        // Création du mail avec le Template Twig
        $email = (new TemplatedEmail())
            ->from('admin@localhost')
            ->to('agent@tresor.gouv.ga')
            ->subject('Nouveau courrier GECAT')
            ->htmlTemplate('email/new_courrier.html.twig')
            ->context([
                'courrier' => $courrier,
                'userName' => $username
            ]);

        $mailer->send($email);

        return new Response('HTML Email sent successfully!');
    }
}
