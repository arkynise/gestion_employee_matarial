<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ResetPasswordController extends AbstractController
{


    private $userRepository;
    private $entityManager;
    private $session;

    public function __construct(
        UserRepository $userRepository,EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;

    }


    public function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }








    #[Route('/reset', name: 'app_reset_password')]
    public function index(): Response
    {
        return $this->render('reset_password/resetForm.html.twig', [
            'controller_name' => 'ResetPasswordController',
        ]);
    }


    #[Route('/reset/link', name: 'app_reset_password')]
    public function sendResetLink(Request $request,): Response
    {   
        $email = $request->get('email');
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if ($user) {
            $token=$this->generateToken();
            $user->setConfirmationToken($token);
            $this->entityManager->persist($user); // Persist the user entity
            $this->entityManager->flush(); 
            $this->sendEmail($token,$email);
            $this->addFlash('error', 'we sent reset link to your email check it');
            return $this->redirectToRoute('startingPoint');
        }else{
            $this->addFlash('error', 'the email you entered doesnt exist, try later with valid one');
            return $this->redirectToRoute('resteForm');
        }
    }




    public function sendEmail(string $token,string $email): bool
    {
        // Create a new Email object
        
        $transport = Transport::fromDsn($_ENV['MAILER_DSN'] );
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from('dz.imp20@gmail.com')  // Sender email address
            ->to($email) // Recipient email address
            ->subject('Test Email')       // Subject of the email
            ->html(
                $this->renderView('reset_password/password_reset_link.html.twig', [
                    'token' => $token,
                ])
            ); // Email body

            try {
                $mailer->send($email);
        return true;
    } catch (TransportExceptionInterface $e) {
        return false;
        
        
    }
        // Return a response
       
    }





    #[Route('/password/reset/{token}', name: 'password_reset_confirm')]
    public function confirmReset(string $token): Response
    {
        $user = $this->userRepository->findOneBy(['confirmationToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Invalid token');
        }

       

       

        return $this->render('reset_password/resetPasswordForm.html.twig', [
            'email'=>$user->getEmail()
        ]);
    }


    #[Route('/reset/save', name: 'password_reset_save')]
    public function resetSave(Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {

        $email = $request->get('email');
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if($request->get('password')===$request->get('confirm_password')){
        $user->setPassword($passwordHasher->hashPassword($user,$request->get('password')));
        }
        $this->entityManager->persist($user); // Persist the user entity
        $this->entityManager->flush(); 
        
       

       

        return $this->redirectToRoute('startingPoint');
    }














}


