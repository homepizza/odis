<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
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
     * @Route("/tasks/new", name="tasks_new", methods={"GET"})
     * @return Response
     */
    public function newTask(): Response
    {
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
