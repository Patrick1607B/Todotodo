<?php

namespace App\Controller;

use App\Entity\TodoLists;
use App\Form\TodoListsType;
use App\Repository\TodoListsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo/lists')]
class TodoListsController extends AbstractController
{
    #[Route('/', name: 'app_todo_lists_index', methods: ['GET'])]
    public function index(TodoListsRepository $todoListsRepository): Response
    {
        return $this->render('todo_lists/index.html.twig', [
            'todo_lists' => $todoListsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_todo_lists_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todoList = new TodoLists();



        $form = $this->createForm(TodoListsType::class, $todoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $todoList->setOwner($this->getUser());

            $entityManager->persist($todoList);
            $entityManager->flush();

            return $this->redirectToRoute('app_todo_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('todo_lists/new.html.twig', [
            'todo_list' => $todoList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_todo_lists_show', methods: ['GET'])]
    public function show(TodoLists $todoList): Response
    {
        return $this->render('todo_lists/show.html.twig', [
            'todo_list' => $todoList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_todo_lists_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TodoLists $todoList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TodoListsType::class, $todoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_todo_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('todo_lists/edit.html.twig', [
            'todo_list' => $todoList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_todo_lists_delete', methods: ['POST'])]
    public function delete(Request $request, TodoLists $todoList, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $todoList->getId(), $request->request->get('_token'))) {
            $entityManager->remove($todoList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_todo_lists_index', [], Response::HTTP_SEE_OTHER);
    }
}
