<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Patient;
use App\Entity\Sejour;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;


final class AdministratifController extends AbstractController
{
    #[Route('/administratif/dashboard', name: 'app_administratif')]
    public function index(): Response
    {
        return $this->render('administratif/index.html.twig');
    }
    
    //Partie Patient
    #[Route('/administratif/patient', name: 'app_patient')]
    public function patients(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Patient::class);
        $patients = $repository->findAll(); // Récupère tous les patients
        return $this->render('administratif/patient.html.twig', [
            'patients' => $patients,
        ]);
    }

    /*Supprimer un patient*/
    #[Route('/administratif/retirerPatient/{id}', name: 'app_retirer_patient', methods: ['POST'])]
    public function retirerPatient(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(Patient::class);
        $em = $doctrine->getManager();

        // Récupération du patient à supprimer
        $patient = $repository->find($id);

        if ($patient) {
            $em->remove($patient);
            $em->flush();

            $this->addFlash('success', 'Le patient a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Aucun patient trouvé avec cet ID.');
        }

        // Redirection vers la liste des patients après suppression
        return $this->redirectToRoute('app_patient');
    }

    #[Route('/administratif/ajoutPatient', name: 'app_ajout_patient', methods: ['GET'])]
    public function afficherFormulaire(ManagerRegistry $doctrine): Response
        // MODIF: On passe la liste des localités à la vue pour le select dans le formulaire d'ajout de patient
    {
        $localites = $doctrine->getRepository(\App\Entity\Localite::class)->findAll();
        return $this->render('administratif/ajouter_patient.html.twig', [
            'localites' => $localites
        ]);
    }


    /* Ajouter un patient */
    #[Route('/administratif/ajouterPatient', name: 'app_ajouter_patient', methods: ['POST'])]
    public function ajouterPatient(Request $request, ManagerRegistry $doctrine): Response
        // MODIF: On récupère l'id de la localité sélectionnée dans le formulaire et on l'associe au patient
    {
        $em = $doctrine->getManager();
        $patient = new Patient();

        // Récupération des données
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $telephone = $request->request->get('telephone');
        $sexe = $request->request->get('sexe');
        $note = $request->request->get('note');
        $localiteId = $request->request->get('localite_id');

        // Affectation
        $patient->setNom($nom);
        $patient->setPrenom($prenom);
        $patient->setTelephone($telephone);
        $patient->setSexe($sexe);
        $patient->setNote($note);
        // Gestion de la localité
        $localite = $doctrine->getRepository(\App\Entity\Localite::class)->find($localiteId);
        $patient->setLocalite($localite);

        // Sauvegarde
        $em->persist($patient);
        $em->flush();

        // Message de réussite
        $this->addFlash('success', 'Le patient a été ajouté avec succès.');

        // Redirection immédiate vers la liste
        return $this->redirectToRoute('app_patient');
    }


    /* Modifier un patient */
    #[Route('/administratif/modifierPatient/{id}', name: 'app_modifier_patient', methods: ['GET', 'POST'])]
    public function modifierPatient(Request $request, ManagerRegistry $doctrine, $id): Response
        // MODIF: On passe la liste des localités à la vue pour le select dans le formulaire de modification de patient
        // MODIF: On récupère l'id de la localité sélectionnée dans le formulaire et on l'associe au patient
    {
        $repository = $doctrine->getRepository(Patient::class);
        $em = $doctrine->getManager();

        $patient = $repository->find($id);

        if (!$patient) {
            $this->addFlash('error', 'Aucun patient trouvé avec cet ID.');
            return $this->redirectToRoute('app_patient');
        }

        if ($request->isMethod('POST')) {
            $patient->setNom($request->request->get('nom'));
            $patient->setPrenom($request->request->get('prenom'));
            $patient->setTelephone($request->request->get('telephone'));
            $patient->setSexe($request->request->get('sexe'));
            $patient->setNote($request->request->get('note'));
            // Gestion de la localité
            $localiteId = $request->request->get('localite_id');
            $localite = $localiteId ? $doctrine->getRepository(\App\Entity\Localite::class)->find($localiteId) : null;
            $patient->setLocalite($localite);
            $em->flush();
            $this->addFlash('success', 'Le patient a été modifié avec succès.');
            return $this->redirectToRoute('app_patient');
        }
        $localites = $doctrine->getRepository(\App\Entity\Localite::class)->findAll();
        return $this->render('administratif/modifier_patient.html.twig', [
            'patient' => $patient,
            'localites' => $localites
        ]);
    }   



    //Partie Séjour
    #[Route('/administratif/sejour', name: 'app_sejour')]
    public function sejours(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Sejour::class);
        $sejours = $repository->findAll(); // Récupère tous les séjours
        return $this->render('administratif/sejour.html.twig', [
            'sejours' => $sejours,
        ]);
    }


    /*Supprimer un sejour*/
    #[Route('/retirerSejour/{id}', name: 'app_retirer_sejour', methods: ['POST'])]
    public function retirerSejour(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(Sejour::class);
        $em = $doctrine->getManager();

        // Récupération du patient à supprimer
        $sejour = $repository->find($id);

        if ($sejour) {
            $em->remove($sejour);
            $em->flush();

            $this->addFlash('success', 'Le sejour a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Aucun sejour trouvé avec cet ID.');
        }

        // Redirection vers la liste des sejours après suppression
        return $this->redirectToRoute('app_sejour');
    }

    #[Route('/administratif/ajoutSejour', name: 'app_ajout_sejour', methods: ['GET'])]
    public function afficherFormulaireSejour(ManagerRegistry $doctrine): Response
    {
        // Récupération des listes pour les selects
        $patientRepository = $doctrine->getRepository(Patient::class);
        $chambreRepository = $doctrine->getRepository(\App\Entity\Chambre::class);
        $localiteRepository = $doctrine->getRepository(\App\Entity\Localite::class);
        
        
        $patients = $patientRepository->findAll();
        $chambres = $chambreRepository->findAll();
        
        return $this->render('administratif/ajouter_sejour.html.twig', [
            'patients' => $patients,
            'chambres' => $chambres,
        ]);
    }

    /* Ajouter un patient */
    #[Route('/administratif/ajouterSejour', name: 'app_ajouter_sejour', methods: ['POST'])]
    public function ajouterSejour(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $sejour = new Sejour();

        // Récupération des données
        $date_entree = $request->request->get('date_entree');
        $date_sortie = $request->request->get('date_sortie');
        $libelle = $request->request->get('libelle');
        $statut_du_jour = $request->request->get('statut_du_jour');

        // Affectation
        $sejour->setDateEntree(new \DateTime($date_entree));
        $sejour->setDateSortie(new \DateTime($date_sortie));
        $sejour->setLibelle($libelle);
        $sejour->setStatutDuJour($statut_du_jour);

        // Gestion des relations
        $patientId = $request->request->get('patient_id');
        $patientRepository = $doctrine->getRepository(Patient::class);
        if ($patientId) {
            $patient = $patientRepository->find($patientId);
            $sejour->setPatient($patient);
        }

        $chambreId = $request->request->get('chambre_id');
        $chambreRepository = $doctrine->getRepository(\App\Entity\Chambre::class);
        if ($chambreId) {
            $chambre = $chambreRepository->find($chambreId);
            $sejour->setChambre($chambre);
        }

        // Sauvegarde
        $em->persist($sejour);
        $em->flush();

        // Message de réussite
        $this->addFlash('success', 'Le sejour a été ajouté avec succès.');

        // Redirection immédiate vers la liste
        return $this->redirectToRoute('app_sejour');
    }




    /* Modifier un séjour */
    #[Route('/administratif/modifierSejour/{id}', name: 'app_modifier_sejour', methods: ['GET', 'POST'])]
    public function modifierSejour(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(Sejour::class);
        $patientRepository = $doctrine->getRepository(Patient::class);
        $chambreRepository = $doctrine->getRepository(\App\Entity\Chambre::class);
        $em = $doctrine->getManager();

        $sejour = $repository->find($id);

        if (!$sejour) {
            $this->addFlash('error', 'Aucun sejour trouvé avec cet ID.');
            return $this->redirectToRoute('app_sejour');
        }

        if ($request->isMethod('POST')) {
            // Conversion des dates
            $dateEntree = $request->request->get('date_entree');
            $dateSortie = $request->request->get('date_sortie');
            
            $sejour->setDateEntree(new \DateTime($dateEntree));
            $sejour->setDateSortie(new \DateTime($dateSortie));
            $sejour->setLibelle($request->request->get('libelle'));
            $sejour->setStatutDuJour($request->request->get('statut_du_jour'));
            
            // Gestion des relations
            $patientId = $request->request->get('patient_id');
            $chambreId = $request->request->get('chambre_id');
            
            if ($patientId) {
                $patient = $patientRepository->find($patientId);
                $sejour->setPatient($patient);
            }
            
            if ($chambreId) {
                $chambre = $chambreRepository->find($chambreId);
                $sejour->setChambre($chambre);
            }
            
            $em->flush();

            $this->addFlash('success', 'Le sejour a été modifié avec succès.');

            return $this->redirectToRoute('app_sejour');
        }

        // Récupération des listes pour les selects
        $patients = $patientRepository->findAll();
        $chambres = $chambreRepository->findAll();

        return $this->render('administratif/modifier_sejour.html.twig', [
            'sejour' => $sejour,
            'patients' => $patients,
            'chambres' => $chambres,
        ]);
    }   
}


/*
===========================
Récapitulatif des fonctions du contrôleur Administratif
===========================

index - but : Afficher le tableau de bord administratif.
explications : Propose l'accès à la gestion des patients et des séjours (index.html.twig).

patients - but : Afficher la liste de tous les patients.
explications : Récupère tous les patients et les affiche (patient.html.twig).

retirerPatient - but : Supprimer un patient.
explications : Supprime un patient par son id, affiche un message de succès ou d'erreur, puis redirige vers la liste des patients.

afficherFormulaire (GET) - but : Afficher le formulaire d'ajout de patient.
explications : Passe la liste des localités à la vue pour le formulaire (ajouter_patient.html.twig).

ajouterPatient (POST) - but : Ajouter un nouveau patient.
explications : Récupère les données du formulaire, crée un patient, l'associe à une localité, sauvegarde et redirige vers la liste.

modifierPatient (GET/POST) - but : Modifier un patient existant.
explications : Affiche le formulaire pré-rempli, puis met à jour le patient avec les nouvelles données (modifier_patient.html.twig).

sejours - but : Afficher la liste de tous les séjours.
explications : Récupère tous les séjours et les affiche (sejour.html.twig).

retirerSejour - but : Supprimer un séjour.
explications : Supprime un séjour par son id, affiche un message de succès ou d'erreur, puis redirige vers la liste des séjours.

afficherFormulaireSejour (GET) - but : Afficher le formulaire d'ajout de séjour.
explications : Passe la liste des patients et des chambres à la vue pour le formulaire (ajouter_sejour.html.twig).

ajouterSejour (POST) - but : Ajouter un nouveau séjour.
explications : Récupère les données du formulaire, crée un séjour, l'associe à un patient et une chambre, sauvegarde et redirige vers la liste.

modifierSejour (GET/POST) - but : Modifier un séjour existant.
explications : Affiche le formulaire pré-rempli, puis met à jour le séjour avec les nouvelles données (modifier_sejour.html.twig).


===========================
Récapitulatif des principaux fichiers Twig
===========================

index.html.twig - but : Tableau de bord administratif.
explication : Propose l'accès à la gestion des patients et des séjours.

patient.html.twig - but : Liste des patients.
explication : Affiche tous les patients, permet d'ajouter, modifier ou supprimer un patient.

ajouter_patient.html.twig - but : Formulaire d'ajout de patient.
explication : Permet de saisir les informations d'un nouveau patient.

modifier_patient.html.twig - but : Formulaire de modification de patient.
explication : Permet de modifier les informations d'un patient existant.

sejour.html.twig - but : Liste des séjours.
explication : Affiche tous les séjours, permet d'ajouter, modifier ou supprimer un séjour.

ajouter_sejour.html.twig - but : Formulaire d'ajout de séjour.
explication : Permet de saisir les informations d'un nouveau séjour.

modifier_sejour.html.twig - but : Formulaire de modification de séjour.
explication : Permet de modifier les informations d'un séjour existant.

*/