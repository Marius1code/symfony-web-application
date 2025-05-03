<?php

namespace App\Controller;

use App\Repository\MovementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movements')]
class MovementController extends AbstractController
{
    #[Route('/', name: 'movement_index', methods: ['GET'])]
    public function index(MovementRepository $movementRepository): Response
    {
        $movements = $movementRepository->findBy([], ['date' => 'DESC']);

        return $this->render('movement/index.html.twig', [
            'movements' => $movements,
        ]);
    }
}
