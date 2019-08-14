<?php

namespace App\Controller;

use App\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentsRepository;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentsController extends AbstractController
{
    private $comments;
    private $tasks;
    private $em;

    public function __construct(CommentsRepository $comments, TasksRepository $tasks, EntityManagerInterface $em)
    {
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
     * @return JsonResponse
     * @throws \Exception
     */
    public function saveComment(Request $request): JsonResponse
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

        return $this->json($comment, 200);
    }
}
