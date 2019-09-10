<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\Notifications\MailService;
use App\Repository\TasksRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mime\NamedAddress;

class NotifyTestingSendCommand extends Command
{
    protected static $defaultName = 'notify:testing:send';
    protected $mail;
    protected $tasks;
    protected $domain;

    public function __construct(ContainerInterface $container, MailService $mail, TasksRepository $tasks)
    {
        parent::__construct();
        $this->domain = $container->getParameter('task.domain');
        $this->mail = $mail;
        $this->tasks = $tasks;
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

        foreach ($tasks as $task) {
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
