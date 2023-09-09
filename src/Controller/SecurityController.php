<?php

namespace App\Controller;

use App\Repository\TasksRepository;
use App\Repository\TodoListsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, TodoListsRepository $todoListsRepository, TasksRepository $tasksRepository, UserRepository $userRepository): Response
    {
        $cptTodo = count($todoListsRepository->findAll());
        $cptTask = count($tasksRepository->findAll());
        $cptUser = count($userRepository->findAll());
        if ($this->getUser()) {
            return $this->redirectToRoute('app_todo_lists_index'); 
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'cptTodo' => $cptTodo, 'cptTask' => $cptTask, 'cptUser' => $cptUser ]);

    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
