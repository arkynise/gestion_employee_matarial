<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Entity\Notefecation;
use Doctrine\ORM\EntityManagerInterface;

class NotefecationController extends AbstractController
{


    private $entityManager;
    

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
     
        
    }


    #[Route('/notefecation/{count}', name: 'app_notefecation')]
    public function index(int $count): Response
    {

        $user = $this->getUser();
        $username = $user->getUserIdentifier();
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->findOneByUsername($username);
        $newCount=$user->getCountNTF()-$count;
        if($newCount>=0)
        $user->setCountNTF($newCount);
        $this->entityManager->flush();
        $repository = $this->entityManager->getRepository(Notefecation::class);
        $queryBuilder = $repository->createQueryBuilder('n')
            ->where('n.who != :excludedUser')
            ->setParameter('excludedUser', $username)
            ->orderBy('n.date_notefecation', 'DESC') // Value to exclude
            ->getQuery();
        $notifications = $queryBuilder->getResult();
        
        return $this->render('notefecation/index.html.twig',[
            'ntfs'=>$notifications,
        ]);
        
        
    }
}
