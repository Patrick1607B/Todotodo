<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\TasksType;
use App\Repository\StatusRepository;
use App\Repository\TasksRepository;
use App\Repository\TodoListsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks')]
class TasksController extends AbstractController
{
    #[Route('/{id}', name: 'app_tasks_index', methods: ['GET'])]
    public function index(TasksRepository $tasksRepository, StatusRepository $statusRepository, $id): Response
    {
        $messageTaskNotEnd = ["Rien ne sert de courir car cela fatigue", "Ne fait jamais le jour même ce que tu porrais faire le lendemain"];
        $messageTaskNotEndRandom = rand(0,count($messageTaskNotEnd)-1);

        $tasks = $tasksRepository->findBy(['tasksTodolists' => $id]);
        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasks,
            'status' => $statusRepository->findAll(),
            'id' => $id,
            'message' => $messageTaskNotEnd[$messageTaskNotEndRandom]
        ]);
    }

    #[Route('/new/{id}', name: 'app_tasks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $id,
    TodoListsRepository  $todoListsRepository): Response
    {
        $todoLists = $todoListsRepository->findBy(['id' => $id])[0];


        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setTasksTodolists($todoLists);

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute(
                'app_tasks_index',
                [
                    'id' => $id
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('tasks/new.html.twig', [
            'task' => $task,
            'form' => $form,
            'id' => $id
        ]);
    }

    // #[Route('/{id}', name: 'app_tasks_show', methods: ['GET'])]
    // public function show(Tasks $task, StatusRepository $statusRepository): Response
    // {
    //     dd($statusRepository->findAll());
    //     return $this->render('tasks/show.html.twig', [
    //         'task' => $task,
    //         'status' => $statusRepository->findAll()
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_tasks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tasks $task, EntityManagerInterface $entityManager, $id): Response
    {
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tasks_index', [
                'id' => $id
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form,
            'id' => $id
        ]);
    }

    #[Route('/{id_todo}/{id}', name: 'app_tasks_delete', methods: ['POST'])]
    public function delete(Request $request, Tasks $task, EntityManagerInterface $entityManager, $id_todo): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tasks_index', [
            'id' => $task->getTasksTodolists()->getId()
        ], Response::HTTP_SEE_OTHER);
    }

    #[Route('/statusTask/{task_id}/{etat_id}', name: 'app_tasks_edit_state', methods: ['GET'])]
    public function editState(Request $request, EntityManagerInterface $entityManager, $task_id, $etat_id, TasksRepository $tasksRepository, StatusRepository $statusRepository): Response
    {
        $task = $tasksRepository->findOneBy(['id' => $task_id]);
        $state = $statusRepository->findOneBy(['id' => $etat_id]);
        // dd($state);
        $task->setStatus($state);
        $entityManager->flush();
        // dd($task);
        // dd($task->getTasksTodolists()->getId());
        return $this->redirectToRoute('app_tasks_index', [
            'id' => $task->getTasksTodolists()->getId()
        ], Response::HTTP_SEE_OTHER);
        // dd($task);
    }
}
