<?php

namespace App\Controller;

use App\Entity\Attachments;
use App\Entity\HistoryStatuses;
use App\Entity\MailsQueue;
use App\Entity\Tasks;
use App\Repository\DomainAreasRepository;
use App\Repository\StatusesRepository AS Statuses;
use App\Repository\PrioritiesRepository AS Priorities;
use App\Repository\TasksRepository;
use App\Repository\TypesRepository AS Types;
use App\Repository\DomainAreasRepository AS Areas;
use App\Repository\UserRepository;
use App\Service\NotificationService;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface AS EM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * Список задач (страница)
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
     * Список задач (данные)
     *
     * @Route("/tasks/data", name="tasks_data", methods={"GET"})
     * @param TasksRepository $tasks
     * @return JsonResponse
     */
    public function listData(TasksRepository $tasks): JsonResponse
    {
        $list = $tasks->notEqualStatuses(['Завершено', 'Отменено']);
        $number = count($list);
        return $this->json(['tasks' => $list, 'number' => $number], 200);
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
        // TODO: Часть вынести в TaskService
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
     * Обновление задачи
     *
     * @Route("/tasks/{id}/update", name="tasks_update", methods={"PUT"})
     * @param int $id
     * @param TasksRepository $tasks
     * @param UserRepository $u
     * @param Priorities $priorities
     * @param Statuses $statuses
     * @param Types $types
     * @param Areas $areas
     * @param Request $request
     * @param TaskService $ts
     * @return JsonResponse
     * @throws \Exception
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function updateTask(
        int $id,
        TasksRepository $tasks,
        UserRepository $u,
        Priorities $priorities,
        Statuses $statuses,
        Types $types,
        DomainAreasRepository $areas,
        Request $request,
        TaskService $ts
    ): JsonResponse
    {
        // TODO: Часть вынести в TaskService
        $taskData = json_decode($request->getContent(), true);
        $task = $tasks->find($taskData['taskNumber']);
        $sourceTask = clone $task;
        $user = !empty($taskData['asignee']) ? $u->find($taskData['asignee']['id']) : null;
        $priority = $priorities->find($taskData['priority']['id']);
        $status = $statuses->find($taskData['status']['id']);
        $statusNew = $status->getId() !== $task->getStatus()->getId();
        $type = $types->find($taskData['type']['id']);
        $area = $areas->find($taskData['area']['id']);
        $task->setValue(json_encode($taskData['timeValue']));
        $task->setAsignee($user);
        $task->setPriority($priority);
        $task->setStatus($status);
        $task->setType($type);
        $task->setArea($area);
        $task->setDueDate(is_null($taskData['dueDate']) ? null : new \DateTime($taskData['dueDate']));
        $task->setTitle($taskData['title']);
        $task->setBody($taskData['description']);
        $task->setSolutionLink($taskData['solutionLink']);
        $this->em->flush();

        $hasAttach = !empty($taskData['attachments']);
        if ($hasAttach) $ts->attach($taskData['attachments'], $task);
        if ($statusNew) $ts->updateHistory($task, $status, $user);

        $user = $this->getUser();
        $ts->createNotificationMessage($user, $sourceTask, $task, $hasAttach);

        return $this->json($task, 200);
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
        $canEdit = in_array('ROLE_DEVELOPER', $user->getRoles()) && empty($task->getAsignee())
            ? true
            : $canEdit
        ;
        return $this->render('tasks/content/task_view.html.twig', [
            'id' => $id,
            'edit' => $canEdit
        ]);
    }
}
