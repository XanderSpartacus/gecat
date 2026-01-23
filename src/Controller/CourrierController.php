<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CourrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourrierController extends AbstractController
{
    #[Route('/courrier/new', name: 'app_courrier_new')]
    public function new(): Response
    {
        return $this->render('courrier/new.html.twig');
    }

    #[Route('/courriers', name: 'app_courrier_list')]
    public function list(CourrierRepository $courrierRepository): Response
    {
        $courriers = $courrierRepository->findAll();
        return $this->render('courrier/list.html.twig', [
            'courriers' => $courriers
        ]);
    }

    #[Route('/courrier/{id}', name: 'app_courrier_show', methods: ['GET'])]
    public function show(): Response
    {
        return $this->render('courrier/show.html.twig');
    }

    #[Route('/courrier/{id}/edit', name: 'app_courrier_edit')]
    public function edit(): Response
    {
        return $this->render('courrier/edit.html.twig');
    }

    #[Route('/courrier/{id}', name: 'app_courrier_delete', methods: ['POST'])]
    public function delete(): Response
    {
        return $this->redirectToRoute('app_courrier_list');
    }
}
