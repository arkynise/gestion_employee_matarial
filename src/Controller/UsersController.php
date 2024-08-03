<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersController extends AbstractController
{



    #[Route('/users', name: 'app_users')]
    public function index(UserRepository $user): Response
    {
        $users=$user->findAll();

        return $this->render('users/users.html.twig',[
            'users'=>$users
        ]);
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
        $email=$request->get('email');
        if($this->checkUserName($request->get('username'),$entityManager)){
            $this->addFlash('error_username', 'the username you used before already exist, you need to enter new one');
            return $this->redirectToRoute('user_add_form');
        }
        $user->setEmail($email);

        $user->setUserName($request->get('username'));
        if($this->checkUserEmail($email,$entityManager)){
            $this->addFlash('error_email', 'the user email you used before already exist, you need to enter new one');
            return $this->redirectToRoute('user_add_form');
        }
        $password=$request->get('password');

        $user->setPassword($passwordHasher->hashPassword($user,$password));
        

        $user->setRoles([$request->get('type')]);
        
        
        try{
            $entityManager->persist($user);
        $entityManager->flush();
        $this->sendEmail($email,$password);
        return $this->redirectToRoute('display_users_list');
        }catch (TransportExceptionInterface $e) {
            return $this->redirectToRoute('user_add_form');
        }
        
        
    }

    public function sendEmail(string $email,string $password): bool
    {
        // Create a new Email object
        
        $transport = Transport::fromDsn($_ENV['MAILER_DSN'] );
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from('dz.imp20@gmail.com')  // Sender email address
            ->to($email) // Recipient email address
            ->subject('Test Email')       // Subject of the email
            ->html(
                '<h1>your password for the website is'.$password.' </h1>'
            ); // Email body

            try {
                $mailer->send($email);
        return true;
    } catch (TransportExceptionInterface $e) {
        return false;
        
        
    }
        // Return a response
       
    }


    public function checkUserEmail(string $email,EntityManagerInterface $emtityManager):bool{
        
         $dql = "SELECT u FROM App\Entity\User u WHERE u.email = :email ";
        $query=$emtityManager->createQuery($dql)
        ->setParameter('email', $email);
        
        $user=$query->getResult();
        if ($user) {
            return true;
        }
        return false;
    }


    public function checkUserName(string $username,EntityManagerInterface $emtityManager):bool{
        
        $dql = "SELECT u FROM App\Entity\User u WHERE u.username = :username ";
       $query=$emtityManager->createQuery($dql)
       ->setParameter('username', $username);
       
       $user=$query->getResult();
       if ($user) {
           return true;
       }
       return false;
   }



   #[Route('/user/search', name: 'search_employee')]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT u FROM App\Entity\User u
             WHERE u.username LIKE :searchInput OR
              u.email LIKE :searchInput '
        );
        $searchInput = $request->get('search_field');
        $query->setParameter('searchInput', $searchInput . '%');
        $users = $query->getResult();
        $length = count($users);
        return $this->render('users/users.html.twig', [
            'users' => $users,
            'count' => $length
        ]);
    }

}
