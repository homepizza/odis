<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Repository\StatusesRepository AS Statuses;
use App\Repository\PrioritiesRepository AS Priorities;
use App\Repository\TypesRepository AS Types;
use App\Repository\DomainAreasRepository AS Areas;
use Doctrine\ORM\EntityManagerInterface AS EM;
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
     * Список задач
     *
     * @Route("/tasks", name="tasks", methods={"GET"})
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('tasks/content/tasks.html.twig');
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
            unset($task);

            return $this->json([], 200);
        }
        return $this->render('tasks/content/task_new.html.twig');
    }

    /**
     * Просмотр и редактирование задачи
     *
     * @Route("/tasks/{id}", name="tasks_view", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function viewTask(int $id): Response
    {
        return $this->render('tasks/content/task_view.html.twig', [
            'id' => $id
        ]);
    }
}
