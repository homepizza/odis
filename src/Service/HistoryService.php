<?php


namespace App\Service;

use App\Entity\HistoryStatuses;
use App\Entity\Tasks;
use App\Repository\HistoryStatusesRepository;

/*
 * Сервис для работы с историей задачи
 */
class HistoryService
{
    /* @var HistoryStatusesRepository */
    private $statuses;

    public function __construct(HistoryStatusesRepository $statuses)
    {
        $this->statuses = $statuses;
    }

    /**
     * Информация о текущем статусе задачи
     *
     * @param Tasks $task
     * @return HistoryStatuses|bool
     */
    public function statusInfo(Tasks $task)
    {
        $stat = $task->getStatus();
        $statuses = $this->statuses->findBy(['task' => $task]);

        foreach ($statuses as $status) {
            if ($status->getStatus()->getId() === $stat->getId()) {
                $statusInfo = $status;
                break;
            }
        }
        return $statusInfo ?? false;
    }
}