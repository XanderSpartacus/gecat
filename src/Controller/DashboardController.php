<?php
// src/Controller/HomeController.php
namespace App\Controller;

use App\Repository\CourrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[isGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')] // Notre route, le GPS de l'application
    public function index(CourrierRepository $courrierRepository): Response // Notre action, le point de départ du chef d'orchestre
    {
        $totalCourriers = $courrierRepository->countAll();
        $courriersEntrants = $courrierRepository->countByType('entrant');
        $courriersSortants = $courrierRepository->countByType('sortant');

        // Le contrôleur délègue la présentation de Twig via la méthode render()
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'HomeController',
            'message' => 'Bienvenue sur GECAT, votre application de gestion de courrier administratif !',
            'totalCourriers' => $totalCourriers,
            'courriersEntrants' => $courriersEntrants,
            'courriersSortants' => $courriersSortants
        ]);
    }
}
