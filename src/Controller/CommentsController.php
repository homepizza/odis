<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\MailsQueue;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentsRepository AS CM;
use App\Repository\TasksRepository AS Tasks;
use Doctrine\ORM\EntityManagerInterface AS EM;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface AS Normalizer;

class CommentsController extends AbstractController
{
    private $normalizer;
    private $comments;
    private $tasks;
    private $em;

    public function __construct(Normalizer $normalizer, CM $comments, Tasks $tasks, EM $em)
    {
        $this->normalizer = $normalizer;
        $this->comments = $comments;
        $this->tasks = $tasks;
        $this->em = $em;
    }

    /**
     * Комментарии к задачи с их пользователями
     *
     * @Route("/comments/task/{id}", name="comments", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function comments(int $id): JsonResponse
    {
        $comments = $this->comments->findBy(['task' => $id]);
        return $this->json($comments, 200);
    }

    /**
     * Сохранение комментария, опубликованного к задаче
     *
     * @Route("/comments/save", name="comments_save", methods={"POST"})
     * @param Request $request
     * @param NotificationService $notify
     * @return JsonResponse
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function saveComment(Request $request, NotificationService $notify): JsonResponse
    {
        $user = $this->getUser();
        $content = json_decode($request->getContent(), true);
        $task = $this->tasks->find($content['task']);
        $comment = new Comments();
        $comment->setUser($user);
        $comment->setTask($task);
        $comment->setCreatedAt(new \DateTime());
        $comment->setComment($content['comment']);
        $this->em->persist($comment);
        $this->em->flush();

        $mq = new MailsQueue();
        $mq->setEvent('comment.create');
        $data = json_encode($this->normalizer->normalize([$user, $task]));
        $mq->setData($data);
        $this->em->persist($mq);
        $this->em->flush();

        return $this->json($comment, 200);
    }
}
