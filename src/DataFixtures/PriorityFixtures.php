<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Priorities;

class PriorityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $priorities = $this->data();
        foreach ($priorities as $priority) {
            $prio = new Priorities();
            $prio->setName($priority['name']);
            $prio->setValue($priority['value']);
            $prio->setComment($priority['comment']);
            $manager->persist($prio);
        }
        $manager->flush();
    }

    /**
     * Приоритеты задач
     *
     * @return array
     */
    private function data(): array
    {
        $data = [
            [
                'name' => 'Низкий',
                'value' => 1,
                'comment' => 'Задачи, не для срочного выполнения, могут быть выполнены сильно позже.'
            ],
            [
                'name' => 'Средний',
                'value' => 2,
                'comment' => 'Задачи для текущего выполнения в рабочем режиме'
            ],
            [
                'name' => 'Высокий',
                'value' => 3,
                'comment' => 'Задачи для текущего выполнения в нормально режиме'
            ]
        ];

        return $data;
    }
}
