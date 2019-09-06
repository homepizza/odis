<?php

namespace App\Service;

use App\Entity\Tasks;
use App\Entity\User;
use App\Repository\CommentsRepository AS Comments;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface AS Normalizer;
use App\Service\Tools\TaskChangesService;
use App\Service\Notifications\MailService;

/*
 * Уведомления об изменении задачи
 */
class NotificationService
{
    private $normalizer;
    private $taskChanges;
    private $mail;
    private $comments;
    private $eventMessages;
    private $domain;

    public function __construct(
        Normalizer $normalizer,
        ContainerInterface $container,
        TaskChangesService $taskChanges,
        MailService $mail,
        Comments $comments
    )
    {
        $this->normalizer = $normalizer;
        $this->taskChanges = $taskChanges;
        $this->mail = $mail;
        $this->comments = $comments;
        $this->eventMessages = $taskChanges->getTaskEventMessages();
        $this->domain = $container->getParameter('task.domain');
    }

    /**
     * Уведомление релевантным участникам об изменении задачи.
     *
     * @param Tasks $sourceTask
     * @param Tasks $updatedTask
     * @param bool $attachments
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function notificationMembersByTask(Tasks $sourceTask, Tasks $updatedTask, bool $attachments): void
    {
        $members = $this->getMembersByTask($sourceTask);
        $sourceTask = $this->normalizer->normalize($sourceTask);
        $updatedTask = $this->normalizer->normalize($updatedTask);
        $differenceFields = $this->taskChanges->checkDifference($sourceTask, $updatedTask);
        dump($sourceTask);
        dump($updatedTask);
        die();
    }

    /**
     * Уведомление участников о новом комментарии от пользователя
     *
     * @param User $user
     * @param Tasks $task
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function notificationAboutNewComment(User $user, Tasks $task): void
    {
        $members = $this->getMembersByTask($task, $user->getId());
        $commentAuthor = $user->getFullname();
        $taskID = $task->getId();

        foreach ($members as $category) {
            if ($category instanceof User) {
                $onEmailNotification = $category->getEmailNotify();
                $actionAuthor = $category->getId() === $user->getId();
                if ($onEmailNotification && !$actionAuthor) {
                    $this->mail->sendEmail(
                        $this->eventMessages['comment'],
                        $category->getEmail(),
                        'Пользователь ('.$commentAuthor.') оставил новый комментарий к задаче №'.$taskID,
                        $this->domain.'/'.$taskID
                    );
                }
            }
            if (is_array($category)) {
                foreach ($category as $member) {
                    if ($member['emailNotify']) {
                        $this->mail->sendEmail(
                            $this->eventMessages['comment'],
                            $member['email'],
                            'Пользователь ('.$commentAuthor.') оставил новый комментарий к задаче №'.$taskID,
                            $this->domain.'/'.$taskID
                        );
                    }
                }
            }
        }
    }

    /**
     * Получение всех участников задачи
     *
     * @param Tasks $task
     * @param null $commentAuthorID
     * @return array
     */
    private function getMembersByTask(Tasks $task, $commentAuthorID=null): array
    {
        $members = [];
        $members['author'] = $task->getAuthor();
        $members['asignee'] = $task->getAsignee();
        $members['other'] = [];
        $owners = empty($members['asignee'])
            ? [$members['author']->getId()]
            : [$members['author']->getId(), $members['asignee']->getId()];

        if (!is_null($commentAuthorID)) {
            $owners[] = $commentAuthorID;
        }

        $commentMembers = $this->comments->members($task->getId(), $owners);
        foreach ($commentMembers as $k => $member) {
            $members['other'][] = $member;
        }
        return $members;
    }
}