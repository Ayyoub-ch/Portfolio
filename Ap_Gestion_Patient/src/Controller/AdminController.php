<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/new', name: 'app_admin_users_new')]
    public function newUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès !');
            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/user_form.html.twig', [
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    #[Route('/admin/users/{id}/edit', name: 'app_admin_users_edit')]
    public function editUser(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserFormType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès !');
            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/user_form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'is_edit' => true,
        ]);
    }

    #[Route('/admin/users/{id}/delete', name: 'app_admin_users_delete', methods: ['POST'])]
    public function deleteUser(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // Prevent deleting yourself
            if ($this->getUser() === $user) {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte !');
            } else {
                $entityManager->remove($user);
                $entityManager->flush();
                $this->addFlash('success', 'Utilisateur supprimé avec succès !');
            }
        }

        return $this->redirectToRoute('app_admin_users');
    }
}


/*
===========================
Récapitulatif des fonctions du contrôleur Admin
===========================

index - but : Afficher le tableau de bord administrateur.
explications : Affiche la page d'accueil de l'espace admin (index.html.twig).

users - but : Afficher la liste des utilisateurs.
explications : Récupère tous les utilisateurs et les affiche (users.html.twig).

newUser - but : Ajouter un nouvel utilisateur.
explications : Affiche le formulaire de création, gère la soumission, hash le mot de passe, sauvegarde l'utilisateur (user_form.html.twig).

===========================
Récapitulatif des principaux fichiers Twig
===========================

index.html.twig - but : Tableau de bord administrateur.
explication : Propose l'accès à la gestion des utilisateurs et autres fonctionnalités admin.

users.html.twig - but : Liste des utilisateurs.
explication : Affiche tous les utilisateurs, permet d'ajouter, modifier ou supprimer un utilisateur.

user_form.html.twig - but : Formulaire d'ajout/modification d'utilisateur.
explication : Permet de saisir ou modifier les informations d'un utilisateur.

*/