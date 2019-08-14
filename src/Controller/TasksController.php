<?php

namespace App\Controller;

use App\Entity\Attachments;
use App\Entity\HistoryStatuses;
use App\Entity\Tasks;
use App\Repository\StatusesRepository AS Statuses;
use App\Repository\PrioritiesRepository AS Priorities;
use App\Repository\TasksRepository;
use App\Repository\TypesRepository AS Types;
use App\Repository\DomainAreasRepository AS Areas;
use Doctrine\ORM\EntityManagerInterface AS EM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
    private $em;
    private $statuses;
    private $priorities;
    private $types;
    private $areas;

    public function __construct(EM $em, Statuses $statuses, Priorities $priorities, Types $types, Areas $areas)
    {
        $this->em = $em;
        $this->statuses = $statuses;
        $this->priorities = $priorities;
        $this->types = $types;
        $this->areas = $areas;
    }

    /**
     * Корень
     *
     * @Route("/", name="tasks_index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->redirectToRoute('tasks');
    }

    /**
     * Список задач
     *
     * @Route("/tasks", name="tasks", methods={"GET"})
     * @param TasksRepository $tasks
     * @return Response
     */
    public function list(TasksRepository $tasks): Response
    {
        $list = $tasks->notEqualStatuses(['Завершено', 'Отменено']);
        return $this->render('tasks/content/tasks.html.twig', [
            'tasks' => $list
        ]);
    }

    /**
     * Создание новой задачи
     *
     * @Route("/tasks/new", name="tasks_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function newTask(Request $request): Response
    {
        $method = $request->getMethod();
        if ($method === 'POST') {
            $user = $this->getUser();
            $taskData = json_decode($request->getContent(), true);
            $status = $this->statuses->findOneBy(['name' => 'Новая']);
            $priority = $this->priorities->find($taskData['priority']['id']);
            $type = $this->types->find($taskData['type']['id']);
            $area = $this->areas->find($taskData['area']['id']);
            $task = new Tasks();
            $task->setAuthor($user);
            $task->setStatus($status);
            $task->setPriority($priority);
            $task->setType($type);
            $task->setArea($area);
            $task->setCreatedAt(new \DateTime());
            $task->setTitle($taskData['title']);
            $task->setBody($taskData['description']);
            $this->em->persist($task);
            $this->em->flush();

            $history = new HistoryStatuses();
            $history->setTask($task);
            $history->setStatus($status);
            $history->setDateStatus(new \DateTime());
            $this->em->persist($history);
            $this->em->flush();

            if (!empty($taskData['attachments'])) {
                foreach ($taskData['attachments'] as $link) {
                    $attachment = new Attachments();
                    $filename = explode('/', $link);
                    $filename = $filename[count($filename)-1];
                    $attachment->setFilename($filename);
                    $attachment->setLink($link);
                    $attachment->setTask($task);
                    $this->em->persist($attachment);
                }
                $this->em->flush();
            }

            return $this->json([], 200);
        }
        return $this->render('tasks/content/task_new.html.twig');
    }

    /**
     * Просмотр и редактирование задачи
     *
     * @Route("/tasks/{id}", name="tasks_view", methods={"GET"})
     * @param int $id
     * @param TasksRepository $tasks
     * @return Response
     */
    public function viewTask(int $id, TasksRepository $tasks): Response
    {
        $user = $this->getUser();
        $task = $tasks->find($id);
        $canEdit = in_array(
            $user->getId(),
            [
                $task->getAuthor()->getId(),
                (!empty($task->getAsignee()) ? $task->getAsignee()->getId() : 0)
            ]
        );
        return $this->render('tasks/content/task_view.html.twig', [
            'id' => $id,
            'edit' => $canEdit
        ]);
    }
}
