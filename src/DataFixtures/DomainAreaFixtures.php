<?php

namespace App\DataFixtures;

use App\Entity\DomainAreas;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DomainAreaFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $areas = $this->data();
        foreach ($areas as $area) {
            $a = new DomainAreas();
            $a->setName($area['name']);
            $a->setComment($area['comment']);
            $manager->persist($a);
        }
        $manager->flush();
    }

    /**
     * Доменные области (области-направления разработки)
     *
     * @return array
     */
    private function data(): array
    {
        $data = [
            [
                'name' => 'Маркетинг',
                'comment' => 'Задачи связанные с направлением - Маркетинг.'
            ],
            [
                'name' => 'Доставка',
                'comment' => 'Задачи связанные с направлением - Доставка.'
            ],
            [
                'name' => 'Прием и обработка заказов',
                'comment' => 'Задачи связанные с оформлением. (в.т.ч и витрина)'
            ],
            [
                'name' => 'Отчеты, цифры, аналитика',
                'comment' => 'Задачи связанные с цифрами.'
            ],
            [
                'name' => 'Производство',
                'comment' => 'Задачи связанные с производственной цепочкой (Кухня, чеки и т.п).'
            ],
            [
                'name' => 'Учет',
                'comment' => 'Задачи связанные с учетом (сотрудников и позиций).'
            ]
        ];

        return $data;
    }
}
