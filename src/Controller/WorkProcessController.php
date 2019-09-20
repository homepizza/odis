<?php

namespace App\Controller;

use App\Repository\TasksRepository;
use App\Service\Formatters\GanttService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WorkProcessController extends AbstractController
{
    /**
     * Ход работы (Диаграмма Ганта)
     *
     * @Route("/work-process", name="work_process")
     */
    public function index()
    {
        return $this->render('tasks/work_process/index.html.twig');
    }

    /**
     * Получение списка отсортированных задач для графика "Ход выполнения" (диаграмма Ганта)
     *
     * @Route("/work-process/tasks", name="work_process_chart", methods={"GET"})
     * @param TasksRepository $task
     * @param GanttService $gantt
     * @return JsonResponse
     */
    public function chartTasks(TasksRepository $task, GanttService $gantt): JsonResponse
    {
        $tasks = $task->notEqualStatuses(['Завершено', 'Отменено']);
        if (!empty($tasks)) {
            $tasks = $gantt->convertingTasks($tasks);
        }
        return $this->json($tasks ?? [], 200);
    }
}
