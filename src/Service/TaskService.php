<?php

namespace App\Service;

use App\Entity\Attachments;
use App\Entity\HistoryStatuses;
use App\Entity\MailsQueue;
use App\Entity\Statuses;
use App\Entity\Tasks;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface AS EM;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface AS Normalizer;
use App\Repository\StatusesRepository;

/*
 * Работа с задачей
 */
class TaskService
{
    private $em;
    private $normalizer;
    private $statuses;

    public function __construct(EM $em, Normalizer $normalizer, StatusesRepository $statuses)
    {
        $this->em = $em;
        $this->normalizer = $normalizer;
        $this->statuses = $statuses;
    }

    /**
     * Закрытие задачи
     *
     * @param Tasks $task
     * @throws \Exception
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function closeTask(Tasks $task)
    {
        $source = clone $task;
        $closeStatus = $this->statuses->findOneBy(['name' => 'Завершено']);
        $task->setStatus($closeStatus);
        $this->updateHistory($task, $closeStatus, $task->getAsignee());
        $this->createNotificationMessage($task->getAuthor(), $source, $task, false);
    }

    /**
     * Прикрепления к задаче
     *
     * @param array $attachments
     * @param Tasks $task
     */
    public function attach(array $attachments, Tasks $task)
    {
        foreach ($attachments as $link) {
            $filename = explode('/', $link);
            $filename = $filename[count($filename)-1];
            $attachment = new Attachments();
            $attachment->setTask($task);
            $attachment->setLink($link);
            $attachment->setFilename($filename);
            $this->em->persist($attachment);
        }
        $this->em->flush();
    }

    /**
     * Обновление истории задачи
     *
     * @param Tasks $task
     * @param Statuses $status
     * @param $user
     * @throws \Exception
     */
    public function updateHistory(Tasks $task, Statuses $status, $user)
    {
        $history = new HistoryStatuses();
        $history->setTask($task);
        $history->setStatus($status);
        $history->setDateStatus(new \DateTime());
        $history->setAsignee($user);
        $this->em->persist($history);
        $this->em->flush();
    }

    /**
     * Постановка уведомления в очередь
     *
     * @param object|null $user
     * @param Tasks $source
     * @param Tasks $updated
     * @param bool $hasAttach
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function createNotificationMessage($user, Tasks $source, Tasks $updated, bool $hasAttach)
    {
        $mq = new MailsQueue();
        $mq->setEvent('task.updated');
        $data = json_encode($this->normalizer->normalize([$user, $source, $updated, $hasAttach]));
        $mq->setData($data);
        $this->em->persist($mq);
        $this->em->flush();
    }
}