<?php


namespace App\Service\Notifications;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mime\NamedAddress;

class MailService
{
    /* @var MailerInterface */
    private $mailer;
    private $sender;

    public function __construct(MailerInterface $mailer, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->sender = $container->getParameter('sender');
    }

    /**
     * Отправка письма на почту
     *
     * @param string $subject
     * @param string $to
     * @param string $textMessage
     * @param string $taskLink
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $subject, string $to, string $textMessage, string $taskLink)
    {
        $email = (new TemplatedEmail())
            ->from(new NamedAddress($this->sender, 'Менеджер задач'))
            ->to($to)
            ->subject($subject)
            ->htmlTemplate('/emails/notification.html.twig')
            ->context([
                'message' => $textMessage,
                'link' => $taskLink,
                'eventDate' => new \DateTime()
            ])
        ;
        $this->mailer->send($email);
    }
}