<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WorkProcessController extends AbstractController
{
    /**
     * Ход работы (Диаграмма Ганта)
     *
     * @Route("/work-process", name="work_process")
     */
    public function index()
    {
        return $this->render('tasks/work_process/index.html.twig');
    }
}
