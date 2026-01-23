<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Courrier;
use App\Form\CourrierType;
use App\Repository\CourrierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourrierController extends AbstractController
{
    #[Route('/courrier/new', name: 'app_courrier_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $courrier = new Courrier();
        $form = $this->createForm(CourrierType::class, $courrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($courrier);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau courrier ajouté avec succès !');

            return $this->redirectToRoute('app_courrier_list');
        }

        return $this->render('courrier/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/courriers', name: 'app_courrier_list')]
    public function list(Request $request, CourrierRepository $courrierRepository): Response
    {
        // $courriers = $courrierRepository->findAll();

        $page = max(1, $request->query->getInt('page', 1));
        $limit = 50;

        $pagination = $courrierRepository->findPaginated($page, $limit);

        $totalItems = count($pagination);
        $totalPages = ceil($totalItems / $limit);

        return $this->render('courrier/list.html.twig', [
            'courriers' => $pagination,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'totalItems'  => $totalItems,
            'limit'       => $limit,
        ]);
    }

    #[Route('/courrier/{id}', name: 'app_courrier_show', methods: ['GET'])]
    public function show(Courrier $courrier): Response
    {
        return $this->render('courrier/show.html.twig', [
            'courrier' => $courrier,
        ]);
    }

    #[Route('/courrier/{id}/edit', name: 'app_courrier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Courrier $courrier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CourrierType::class, $courrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('warning', 'Courrier mis à jour avec succès !');

            return $this->redirectToRoute('app_courrier_list');
        }

        return $this->render('courrier/edit.html.twig', [
            'form' => $form->createView(),
            'courrier' => $courrier,
        ]);
    }

    #[Route('/courrier/{id}', name: 'app_courrier_delete', methods: ['POST'])]
    public function delete(Request $request, Courrier $courrier, EntityManagerInterface $entityManager): Response
    {
        // dd($courrier);

        if($this->isCsrfTokenValid('delete'.$courrier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($courrier);
            $entityManager->flush();

            $this->addFlash('error', 'Courrier supprimé avec succès !');
        }

        return $this->redirectToRoute('app_courrier_list');
    }
}
