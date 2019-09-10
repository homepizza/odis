<?php

namespace App\Service\Tools;

use App\Entity\Tasks;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TaskChangesService
{
    private $events;
    private $normalizer;

    public function __construct(ContainerInterface $container, NormalizerInterface $normalizer)
    {
        $this->events = $container->getParameter('task.events');
        $this->normalizer = $normalizer;
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

    /**
     * Поиск изменений и предоставление произошедших событий с описанием
     *
     * @param Tasks $source
     * @param Tasks $updated
     * @param bool $attachments
     * @return array
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function checkDifference(Tasks $source, Tasks $updated, bool $attachments): array
    {
        $source = $this->normalizer->normalize($source);
        $updated = $this->normalizer->normalize($updated);
        $messages = [];
        if ($attachments) {
            $messages['attachments'] = $this->events['attachments'];
        }
        $messages = $this->checkStatus($source, $updated, $messages);
        $messages = $this->checkPriority($source, $updated, $messages);
        $messages = $this->checkDueDate($source, $updated, $messages);
        $messages = $this->checkTitle($source, $updated, $messages);
        $messages = $this->checkBody($source, $updated, $messages);
        $messages = $this->checkTimeValue($source, $updated, $messages);

        return $messages;
    }

    /**
     * Проверка статуса
     *
     * @param array $one
     * @param array $two
     * @param array $messages
     * @return array
     */
    private function checkStatus(array $one, array $two, array $messages): array
    {
        if ($one['status']['id'] !== $two['status']['id']) {
            $nameOne = $one['status']['name'];
            $nameTwo = $two['status']['name'];
            $messages['status'] = $this->events['status'].' c "'.$nameOne.'" на "'.$nameTwo.'"';
        }
        return $messages;
    }

    /**
     * Проверка приоритета
     *
     * @param array $one
     * @param array $two
     * @param array $messages
     * @return array
     */
    private function checkPriority(array $one, array $two, array $messages): array
    {
        if ($one['priority']['id'] !== $two['priority']['id']) {
            $nameOne = $one['priority']['name'];
            $nameTwo = $two['priority']['name'];
            $messages['priority'] = $this->events['priority'].' c "'.$nameOne.'" на "'.$nameTwo.'"';
        }
        return $messages;
    }

    /**
     * Проверка срока выполнения
     *
     * @param array $one
     * @param array $two
     * @param array $messages
     * @return array
     * @throws \Exception
     */
    private function checkDueDate(array $one, array $two, array $messages): array
    {
        if ($one['dueDate'] !== $two['dueDate']) {
            $nameOne = is_null($one['dueDate']) ? 'не назначен' : (new \DateTime($one['dueDate']))->format('d.m.Y');
            $nameTwo = is_null($two['dueDate']) ? 'не назначен' : (new \DateTime($two['dueDate']))->format('d.m.Y');
            $messages['dueDate'] = $this->events['dueDate'].' c "'.$nameOne.'" на "'.$nameTwo.'"';
        }
        return $messages;
    }

    /**
     * Проверка названия задачи
     *
     * @param array $one
     * @param array $two
     * @param array $messages
     * @return array
     */
    private function checkTitle(array $one, array $two, array $messages): array
    {
        if ($one['title'] !== $two['title']) {
            $messages['title'] = $this->events['title'].' c "'.$one['title'].'" на "'.$two['title'].'"';
        }
        return $messages;
    }

    /**
     * Проверка описания
     *
     * @param array $one
     * @param array $two
     * @param array $messages
     * @return array
     */
    private function checkBody(array $one, array $two, array $messages): array
    {
        if ($one['body'] !== $two['body']) {
            $messages['body'] = $this->events['body'];
        }
        return $messages;
    }

    /**
     * Проверка оценки задачи
     *
     * @param array $one
     * @param array $two
     * @param array $messages
     * @return array
     */
    private function checkTimeValue(array $one, array $two, array $messages): array
    {
        $time1 = json_decode($one['value'], true);
        $time2 = json_decode($two['value'], true);
        if ($one['value'] !== $two['value']) {
            $oneTime = is_null($one['value']) ? 'не оценена' : $time1['D'].' дн '.$time1['H'].' ч '.$time1['m'].' мин';
            $twoTime = is_null($two['value']) ? 'не оценена' : $time2['D'].' дн '.$time2['H'].' ч '.$time2['m'].' мин';
            $messages['time_value'] = $this->events['time_value'].': '.$twoTime.' (Было: '.$oneTime.')';
        }
        return $messages;
    }
}