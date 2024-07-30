<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StartingPointController extends AbstractController
{
    #[Route('/', name: 'app_stating_point')]
    public function index(): Response
    {
        return $this->render('starting_point/index.html.twig', [
            'controller_name' => 'StatingPointController',
        ]);
    }
}
