<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
/*

Contrôleur : MainController

Fonctions principales :

index : Affiche la page principale de l'application.

Détail des méthodes :

index() : Route '/main', rend le template 'main/index.html.twig'.

Templates Twig associés :

index.html.twig :

Affiche un message selon l'état de connexion de l'utilisateur (connecté ou non).
Si l'utilisateur n'est pas connecté, affiche "Vous etes pas encore connecté".
Si l'utilisateur est connecté, affiche "Vous etes connecté".

Auteur : Rayan Arabi
Date de dernière modification : Inconnu
**/