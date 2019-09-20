<?php

namespace App\Service\Formatters;

use App\Entity\Tasks;
use App\Service\TaskService;

/*
 * Обработка текущих задач для диаграммы Ганта.
 */
class GanttService
{
    private $tasks;
    private $results;
    private $durationSUM;
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->durationSUM = 0;
        $this->taskService = $taskService;
    }

    public function convertingTasks(array $tasks)
    {
        foreach ($tasks as $task) {
            $history = $this->taskService->history($task);
            $task->setHistory($history);
        }
        $this->tasks = $this->groupedByTimeData($tasks);
        if (!empty($this->tasks[0]) || !empty($this->tasks[1])) {
            $start = $this->detectFirstStartPoint();
            $end = $this->calculateTasks($start);
            $this->calculateTasks($end, true);
            $this->setPercentOfValue();
        }

        return $this->results;
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
     * Выстраивание задач у которых указан "Срок выполнения" или "Время выполнения"
     *
     * @param $start
     * @param bool $byTime
     * @return int
     */
    private function calculateTasks($start, $byTime = false): int
    {
        $n = $byTime ? 1 : 0;
        foreach ($this->tasks[$n] as $task) {
            if (!$byTime) {
                $dueTime = strtotime($task->getDueDate()->format('Y-m-d H:i:s'));
                $duration = $dueTime - $start;
            }
            else {
                $duration = $this->calculateDuration($task);
            }
            $this->durationSUM += $duration;
            $class = $task->getStatus()->getClass();
            $color =  ($class === 'u-color-warning') ? ("#D2D400")
                : (($class === 'u-color-success') ? "#1EBC61" : "#0287D0");
            $base = [
                'fill' => $color,
                'stroke' => $color
            ];
            $t = [
                'id' => $task->getId(),
                'label' => $task->getTitle(),
                'user' => $task->getAuthor()->getFullname(),
                'start' => $start,
                'duration' => $duration,
                'percent' => 0,
                'type' => 'task',
                'style' => ['base' => $base]
            ];
            $this->results[] = $t;
            $start += $duration;
        }
        return $start;
    }

    /**
     * Вычисление времени выполнения в милисекундах.
     *
     * @param Tasks $task
     * @return int
     */
    private function calculateDuration(Tasks $task): int
    {
        $value = json_decode($task->getValue(), true);
        if (is_numeric($value['D'])) {
            $h = $value['D'] * 24;
        }

        if (is_numeric($value['H'])) {
            $m = isset($h) ? $h * 60 : 0;
            $m += $value['H'] * 60;
        }
        else {
            $m = $h * 60;
        }

        if (is_numeric($value['m'])) {
            $s = isset($m) ? $m * 60 : 0;
            $s += $value['m'] * 60;
        }
        else {
            $s = $m * 60;
        }
        return $s * 1000;
    }

    /**
     * Проставление объема задачи в процентном выражении + приведение даты старта к милисекундам
     */
    private function setPercentOfValue(): void
    {
        foreach ($this->results as $k => $result) {
            $result['start'] *= 1000;
            $result['percent'] = ($result['duration'] * 100) / $this->durationSUM;
            $this->results[$k] = $result;
        }
    }
}