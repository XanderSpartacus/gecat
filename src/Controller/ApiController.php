<?php

namespace App\Controller;

use App\Repository\CourrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse; // Ajoutez cet import

final class ApiController extends AbstractController
{

// Controller pour les requêtes API (pour comm. HTTP avec react)

    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    // Nouvel endpoint API avec une route différente
    #[Route('/api/courriers', name: 'api_courrier_list', methods: ['GET'])]
    // Renommé la méthode pour éviter les conflits
    public function apiList(Request $request, CourrierRepository $courrierRepository): JsonResponse // Retourne JsonResponse au lieu de Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        // Permettre de personnaliser la limite via le paramètre de requête
        $limit = $request->query->getInt('limit', 50);

        $filters = [
            'destinataire' => $request->query->get('destinataire'),
            'type'         => $request->query->get('type'),
            'statut'       => $request->query->get('statut'),
            'nature'       => $request->query->get('nature'),
            'gestionnaire' => $request->query->get('gestionnaire'),
            'responsable'  => $request->query->get('responsable'),
            'reference'    => $request->query->get('reference'),
            'expediteur'   => $request->query->get('expediteur'),
        ];

        $pagination = $courrierRepository->findFilteredPaginated($filters, $page, $limit);

        // NOUVEAU: Transformation des objets Courrier en tableaux pour JSON
        $courriers = [];
        foreach ($pagination as $courrier) {
            $courriers[] = [
                'id' => $courrier->getId(),
                'reference' => $courrier->getReference(),
                'objet' => $courrier->getObjet(),
                'type' => $courrier->getType(),
                'statut' => [
                    'value' => $courrier->getStatut()->value,
                    'label' => $courrier->getStatut()->getLabel(),
                    'color' => $courrier->getStatut()->getColor()
                ],
                'destinataire' => $courrier->getDestinataire(),
                'expediteur' => $courrier->getExpediteur(),
                'nature' => $courrier->getNature(),
                'dateReception' => $courrier->getDateReception()?->format('Y-m-d H:i:s'),
                'gestionnaire' => $courrier->getGestionnaire(),
                'responsable' => $courrier->getResponsable(),
            ];
        }

        $totalItems = $pagination->count();
        $totalPages = ceil($totalItems / $limit);

        // NOUVEAU: Retourne JSON au lieu de rendre un template Twig
        return $this->json([
            'courriers' => $courriers,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'totalItems' => $totalItems,
                'totalPages' => $totalPages
            ],
            'filters' => $filters
        ]);
    }

}
