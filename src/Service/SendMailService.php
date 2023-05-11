<?php
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService 
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void // on envoie le mail mais on ne retourne pas de donnée
    
    {
        //on crée l'email

        $email = (new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate("emails/$template.html.twig")
        ->context($context);

        //on envoie l'email

        $this->mailer->send($email);

    }
}