<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(): Response
    {
        return $this->render('users/users.html.twig');
    }


    #[Route('/users/add', name: 'app_users')]
    public function userForm(): Response
    {
        return $this->render('users/addUser.html.twig');
    }

    #[Route('/users/save', name: 'app_users')]
    public function saveUser(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User();
        $user->setEmail($request->get('email'));
        $user->setUserName($request->get('username'));
        $user->setPassword($passwordHasher->hashPassword($user,$request->get('password')));
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->render('users/users.html.twig');
    }



}
