<?php

namespace App\Command;

use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\Notifications\MailService;
use App\Repository\TasksRepository AS Tasks;
use Symfony\Component\DependencyInjection\ContainerInterface AS Container;
use App\Service\HistoryService;

class NotifyTestingSendCommand extends Command
{
    protected static $defaultName = 'notify:testing:send';
    protected $mail;
    protected $tasks;
    protected $domain;
    protected $testingDays;
    protected $history;
    protected $ts;

    /* @var EntityManagerInterface */
    protected $em;

    public function __construct(
        Container $container,
        MailService $mail,
        Tasks $tasks,
        TaskService $ts,
        HistoryService $history
    )
    {
        parent::__construct();
        $this->domain = $container->getParameter('task.domain');
        $this->testingDays = (int)$container->getParameter('testing_close_days');
        $this->em = $container->get('doctrine.orm.default_entity_manager');
        $this->ts = $ts;
        $this->mail = $mail;
        $this->tasks = $tasks;
        $this->history = $history;
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
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $tasks = $this->tasks->equalStatuses(['Тестирование']);
        $today = time();

        foreach ($tasks as $task) {
            $status = $this->history->statusInfo($task);
            $dateStatus = strtotime($status->getDateStatus()->format('Y-m-d H:i:s'));
            $testingDays = $task->getTestingDays() ?? $this->testingDays;
            $testingDays *= 86400;
            $dueTesting = $dateStatus + $testingDays;
            $todoCloseTask = $today > $dueTesting;

            $author = $task->getAuthor();
            $emailNotify = $author->getEmailNotify();

            if ($todoCloseTask) {
                // Закрытие задачи с уведомлением участников (task.updated) в очередь сообщение
                $this->ts->closeTask($task);
            }
            else {
                // Уведомление для автора о задаче на тестировании (Срок и Линк)
                if ($emailNotify) {
                    $closeDate = date('d.m.Y H:i:s', $dueTesting);
                    // Отправляем уведомление на почту.
                    $this->mail->sendEmail(
                        'Задача ожидает вашего тестирования',
                        $author->getEmail(),
                        'У вас на тестировании находится задача №'.$task->getId().', закроется: '.$closeDate,
                        $this->domain.'/'.$task->getId()
                    );
                }
            }
        }
    }
}
