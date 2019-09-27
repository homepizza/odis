<?php


namespace App\Service;


use App\Entity\Tasks;
use App\Repository\HistoryStatusesRepository;

class TaskService
{
    /* @var HistoryStatusesRepository */
    private $historyStatuses;

    public function __construct(HistoryStatusesRepository $historyStatuses)
    {
        $this->historyStatuses = $historyStatuses;
    }

    /**
     * История статусов задачи
     *
     * @param Tasks $task
     * @return array
     */
    public function history(Tasks $task): array
    {
        return $this->historyStatuses->findBy(['task' => $task->getId()]);
    }

    /**
     * Проверка на наличие заполненного поля "Время выполнения"
     *
     * @param $value
     * @return bool
     */
    public function checkValue($value): bool
    {
        $hasValue = !is_null($value);
        if ($hasValue) {
            $value = json_decode($value, true);
            $hasValue = !empty($value['D']) || !empty($value['H']) || !empty($value['m']);
        }
        return $hasValue;
    }
}