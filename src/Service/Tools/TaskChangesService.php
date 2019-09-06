<?php

namespace App\Service\Tools;

use Symfony\Component\DependencyInjection\ContainerInterface;


class TaskChangesService
{
    private $events;

    public function __construct(ContainerInterface $container)
    {
        $this->events = $container->getParameter('task.events');
    }

    /**
     * Список существующих событий (сообщения для них)
     *
     * @return mixed
     */
    public function getTaskEventMessages()
    {
        return $this->events;
    }

    public function checkDifference(array $one, array $two): array
    {
        return [];
    }

    private function checkStatus()
    {

    }

    private function checkPriority()
    {
        
    }

    private function checkDueDate()
    {
        
    }

    private function checkTitle()
    {
        
    }

    private function checkBody()
    {
        
    }
}