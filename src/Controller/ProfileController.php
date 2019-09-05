<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface AS EM;
use App\Repository\UserRepository AS User;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    private $em;

    public function __construct(EM $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        return $this->render('tasks/profile/index.html.twig');
    }

    /**
     * Данные пользователя
     *
     * @Route("/profile/data", name="profile_data", methods={"GET"})
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json($user, 200);
    }

    /**
     * Сохранить данные профиля
     *
     * @Route("/profile/save", name="profile_save", methods={"PUT"})
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function saveProfile(Request $request, User $user): JsonResponse
    {
        $profile = json_decode($request->getContent(), true);
        try {
            $existProfile = $user->find($profile['id']);
            $existProfile->setFullname($profile['name']);
            $existProfile->setAbout($profile['about']);
            $existProfile->setUuid($profile['phone']);
            $existProfile->setEmail($profile['email']);
            $existProfile->setJobPosition($profile['jobPosition']);
            $existProfile->setBirthday($profile['birthday']);
            $existProfile->setEmailNotify((int)$profile['emailNotify']);
            $existProfile->setTelegramNotify((int)$profile['telegramNotify']);
            $this->em->flush();
            $response = $this->json($existProfile, 200);
        }
        catch (ORMException $e) {
            $response = $this->json([$e->getMessage()], $e->getCode());
        }
        return $response;
    }
}
