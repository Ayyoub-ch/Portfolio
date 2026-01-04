<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}


/*
===========================
Récapitulatif des fonctions du contrôleur Security
===========================

login - but : Afficher et traiter le formulaire de connexion.
explications : Affiche le formulaire de connexion, gère l’affichage des erreurs et du dernier nom d’utilisateur saisi (login.html.twig).

logout - but : Déconnexion de l’utilisateur.
explications : Méthode interceptée par le firewall de sécurité, ne contient pas de logique métier.

===========================
Récapitulatif des principaux fichiers Twig
===========================

login.html.twig - but : Afficher le formulaire de connexion.
explication : Affiche les champs de connexion, les erreurs éventuelles et le dernier nom d’utilisateur saisi.

*/