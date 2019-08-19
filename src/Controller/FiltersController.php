<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FiltersController extends AbstractController
{
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
     * @return JsonResponse
     */
    public function saveFilters(): JsonResponse
    {
        return $this->json([], 200);
    }
}
