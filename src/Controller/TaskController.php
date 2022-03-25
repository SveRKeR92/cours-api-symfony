<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks', name: 'app_tasks')]
class TaskController extends AbstractController
{
    public function __construct(
        private TaskRepository $taskRepository,
        UserRepository         $userRepository,
        RequestStack           $requestStack,
    )
    {
        $apiKey = $requestStack->getCurrentRequest()->headers->get('x-api-key');
        $user = $userRepository->findOneBy([
            'api_key' => $apiKey
        ]);

        if (!$user instanceof User) {
            throw new AccessDeniedHttpException('Authentication needed');
        }
    }


    #[Route('', name: 'tasks_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json($this->taskRepository->findAll());
    }


    #[Route('/{id}', name: 'tasks_get', methods: ['GET'])]
    public function show($id): Response
    {
        $task = $this->taskRepository->find($id);
        if (!$task instanceof Task) {
            throw new NotFoundHttpException('Task not Found');
        }
        return $this->json($task);
    }


    #[Route('', name: 'tasks_post', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $task = new Task;

        $form = $this->createForm(TaskType::class, $task);
        $form->submit(json_decode($request->getContent(), true));

        $this->taskRepository->add($task);

        return $this->json($task);
    }


    #[Route('/{id}', name: 'tasks_update', methods: ['PUT'])]
    public function update($id, Request $request): Response
    {
        $task = $this->taskRepository->find($id);

        if (!$task instanceof Task) {
            throw new NotFoundHttpException('Task Not Found');
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->submit(json_decode($request->getContent(), true));

        $this->taskRepository->add($task);

        return $this->json($task);
    }


    #[Route('/{id}', name: 'task_delete', methods: ['DELETE'])]
    public function delete($id): Response
    {

        $task = $this->taskRepository->find($id);
        if (!$task instanceof Task) {
            throw new NotFoundHttpException('Task Not Found');
        }
        $this->taskRepository->remove($task);

        return $this->json(true);
    }
}
