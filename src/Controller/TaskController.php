<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks', name: 'app_tasks')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'tasks_index')]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->json($taskRepository->findAll());
    }
}
