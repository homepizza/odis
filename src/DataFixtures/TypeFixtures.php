<?php

namespace App\DataFixtures;

use App\Entity\Types;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = $this->data();
        foreach ($categories as $category) {
            $ct = new Types();
            $ct->setName($category['name']);
            $ct->setComment($category['comment']);
            $manager->persist($ct);
        }
        $manager->flush();
    }

    /**
     * Типы задач.
     *
     * @return array
     */
    private function data(): array
    {
        $data = [
            [
                'name' => 'Техподдержка',
                'comment' => 'Все, что связано с рабочим состоянием программы'
            ],
            [
                'name' => 'Доработки',
                'comment' => 'Все, что связано с изменением существующего функционала'
            ],
            [
                'name' => 'Развитие',
                'comment' => 'Все, что связано с привнесением нового функционала'
            ],
        ];
        return $data;
    }
}
