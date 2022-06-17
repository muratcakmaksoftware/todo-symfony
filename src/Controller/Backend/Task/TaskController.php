<?php

namespace App\Controller\Backend\Task;

use App\Entity\Task;
use App\Enum\Task\TaskEnum;
use App\Form\Task\TaskType;
use App\Repository\Backend\Task\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/task')]
class TaskController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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
        return $this->render('backend/task/create.html.twig', [
            'taskStatusTexts' => TaskEnum::$taskStatusTextEnum
        ]);
    }

    #[Route('/store', name: 'app_task_store', methods: ['POST'])]
    public function store(Request $request, TaskRepository $taskRepository, ValidatorInterface $validator): Response
    {
        $user = $this->security->getUser();
        if ($user && $this->isCsrfTokenValid('task-store', $request->request->get('token'))) {
            $task = new Task();
            $task->setUser($user);
            $task->setTitle($request->request->get('title'));
            $task->setDescription($request->request->get('description'));
            $task->setStatus($request->request->get('status'));

            $errors = $validator->validate($task);
            if (count($errors) > 0) {
                return $this->render('backend/task/create.html.twig', [
                    'errors' => $errors,
                ]);
            }

            $taskRepository->add($task, true);
        }
        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
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
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $taskRepository->remove($task, true);
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
