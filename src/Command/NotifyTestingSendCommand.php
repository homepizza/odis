<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\Notifications\MailService;
use App\Repository\TasksRepository AS Tasks;
use Symfony\Component\DependencyInjection\ContainerInterface AS Container;
use App\Service\NotificationService;


class NotifyTestingSendCommand extends Command
{
    protected static $defaultName = 'notify:testing:send';
    protected $mail;
    protected $tasks;
    protected $domain;
    protected $notify;

    public function __construct(Container $container, MailService $mail, Tasks $tasks, NotificationService $notify)
    {
        parent::__construct();
        $this->domain = $container->getParameter('task.domain');
        $this->mail = $mail;
        $this->tasks = $tasks;
        $this->notify = $notify;
    }

    protected function configure()
    {
        $this->setDescription('Отправка уведомления авторам, чьи задачи на тестировании');
    }

    /**
     * Выполнение таски
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $tasks = $this->tasks->equalStatuses(['Тестирование']);

        // TODO: Сравнение срока (существующего или дефолтного)
        // TODO: Добавить дату в текущие уведомления
        // TODO: Уведомление участникам при закрытии (метод уведомления участников)
        
        foreach ($tasks as $task) {
            $members = $this->notify->getMembersByTask($task);
            dump($members);
            die();
            $author = $task->getAuthor();
            $emailNotify = $author->getEmailNotify();
            if ($emailNotify) {
                // Отправляем уведомление на почту.
                $this->mail->sendEmail(
                    'Задача ожидает вашего тестирования',
                    $author->getEmail(),
                    'У вас на тестировании находится задача №'.$task->getId(),
                    $this->domain.'/'.$task->getId()
                );
            }
        }
    }
}
