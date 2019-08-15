<?php

namespace App\Controller;

use App\Repository\WorkflowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WorkflowController extends AbstractController
{
    /**
     * Доступные статусы от текущего
     *
     * @Route("/workflow/status/{id}", name="workflow")
     * @param int $id
     * @param WorkflowRepository $workflow
     * @return JsonResponse
     */
    public function workflow(int $id, WorkflowRepository $workflow): JsonResponse
    {
        $statuses = $workflow->findBy(['status' => $id]);
        $result = [];
        foreach ($statuses as $status) {
            $result[] = $status->getAccess();
        }
        return $this->json($result, 200);
    }
}
