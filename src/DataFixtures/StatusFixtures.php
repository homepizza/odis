<?php

namespace App\DataFixtures;

use App\Entity\Statuses;
use App\Entity\Workflow;
use App\Repository\StatusesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    private $statuses;

    public function __construct(StatusesRepository $statuses)
    {
        $this->statuses = $statuses;
    }

    public function load(ObjectManager $manager)
    {
        // Загрузка статусов
        $statuses = $this->statuses();
        foreach ($statuses as $status) {
            $st = new Statuses();
            $st->setName($status['name']);
            $st->setComment($status['comment']);
            $manager->persist($st);
        }
        $manager->flush();

        // Загрузка цепочек перехода по статусам (workflow)
        $statuses = $this->statuses->findAll();
        foreach ($statuses as $status) {
            $statusName = $status->getName();
            $workchain = $this->workchain($statusName);
            foreach ($workchain as $accessStatus) {
                $nextStatus = $this->statuses->findOneBy(['name' => $accessStatus]);
                $workflow = new Workflow();
                $workflow->setStatus($status);
                $workflow->setAccess($nextStatus);
                $manager->persist($workflow);
            }
        }
        $manager->flush();
    }

    /**
     * Возможные статусы задачи.
     *
     * @return array
     */
    private function statuses(): array
    {
        $data = [
            [
                'name' => 'Новая',
                'comment' => 'Новая задача, для выполнения'
            ],
            [
                'name' => 'В работе',
                'comment' => 'Задача выполняется исполнителем'
            ],
            [
                'name' => 'Ожидает доработки',
                'comment' => 'Задача уже была в работе, ожидает выполнения'
            ],
            [
                'name' => 'Тестирование',
                'comment' => 'Задача находится на ручной проверке у пользователей'
            ],
            [
                'name' => 'Ожидает проверки',
                'comment' => 'Задача выполнена и готова к проверке'
            ],
            [
                'name' => 'На проверке',
                'comment' => 'Выполненная задача проверяется разработкой'
            ],
            [
                'name' => 'Запрос данных',
                'comment' => 'Задача требует уточнения'
            ],
            [
                'name' => 'Согласование',
                'comment' => 'Требуется обсуждение для правильной постановки задачи'
            ],
            [
                'name' => 'Отменено',
                'comment' => 'Задача отменена'
            ],
            [
                'name' => 'Завершено',
                'comment' => 'Выполненная задача'
            ]
        ];

        return $data;
    }

    /**
     * Цепочки доступных переходов по статусам
     *
     * @param $status
     * @return array
     */
    private function workchain($status): array
    {
        $workflow = [
            'Новая' => ['Запрос данных', 'Согласование', 'Отменено', 'В работе'],
            'В работе' => ['Запрос данных', 'Согласование', 'Ожидает доработки', 'Ожидает проверки'],
            'Ожидает доработки' => ['В работе', 'Согласование', 'Запрос данных'],
            'Тестирование' => ['Ожидает доработки', 'Завершено'],
            'Ожидает проверки' => ['В работе', 'На проверке'],
            'На проверке' => ['Ожидает доработки', 'Тестирование'],
            'Запрос данных' => ['Новая', 'Отменено', 'Ожидает доработки', 'Согласование'],
            'Согласование' => ['Новая', 'Отменено', 'Ожидает доработки'],
            'Отменено' => [],
            'Завершено' => [],
        ];

        return $workflow[$status];
    }
}
