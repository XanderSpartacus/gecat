<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')] // Notre route, le GPS de l'application
    public function index(): Response // Notre action, le point de départ du chef d'orchestre
    {
        // Le contrôleur délègue la présentation de Twig via la méthode render()
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'HomeController',
            'message' => 'Bienvenue sur GECAT, votre application de gestion de courrier administratif !'
        ]);
    }
}
