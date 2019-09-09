<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\MailsQueueRepository AS Mails;
use Doctrine\ORM\EntityManagerInterface AS EM;
use App\Service\NotificationService;
use App\Repository\UserRepository AS User;
use App\Repository\TasksRepository AS TasksR;

class MailsProcessingCommand extends Command
{
    protected static $defaultName = 'mails:processing';
    protected $mails;
    private $em;
    private $notification;
    private $users;
    private $tasks;

    public function __construct(Mails $mails, EM $em, NotificationService $notification, User $users, TasksR $tasks)
    {
        parent::__construct();
        $this->mails = $mails;
        $this->em = $em;
        $this->notification = $notification;
        $this->users = $users;
        $this->tasks = $tasks;
    }

    protected function configure()
    {
        $this->setDescription('Обработка накопленных сообщений, их рассылка');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // Очищаем очередь от отправленных писем.
        $completedItems = $this->mails->findBy(['success' => 1]);
        foreach ($completedItems as $completedItem) {
            $this->em->remove($completedItem);
        }
        $this->em->flush();

        $todoItems = $this->mails->findBy(['success' => 0]);

        foreach ($todoItems as $todoItem) {
            $data = json_decode($todoItem->getData(), 1);
            $event = $todoItem->getEvent();
            $user = $this->users->find($data[0]['id']);
            $task = count($data) > 2
                ? $this->tasks->find($data[2]['id'])
                : $this->tasks->find($data[1]['id']);

            if ($event === 'task.updated') {
                $sourceData = $data[1];
                $attachments = $data[3];
                $sourceTask = clone $task;
                $sourceTask->setCreatedAt(new \DateTime($sourceData['createdAt']));
                $sourceTask->setDueDate(new \DateTime($sourceData['dueDate']));
                $sourceTask->setTitle($sourceData['title']);
                $sourceTask->setBody($sourceData['body']);
                $sourceTask->setSolutionLink($sourceData['solutionLink']);
                $sourceTask->setValue($sourceData['value']);
                $asignee = $this->users->find($sourceData['asignee']['id']);
                $priority = $this->em->getRepository('App:Priorities')->find($sourceData['priority']['id']);
                $status = $this->em->getRepository('App:Statuses')->find($sourceData['status']['id']);
                $type = $this->em->getRepository('App:Types')->find($sourceData['type']['id']);
                $area = $this->em->getRepository('App:DomainAreas')->find($sourceData['area']['id']);
                $sourceTask->setAsignee($asignee);
                $sourceTask->setPriority($priority);
                $sourceTask->setStatus($status);
                $sourceTask->setType($type);
                $sourceTask->setArea($area);
                $this->notification->notificationMembersByTask($user, $sourceTask, $task, $attachments);
                $todoItem->setSuccess(true);
            }
            else {
                $this->notification->notificationAboutNewComment($user, $task);
                $todoItem->setSuccess(true);
            }
        }
        $this->em->flush();
    }
}
