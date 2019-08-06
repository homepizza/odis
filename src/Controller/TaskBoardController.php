<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TasksRepository;
use App\Repository\WorkflowRepository;

class TaskBoardController extends AbstractController
{
    private $tasks;
    private $workflow;

    public function __construct(TasksRepository $tasks, WorkflowRepository $workflow)
    {
        $this->tasks = $tasks;
        $this->workflow = $workflow;
    }

    /**
     * @Route("/", name="tasks_board")
     */
    public function index()
    {
        return $this->redirectToRoute('tasks');
    }

    /**
     * Список задач (общий)
     *
     * @Route("/tasks", name="tasks")
     */
    public function list()
    {
        return $this->render('tasks/demo.html.twig');
    }
}