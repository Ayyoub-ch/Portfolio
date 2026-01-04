<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}


/*
===========================
Récapitulatif des fonctions du contrôleur Registration
===========================

register - but : Afficher et traiter le formulaire d'inscription utilisateur.
explications : Affiche le formulaire d'inscription (RegistrationFormType), vérifie la validité, hash le mot de passe, enregistre l'utilisateur et redirige vers la page principale (register.html.twig).

===========================
Récapitulatif des principaux fichiers Twig
===========================

register.html.twig - but : Afficher le formulaire d'inscription.
explication : Affiche les champs email, mot de passe, conditions d'utilisation, gère les erreurs et propose un lien vers la page de connexion.

*/