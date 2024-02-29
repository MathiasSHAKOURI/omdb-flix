<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MyMailerService
{
    private $mailer;
    private $adminMail;

    public function __construct(MailerInterface $mailer, string $adminMail){
        $this->mailer = $mailer;
        $this->adminMail = $adminMail;
    }

    public function alertToAdmin(string $subject, string $template, array $context){

         $email = (new TemplatedEmail())
         ->from($this->adminMail)
         ->to($this->adminMail)
         ->subject($subject)
         ->htmlTemplate("email/$template")
         ->context($context);
         $this->mailer->send($email);

    }
}