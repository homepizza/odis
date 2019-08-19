<?php

namespace App\Controller;

use App\Entity\Filters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface AS EM;
use App\Repository\UserRepository AS Users;
use App\Repository\PrioritiesRepository AS Priorities;
use App\Repository\TypesRepository AS Types;
use App\Repository\DomainAreasRepository AS Areas;

class FiltersController extends AbstractController
{
    private $em;
    private $user;
    private $priorities;
    private $types;
    private $areas;

    public function __construct(EM $em, Users $user, Priorities $priorities, Types $types, Areas $areas)
    {
        $this->em = $em;
        $this->user = $user;
        $this->priorities = $priorities;
        $this->types = $types;
        $this->areas = $areas;
    }

    /**
     * Получить список сохраненных фильтров
     *
     * @Route("/filters", name="filters", methods={"GET"})
     */
    public function filters(): JsonResponse
    {
        return $this->json([], 200);
    }

    /**
     * Сохранить фильтр
     *
     * @Route("/filters/save", name="filters_save", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function saveFilters(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $filter = new Filters();
        $filter->setName($data['name']);
        $filter->setDueFrom(empty($data['dueFrom']) ? null : new \DateTime($data['dueFrom']));
        $filter->setDueTo(empty($data['dueTo']) ? null : new \DateTime($data['dueTo']));

        $author = $this->user->find($data['author']['id'] ?? 0);
        $asignee = $this->user->find($data['asignee']['id'] ?? 0);

        // Приоритет
        $priority = $this->priorities->find($data['priority']['id'] ?? 0);

        // Тип
        $type = $this->types->find($data['type']['id'] ?? 0);

        // Доменная область (направление)
        $area = $this->areas->find($data['area']['id'] ?? 0);

        $filter->setAuthor($author);
        $filter->setAsignee($asignee);
        $filter->setPriority($priority);
        $filter->setType($type);
        $filter->setArea($area);
        $filter->setUser($user);
        $this->em->persist($filter);
        $this->em->flush();

        return $this->json([], 200);
    }

    /**
     * Авторы и исполнители
     *
     * @Route("/filters/users", name="filters_users", methods={"GET"})
     * @return JsonResponse
     */
    public function users(): JsonResponse
    {
        $users = $this->user->findAll();
        return $this->json($users, 200);
    }

    /**
     * Приоритеты
     *
     * @Route("/filters/priorities", name="filters_priorities", methods={"GET"})
     * @return JsonResponse
     */
    public function priorities(): JsonResponse
    {
        $priorities = $this->priorities->findAll();
        return $this->json($priorities, 200);
    }
}
