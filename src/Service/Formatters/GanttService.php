<?php

namespace App\Service\Formatters;

use App\Service\TaskService;

/*
 * Обработка текущих задач для диаграммы Ганта.
 */
class GanttService
{
    private $tasks;
    private $results;
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function convertingTasks(array $tasks)
    {
        foreach ($tasks as $task) {
            $history = $this->taskService->history($task);
            $task->setHistory($history);
        }
        $this->tasks = $this->groupedByTimeData($tasks);
        $sp = $this->detectFirstStartPoint();
        $end = $this->tasksByDue($sp);
        $this->tasksByDuration($end);
        $this->setPercentOfValue();
        dump($sp);
        dump($this->tasks);
        die();
    }

    /**
     * Группировка задач на 2 категории: "Есть срок" и "Есть только время".
     *
     * @param array $tasks
     * @return array
     */
    private function groupedByTimeData(array $tasks): array
    {
        $tasksByTimeData = [[], []];
        foreach ($tasks as $task) {
            $value = $task->getValue();
            $hasDue = !empty($task->getDueDate());
            $hasValue = $this->taskService->checkValue($value);
            if ($hasDue) {
                $tasksByTimeData[0][] = $task;
            }
            elseif ($hasValue) {
                $tasksByTimeData[1][] = $task;
            }
        }
        unset($tasks);
        return $tasksByTimeData;
    }

    /**
     * Сортировка задач с указанным сроком выполнения и определение времени старта.
     *
     * @return false|int
     */
    private function detectFirstStartPoint()
    {
        $withDueObject = !empty($this->tasks[0]);
        if ($withDueObject) {
            $t = $this->tasks[0];
            usort($t, 'self::dateCompare');
            $detect = true;
            $i = 0;
            while ($detect) {
                if (isset($t[$i])) {
                    foreach ($t[$i]->getHistory() as $h) {
                        if ($h->getStatus()->getName() === 'В работе') {
                            $sp = strtotime($h->getDateStatus()->format('Y-m-d H:i:s'));
                            $detect = false;
                            break;
                        }
                    }
                    if (!isset($sp)) {
                        unset($t[$i]);
                    }
                }
                else { $detect = false; }
                $i++;
            }
            $this->tasks[0] = $t;
        }
        else {
            $sp = time();
        }
        return $sp ?? time();
    }

    /**
     * Сравнение 2-х дат. (обработчик)
     *
     * @param $task1
     * @param $task2
     * @return false|int
     */
    static function dateCompare($task1, $task2)
    {
        $datetime1 = $task1->getDueDate();
        $datetime2 = $task2->getDueDate();
        $t1 = strtotime($datetime1->format('Y-m-d H:i:s'));
        $t2 = strtotime($datetime2->format('Y-m-d H:i:s'));
        return $t1 - $t2;
    }

    /**
     * Выстраивание задач у которых указан "Срок выполнения"
     *
     * @param $start
     * @return int
     */
    private function tasksByDue($start): int
    {
        foreach ($this->tasks[0] as $task) {
            $dueTime = strtotime($task->getDueDate()->format('Y-m-d H:i:s'));
//            dump(date('Y-m-d H:i:s', $dueTime));
//            dump(date('Y-m-d H:i:s', $start));
//            die();
            $duration = $dueTime - $start;
            $t = [
                'id' => $task->getId(),
                'label' => $task->getTitle(),
                'user' => $task->getAuthor()->getFullname(),
                'start' => $start,
                'duration' => $duration,
                'percent' => 0,
                'type' => 'task',
            ];
            $this->results[] = $t;
            $start += $duration;
        }
        dump($this->results);
        die();
        return $start;
    }

    /**
     * Выстраивание задач у которых есть только "Время выполнения"
     *
     * @param $start
     * @return void
     */
    private function tasksByDuration($start): void
    {

    }

    /**
     * Проставление объема задачи в процентном выражении
     */
    private function setPercentOfValue(): void
    {

    }
}