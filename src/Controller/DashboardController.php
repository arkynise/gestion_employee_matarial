<?php

namespace App\Controller;

use App\Entity\Notefecation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;


class DashboardController extends AbstractController
{



    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }



    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {

        $user = $this->getUser();
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $queryBuilder->select('e')
            ->from(Notefecation::class, 'e')
            ->where('e.seen = :seen')
            ->andWhere('e.who <> :user')
            ->setParameter('seen', false)
            ->setParameter('user', $user->getUserIdentifier());
        $data = $queryBuilder->getQuery()->getResult();
        $numberOfItems = count($data);
        // In your controller
        $this->addFlash('notefecation', $numberOfItems);

        return $this->render('dashboard/welcomePage.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/dashboard/side_bar', name: 'dashboard_partial')]
    public function data(): JsonResponse
    {
        // Fetch data from the database
        $user = $this->getUser(); // Adjust query as needed
        $username=$user->getUserIdentifier();
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->findOneByUsername($username);
        $newCount=$user->getCountNTF();
        
        return new JsonResponse(['count' => $newCount]);
    }
}
