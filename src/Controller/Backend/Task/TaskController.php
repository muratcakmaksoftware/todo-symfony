<?php

namespace App\Controller\Backend\Task;

use App\Entity\Task;
use App\Form\Task\TaskType;
use App\Repository\Backend\Task\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/task')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('backend/task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'app_task_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->renderForm('backend/task/create.html.twig', [
            'taskTextStatus' => Task::getTextStatus()
        ]);
    }

    #[Route('/store', name: 'app_task_store', methods: ['POST'])]
    public function store(Task $task, TaskRepository $taskRepository): Response
    {
        dd($task);
        $taskRepository->add($task, true);
        return $this->redirectToRoute('app_task_index', [], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('backend/task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskRepository->add($task, true);

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $taskRepository->remove($task, true);
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
