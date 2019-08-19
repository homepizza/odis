<?php

namespace App\Controller;

use App\Repository\CommentsRepository;
use App\Repository\StatusesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\TasksRepository AS Tasks;
use App\Repository\PrioritiesRepository AS Priorities;
use App\Repository\TypesRepository AS Types;
use App\Repository\DomainAreasRepository AS Areas;
use App\Utils\FileUploader;
use App\Repository\HistoryStatusesRepository AS HistoryStatuses;
use App\Repository\StatusesRepository AS Statuses;

class TaskController extends AbstractController
{
    /* @var Tasks */
    private $tasks;

    /* @var Priorities */
    private $priorities;

    /* @var Types */
    private $types;

    /* @var Areas */
    private $areas;

    /* @var FileUploader */
    private $file;

    public function __construct(Tasks $tasks, Priorities $priorities, Types $types, Areas $areas, FileUploader $file)
    {
        $this->tasks = $tasks;
        $this->priorities = $priorities;
        $this->types = $types;
        $this->areas = $areas;
        $this->file = $file;
    }

    /**
     * Страница просмотра / редактирования задачи
     *
     * @Route("/task", name="task")
     */
    public function index()
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    /**
     * Приоритеты задачи
     *
     * @IsGranted({"ROLE_AUTHOR", "ROLE_DEVELOPER"})
     * @Route("/task/priorities", name="task_priorities", methods={"GET"})
     * @return JsonResponse
     */
    public function priorities(): JsonResponse
    {
        // TODO: Вынести в API все подобные
        $roles = $this->getUser()->getRoles();
        if (in_array("ROLE_AUTHOR", $roles)) {
            $id = $this->getUser()->getId();
            $tasks = $this->tasks->notEqualStatuses(['Завершено', 'Отменено'], $id, ['Средний']);
            $names = empty($tasks) ? ['Низкий', 'Средний'] : ['Низкий'];
            $priorities = $this->priorities->findBy(['name' => $names]);
        } else {
            $priorities = $this->priorities->findAll();
        }
        return $this->json($priorities, 200);
    }

    /**
     * Типы задачи
     *
     * @IsGranted({"ROLE_AUTHOR", "ROLE_DEVELOPER"})
     * @Route("/task/types", name="task_types", methods={"GET"})
     * @return JsonResponse
     */
    public function types(): JsonResponse
    {
        $types = $this->types->findAll();
        return $this->json($types, 200);
    }

    /**
     * Доменные области (направления) задачи
     *
     * @IsGranted({"ROLE_AUTHOR", "ROLE_DEVELOPER"})
     * @Route("/task/areas", name="task_domain_areas", methods={"GET"})
     * @return JsonResponse
     */
    public function areas(): JsonResponse
    {
        $areas = $this->areas->findAll();
        return $this->json($areas, 200);
    }

    /**
     * Загрузка файлов и их удаление
     *
     * @Route("/task/attach", name="task_attachment", methods={"POST", "PATCH"})
     * @param Request $request
     * @param string $uploadDir
     * @return JsonResponse
     */
    public function attachments(Request $request, string $uploadDir): JsonResponse
    {
        $method = $request->getMethod();
        if ($method === 'PATCH') {
            $link = json_decode($request->getContent(), 1)['link'];
            $file = explode('uploads', $link)[1];
            $this->file->delete('../public/uploads/'.$file);
        }
        else {
            /* @var UploadedFile */
            $file = $request->files->get('file');
            $filename = $file->getClientOriginalName();
            $link = $this->file->upload($uploadDir, $file, $filename);
        }

        return $this->json(['link' => $link], 200);
    }

    /**
     * Все исполнители
     *
     * @Route("/task/developers", name="task_developers", methods={"GET"})
     * @param UserRepository $users
     * @return JsonResponse
     */
    public function developers(UserRepository $users): JsonResponse
    {
        $developers = [];
        $users = $users->findAll();
        foreach ($users as $user) {
            if (in_array('ROLE_DEVELOPER', $user->getRoles())) {
                $developers[] = $user;
            }
        }
        return $this->json($developers, 200);
    }

    /**
     * Данные текущей задачи
     *
     * @Route("/task/{id}/data", name="task_data", methods={"GET"})
     * @param int $id
     * @param HistoryStatuses $history
     * @param Statuses $statuses
     * @return JsonResponse
     */
    public function taskData(int $id, HistoryStatuses $history, Statuses $statuses): JsonResponse
    {
        $task = $this->tasks->find($id);
        $workStatus = $statuses->findBy(['name' => 'В работе']);
        $hasWork = $history->findBy(['task' => $task, 'status' => $workStatus]);
        $hasWork = !empty($hasWork);
        return $this->json(['task' => $task, 'hasWork' => $hasWork], 200);
    }

    /**
     * Информация о участниках задачи и их активности в комментариях
     *
     * @Route("/task/{id}/members", name="task_members", methods={"GET"})
     * @param int $id
     * @param CommentsRepository $comments
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function membersInfo(int $id, CommentsRepository $comments): JsonResponse
    {
        $user = $this->getUser();
        $task = $this->tasks->find($id);
        $isAuthor = $this->getUser()->getId() === $task->getAuthor()->getId();
        $isAuthor = in_array('ROLE_DEVELOPER', $user->getRoles()) ? false : $isAuthor;
        $messages = $comments->messages($task->getId());
        $owners = !empty($task->getAsignee())
            ? [$task->getAuthor()->getId(), $task->getAsignee()->getId()]
            : [$task->getAuthor()->getId()];
        $members = $comments->members($task->getId(), $owners);
        foreach ($members as $k => $member) {
            $member['usertype'] = 'Обсуждение';
            $members[$k] = $member;
        }
        if (!empty($task->getAsignee())) {
            $members[] = [
                'id' => $task->getAsignee()->getId(),
                'fullname' => $task->getAsignee()->getFullname(),
                'usertype' => 'Исполнитель'
            ];
        }
        $members[] = [
            'id' => $task->getAuthor()->getId(),
            'fullname' => $task->getAuthor()->getFullname(),
            'usertype' => 'Автор'
        ];

        return $this->json([
            'members'  => array_reverse($members),
            'messages' => $messages,
            'author'   => $isAuthor
        ], 200);
    }
}
