<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Patient;
use App\Entity\Sejour;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SejourRepository;
use Symfony\Component\HttpFoundation\Request;

class InfirmierController extends AbstractController
{
    #[Route('/infirmier/', name: 'app_infirmier')]
    public function index(): Response
    {
        return $this->render('infirmier/index.html.twig');
    }

    //Gestion des séjours des patients
    #[Route('/infirmier/sejours', name: 'app_gestion')]
    public function gestionSejours(): Response {
        return $this->render('infirmier/index_gestion.html.twig'); 
    }


    #[Route('/infirmier/arrivee', name: 'app_arrivee')]
    public function arriveePatient(EntityManagerInterface $em, SejourRepository $sejours): Response {
        $repository = $em->getRepository(Sejour::class);
        $sejours = $repository->findAll(); // Récupère tous les séjours
        return $this->render('infirmier/arrivee_patient.html.twig',
         [
            'sejours' => $sejours
        ]);
    }

    #[Route('/infirmier/arrivee/aujourdhui', name: 'app_arrivee_aujourdhui')]
    public function arriveePatientAujourdhui(EntityManagerInterface $em): Response {
        // Récupère le repository de l'entité Sejour
        $repository = $em->getRepository(Sejour::class);

        // Définit le début de la journée (00:00:00)
        $start = (new \DateTimeImmutable('today'))->setTime(0, 0, 0);

        // Définit la fin de la journée (00:00:00 du lendemain)
        $end = $start->modify('+1 day');

        // Crée une requête pour sélectionner les séjours dont la date d'entrée est aujourd'hui
        $qb = $repository->createQueryBuilder('s');
        $qb->where('s.date_entree >= :start') // Date d'entrée supérieure ou égale à minuit
            ->andWhere('s.date_entree < :end') // Date d'entrée strictement inférieure à minuit du lendemain
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        // Exécute la requête et récupère les résultats
        $sejours = $qb->getQuery()->getResult();

        // Affiche la vue avec la liste filtrée des séjours
        return $this->render('infirmier/arrivee_patient.html.twig', [
            'sejours' => $sejours
        ]);
    }


    #[Route('/infirmier/sortie', name: 'app_sortie')]
    public function sortiePatient(EntityManagerInterface $em, SejourRepository $sejours): Response {
        $repository = $em->getRepository(Sejour::class);
        $sejours = $repository->findAll(); // Récupère tous les séjours
        return $this->render('infirmier/sortie_patient.html.twig',
         [
            'sejours' => $sejours
        ]);
    }
    #[Route('/infirmier/sortie/aujourdhui', name: 'app_sortie_aujourdhui')]
    public function sortiePatientAujourdhui(EntityManagerInterface $em): Response {
        // Récupère le repository de l'entité Sejour
        $repository = $em->getRepository(Sejour::class);

        // Définit le début de la journée (00:00:00)
        $start = (new \DateTimeImmutable('today'))->setTime(0, 0, 0);

        // Définit la fin de la journée (00:00:00 du lendemain)
        $end = $start->modify('+1 day');

        // Crée une requête pour sélectionner les séjours dont la date de sortie est aujourd'hui
        $qb = $repository->createQueryBuilder('s');
        $qb->where('s.date_sortie >= :start') // Date de sortie supérieure ou égale à minuit
            ->andWhere('s.date_sortie < :end') // Date de sortie strictement inférieure à minuit du lendemain
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        // Exécute la requête et récupère les résultats
        $sejours = $qb->getQuery()->getResult();

        // Affiche la vue avec la liste filtrée des séjours
        return $this->render('infirmier/sortie_patient.html.twig', [
            'sejours' => $sejours
        ]);
    }

    #[Route('/infirmier/patient/{id}', name: 'app_detail_patient')]
    public function detailPatient(PatientRepository $patientRepository, int $id): Response {
        $patient = $patientRepository->find($id);
        if (!$patient) {
            throw $this->createNotFoundException('Patient non trouvé.');
        }
        return $this->render('infirmier/detail_patient.html.twig', [
            'patient' => $patient
        ]);
    }

    #[Route('/infirmier/sejour/{id}', name: 'app_detail_sejour')]
    public function detailSejour(int $id, SejourRepository $repo, Request $request): Response {
        $sejour = $repo->find($id);
        if (!$sejour) { throw $this->createNotFoundException('Séjour non trouvé.'); }
        $backDate = $request->query->get('date');
        return $this->render('infirmier/detail_sejour.html.twig', ['sejour' => $sejour, 'backDate' => $backDate]);
    }

        /**
     * Valide l'entrée d'un patient pour un séjour donné (arrivee_etat).
     * @param int $id L'identifiant du séjour à valider
     * @param EntityManagerInterface $em Le gestionnaire d'entités Doctrine
     * @return Response
     */
    #[Route('/infirmier/validation_entree/{id}', name: 'app_validation_entree', methods: ['POST'])]
    public function validation_entree(int $id, EntityManagerInterface $em): Response
    {
        $sejour = $em->getRepository(\App\Entity\Sejour::class)->find($id);
        if (!$sejour) {
            throw $this->createNotFoundException('Séjour non trouvé.');
        }
        $sejour->setArriveeEtat(true);
        $em->flush();
        $this->addFlash('success', "Entrée du patient validée.");
        return $this->redirectToRoute('app_arrivee');
    }

    /**
     * Valide la sortie d'un patient pour un séjour donné (sortie_etat).
     * @param int $id L'identifiant du séjour à valider
     * @param EntityManagerInterface $em Le gestionnaire d'entités Doctrine
     * @return Response
     */
    #[Route('/infirmier/validation_sortie/{id}', name: 'app_validation_sortie', methods: ['POST'])]
    public function validation_sortie(int $id, EntityManagerInterface $em): Response
    {
        $sejour = $em->getRepository(\App\Entity\Sejour::class)->find($id);
        if (!$sejour) {
            throw $this->createNotFoundException('Séjour non trouvé.');
        }
        $sejour->setSortieEtat(true);
        $em->flush();
        $this->addFlash('success', "Sortie du patient validée.");
        return $this->redirectToRoute('app_sortie');
    }


    //Partie Consulation

    // Consultation des séjours des patients
    #[Route('/infirmier/consultation', name: 'app_consultation')]
    public function consultationSejours(): Response {
        return $this->render('infirmier/index_consultation.html.twig'); 
    }

    // Consultation des séjours des patients
    // Consultation des séjours des patients
    #[Route('/infirmier/consultation_date_donnee', name: 'app_consultation_sejour_date_donnee')]
    public function consultationSejoursDateDonnee(Request $request, EntityManagerInterface $em): Response {
        $dateInput = $request->query->get('date');
        $selectedDate = $dateInput ? \DateTimeImmutable::createFromFormat('Y-m-d', $dateInput) : new \DateTimeImmutable('today');

        if (!$selectedDate) {
            $selectedDate = new \DateTimeImmutable('today');
            $this->addFlash('error', 'Date invalide, utilisation de la date du jour.');
        }

        $start = $selectedDate->setTime(0, 0, 0);
        $end = $start->modify('+1 day');

        $qb = $em->getRepository(Sejour::class)->createQueryBuilder('s')
            ->leftJoin('s.patient', 'p')->addSelect('p')
            ->leftJoin('s.chambre', 'c')->addSelect('c')
            ->where('s.date_entree < :end')
            ->andWhere('s.date_sortie >= :start')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('s.date_entree', 'ASC');

        $sejours = $qb->getQuery()->getResult();

        return $this->render('infirmier/consultation_sejour_date_donnee.html.twig', [
            'selectedDate' => $selectedDate,
            'sejours' => $sejours,
        ]); 
    }


    #[Route('/infirmier/consultation_commencement', name: 'app_consultation_sejour_commencement')]
    public function consultationSejoursCommencement(Request $request, EntityManagerInterface $em): Response {
        $dateInput = $request->query->get('date');
        $selectedDate = $dateInput ? \DateTimeImmutable::createFromFormat('Y-m-d', $dateInput) : new \DateTimeImmutable('today');

        if (!$selectedDate) {
            $selectedDate = new \DateTimeImmutable('today');
            $this->addFlash('error', 'Date invalide, utilisation de la date du jour.');
        }

        $start = $selectedDate->setTime(0, 0, 0);
        $end = $start->modify('+1 day');

        $qb = $em->getRepository(Sejour::class)->createQueryBuilder('s')
            ->leftJoin('s.patient', 'p')->addSelect('p')
            ->leftJoin('s.chambre', 'c')->addSelect('c')
            ->where('s.date_entree >= :start')
            ->andWhere('s.date_entree < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('s.date_entree', 'ASC');

        $sejours = $qb->getQuery()->getResult();

        return $this->render('infirmier/consultation_sejour_commencement.html.twig', [
            'selectedDate' => $selectedDate,
            'sejours' => $sejours,
        ]);
    }

    #[Route('/infirmier/consultation_a_venir', name: 'app_consultation_sejour_a_venir')]
    public function consultationSejoursAVenir(Request $request, EntityManagerInterface $em): Response {
        $dateInput = $request->query->get('date');
        $referenceDate = $dateInput ? \DateTimeImmutable::createFromFormat('Y-m-d', $dateInput) : new \DateTimeImmutable('today');

        if (!$referenceDate) {
            $referenceDate = new \DateTimeImmutable('today');
            $this->addFlash('error', 'Date invalide, utilisation de la date du jour.');
        }

        $start = $referenceDate->setTime(0, 0, 0);

        $qb = $em->getRepository(Sejour::class)->createQueryBuilder('s')
            ->leftJoin('s.patient', 'p')->addSelect('p')
            ->leftJoin('s.chambre', 'c')->addSelect('c')
            ->where('s.date_entree >= :start')
            ->setParameter('start', $start)
            ->orderBy('s.date_entree', 'ASC');

        $sejours = $qb->getQuery()->getResult();

        return $this->render('infirmier/consultation_sejour_a_venir.html.twig', [
            'referenceDate' => $referenceDate,
            'sejours' => $sejours,
        ]);
    }

    
}



/*
=================================================================
RÉCAPITULATIF DÉTAILLÉ DES FONCTIONS DU CONTRÔLEUR INFIRMIER
=================================================================

1. index()
   Route : /infirmier/
   But : Afficher le menu principal de l'espace infirmier (page d'accueil après authentification)
   Fonctionnalités :
     - Point d'entrée de l'espace infirmier après connexion
     - Affiche deux grandes sections : Gestion des séjours et Consultation des séjours
     - Permet la navigation vers les différentes fonctionnalités disponibles pour le personnel infirmier
   Template : infirmier/index.html.twig
   Paramètres : Aucun
   Retour : Response avec le menu principal

2. gestionSejours()
   Route : /infirmier/sejours
   But : Afficher le menu de gestion des séjours
   Fonctionnalités :
     - Page intermédiaire entre le menu principal et les fonctionnalités de gestion
     - Permet d'accéder à la gestion des arrivées (validation des entrées)
     - Permet d'accéder à la gestion des sorties (validation des départs)
     - Centralise les actions de validation des mouvements de patients
   Template : infirmier/index_gestion.html.twig
   Paramètres : Aucun
   Retour : Response avec les liens vers arrivées et sorties

3. arriveePatient()
   Route : /infirmier/arrivee
   But : Afficher la liste complète de tous les séjours pour gérer les arrivées
   Fonctionnalités :
     - Récupère TOUS les séjours de la base de données (sans filtre de date)
     - Affiche un tableau avec les informations des séjours : patient, chambre, dates, statut
     - Permet de valider l'arrivée effective d'un patient (bouton de validation)
     - Affiche l'état de validation (arrivee_etat : validé ou non)
     - Inclut des boutons d'action : "Voir le séjour" et "Fiche patient"
   Template : infirmier/arrivee_patient.html.twig
   Paramètres : EntityManagerInterface, SejourRepository
   Retour : Response avec tous les séjours

4. arriveePatientAujourdhui()
   Route : /infirmier/arrivee/aujourdhui
   But : Afficher uniquement les séjours dont la date d'entrée est aujourd'hui
   Fonctionnalités :
     - Filtre les séjours avec une requête DQL (Query Builder)
     - Définit la plage horaire : de 00:00:00 aujourd'hui à 00:00:00 demain
     - Utilise les conditions WHERE : date_entree >= début ET date_entree < fin
     - Affiche uniquement les arrivées prévues pour la journée en cours
     - Permet une gestion ciblée des arrivées du jour (optimise le workflow quotidien)
     - Fonctionnalités identiques à arriveePatient() mais avec liste filtrée
   Template : infirmier/arrivee_patient.html.twig (réutilise le même template)
   Paramètres : EntityManagerInterface
   Retour : Response avec les séjours du jour uniquement

5. sortiePatient()
   Route : /infirmier/sortie
   But : Afficher la liste complète de tous les séjours pour gérer les sorties
   Fonctionnalités :
     - Récupère TOUS les séjours de la base de données (sans filtre de date)
     - Affiche un tableau avec les informations des séjours : patient, chambre, dates, statut
     - Permet de valider la sortie effective d'un patient (bouton de validation)
     - Affiche l'état de validation (sortie_etat : validé ou non)
     - Inclut des boutons d'action : "Voir le séjour" et "Fiche patient"
   Template : infirmier/sortie_patient.html.twig
   Paramètres : EntityManagerInterface, SejourRepository
   Retour : Response avec tous les séjours

6. sortiePatientAujourdhui()
   Route : /infirmier/sortie/aujourdhui
   But : Afficher uniquement les séjours dont la date de sortie est aujourd'hui
   Fonctionnalités :
     - Filtre les séjours avec une requête DQL (Query Builder)
     - Définit la plage horaire : de 00:00:00 aujourd'hui à 00:00:00 demain
     - Utilise les conditions WHERE : date_sortie >= début ET date_sortie < fin
     - Affiche uniquement les sorties prévues pour la journée en cours
     - Permet une gestion ciblée des sorties du jour (optimise le workflow quotidien)
     - Fonctionnalités identiques à sortiePatient() mais avec liste filtrée
   Template : infirmier/sortie_patient.html.twig (réutilise le même template)
   Paramètres : EntityManagerInterface
   Retour : Response avec les séjours du jour uniquement

7. detailPatient()
   Route : /infirmier/patient/{id}
   But : Afficher la fiche complète d'un patient spécifique
   Fonctionnalités :
     - Récupère un patient unique via son identifiant (id)
     - Affiche toutes les informations personnelles : nom, prénom, date de naissance, etc.
     - Affiche l'adresse complète du patient (avec localité associée)
     - Liste tous les séjours associés au patient (historique et séjours en cours)
     - Gère l'erreur 404 si le patient n'existe pas dans la base
     - Permet la navigation vers les détails de chaque séjour
   Template : infirmier/detail_patient.html.twig
   Paramètres : PatientRepository, int $id
   Retour : Response avec les données du patient ou exception 404

8. detailSejour()
   Route : /infirmier/sejour/{id}
   But : Afficher les informations détaillées d'un séjour spécifique
   Fonctionnalités :
     - Récupère un séjour unique via son identifiant (id)
     - Affiche toutes les informations : patient, chambre, dates, libellé, statut
     - Récupère le paramètre 'date' depuis l'URL (pour le bouton retour)
     - Permet la navigation de retour vers la page de consultation avec contexte
     - Gère l'erreur 404 si le séjour n'existe pas
     - Affiche les états de validation (arrivée et sortie)
   Template : infirmier/detail_sejour.html.twig
   Paramètres : int $id, SejourRepository, Request
   Retour : Response avec les données du séjour ou exception 404

9. validation_entree()
   Route : /infirmier/validation_entree/{id}
   Méthode HTTP : POST uniquement
   But : Valider l'arrivée effective d'un patient pour un séjour donné
   Fonctionnalités :
     - Recherche le séjour par son id
     - Met à jour le champ arrivee_etat à true (booléen)
     - Persiste la modification en base de données (flush)
     - Affiche un message flash de succès "Entrée du patient validée."
     - Redirige automatiquement vers la liste des arrivées
     - Gère l'erreur 404 si le séjour n'existe pas
     - Sécurisé : accepte uniquement les requêtes POST (protection CSRF)
   Template : Aucun (redirection)
   Paramètres : int $id, EntityManagerInterface
   Retour : Redirection vers app_arrivee

10. validation_sortie()
    Route : /infirmier/validation_sortie/{id}
    Méthode HTTP : POST uniquement
    But : Valider la sortie effective d'un patient pour un séjour donné
    Fonctionnalités :
      - Recherche le séjour par son id
      - Met à jour le champ sortie_etat à true (booléen)
      - Persiste la modification en base de données (flush)
      - Affiche un message flash de succès "Sortie du patient validée."
      - Redirige automatiquement vers la liste des sorties
      - Gère l'erreur 404 si le séjour n'existe pas
      - Sécurisé : accepte uniquement les requêtes POST (protection CSRF)
    Template : Aucun (redirection)
    Paramètres : int $id, EntityManagerInterface
    Retour : Redirection vers app_sortie

11. consultationSejours()
    Route : /infirmier/consultation
    But : Afficher le menu principal de consultation des séjours
    Fonctionnalités :
      - Page intermédiaire entre le menu principal et les différentes consultations
      - Propose trois types de consultations :
        * Consultation par date donnée (séjours effectifs à une date)
        * Consultation par date de commencement (séjours débutant à une date)
        * Consultation des séjours à venir (séjours futurs)
      - Permet la navigation vers chaque type de consultation
    Template : infirmier/index_consultation.html.twig
    Paramètres : Aucun
    Retour : Response avec les liens vers les consultations

12. consultationSejoursDateDonnee()
    Route : /infirmier/consultation_date_donnee
    But : Consulter les séjours effectifs (en cours) à une date donnée
    Fonctionnalités :
      - Récupère le paramètre 'date' depuis l'URL (formulaire GET)
      - Utilise la date du jour par défaut si aucune date n'est fournie
      - Filtre les séjours dont la plage [date_entree, date_sortie] contient la date sélectionnée
      - Utilise leftJoin pour charger patient et chambre (évite N+1 queries et gère les relations nulles)
      - Conditions : date_entree < fin_de_journée ET date_sortie >= début_de_journée
      - Tri par ordre chronologique (date_entree ASC)
      - Affiche un formulaire de sélection de date avec bouton "Valider"
      - Affiche un tableau avec toutes les informations (patient, chambre, dates, libellé, statut)
      - Inclut les boutons d'action : "Voir le séjour" et "Fiche patient"
      - Gestion des erreurs : affiche un message flash si la date est invalide
    Template : infirmier/consultation_sejour_date_donnee.html.twig
    Paramètres : Request, EntityManagerInterface
    Retour : Response avec la date sélectionnée et les séjours correspondants

13. consultationSejoursCommencement()
    Route : /infirmier/consultation_commencement
    But : Consulter les séjours commençant exactement à une date donnée
    Fonctionnalités :
      - Récupère le paramètre 'date' depuis l'URL (formulaire GET)
      - Utilise la date du jour par défaut si aucune date n'est fournie
      - Filtre les séjours dont date_entree correspond exactement à la date sélectionnée
      - Utilise leftJoin pour charger patient et chambre (évite N+1 queries et gère les relations nulles)
      - Conditions : date_entree >= début_de_journée ET date_entree < fin_de_journée
      - Tri par ordre chronologique (date_entree ASC)
      - Affiche un formulaire de sélection de date avec bouton "Valider"
      - Affiche un tableau avec toutes les informations (patient, chambre, dates, libellé, statut)
      - Inclut les boutons d'action : "Voir le séjour" et "Fiche patient"
      - Gestion des erreurs : affiche un message flash si la date est invalide
      - Différence avec consultationSejoursDateDonnee : filtre uniquement les débuts de séjour
    Template : infirmier/consultation_sejour_commencement.html.twig
    Paramètres : Request, EntityManagerInterface
    Retour : Response avec la date sélectionnée et les séjours débutant à cette date

14. consultationSejoursAVenir()
    Route : /infirmier/consultation_a_venir
    But : Consulter tous les séjours à venir (futurs) à partir d'une date de référence
    Fonctionnalités :
      - Récupère le paramètre 'date' depuis l'URL (formulaire GET)
      - Utilise la date du jour par défaut comme date de référence
      - Filtre les séjours dont date_entree >= date de référence (pas de limite supérieure)
      - Utilise leftJoin pour charger patient et chambre (évite N+1 queries et gère les relations nulles)
      - Condition : date_entree >= début_de_la_journée_de_référence
      - Tri par ordre chronologique (date_entree ASC) pour affichage cohérent
      - Affiche un formulaire avec :
        * Champ de sélection de date de référence
        * Bouton "Valider"
        * Bouton "Réinitialiser (aujourd'hui)" pour revenir à la date du jour
      - Affiche un tableau avec toutes les informations (patient, chambre, dates, libellé, statut)
      - Inclut les boutons d'action : "Voir le séjour" et "Fiche patient"
      - Affiche un compteur du nombre total de séjours à venir
      - Gestion des erreurs : affiche un message flash si la date est invalide
      - Différence avec consultationSejoursCommencement : affiche TOUS les séjours futurs, pas seulement ceux d'un jour précis
    Template : infirmier/consultation_sejour_a_venir.html.twig
    Paramètres : Request, EntityManagerInterface
    Retour : Response avec la date de référence et tous les séjours futurs


=================================================================
RÉCAPITULATIF DÉTAILLÉ DES FICHIERS TWIG DE L'ESPACE INFIRMIER
=================================================================

1. index.html.twig
   Chemin : templates/infirmier/index.html.twig
   But : Menu principal de l'espace infirmier (page d'accueil)
   Fonctionnalités :
     - Étend le template de base (base.html.twig)
     - Affiche un titre de bienvenue pour le personnel infirmier
     - Présente deux sections principales sous forme de cartes Bootstrap :
       * Section "Gestion des séjours" : lien vers la gestion des arrivées et sorties
       * Section "Consultation des séjours" : lien vers les différentes consultations
     - Utilise des icônes et des boutons pour une navigation intuitive
     - Responsive design avec Bootstrap
   Variables attendues : Aucune
   Liens de navigation : app_gestion, app_consultation

2. index_gestion.html.twig
   Chemin : templates/infirmier/index_gestion.html.twig
   But : Menu de gestion des séjours (sous-menu)
   Fonctionnalités :
     - Étend le template de base
     - Affiche un titre "Gestion des séjours"
     - Présente deux options principales :
       * Lien vers la gestion des arrivées (avec option "Aujourd'hui")
       * Lien vers la gestion des sorties (avec option "Aujourd'hui")
     - Permet de choisir entre voir tous les séjours ou uniquement ceux du jour
     - Navigation avec boutons stylisés Bootstrap
   Variables attendues : Aucune
   Liens de navigation : app_arrivee, app_arrivee_aujourdhui, app_sortie, app_sortie_aujourdhui

3. arrivee_patient.html.twig
   Chemin : templates/infirmier/arrivee_patient.html.twig
   But : Gérer et valider les arrivées des patients
   Fonctionnalités :
     - Étend le template de base
     - Affiche un tableau Bootstrap responsive avec tous les séjours
     - Colonnes du tableau :
       * Numéro de séjour
       * Nom et prénom du patient (avec ID)
       * Chambre et étage
       * Date d'entrée et date de sortie
       * Libellé du séjour
       * Statut du jour
       * État de validation de l'arrivée (badge vert si validé)
       * Actions (boutons)
     - Gestion des valeurs nulles (patient ou chambre non renseignés)
     - Boutons d'action alignés horizontalement :
       * "Valider l'arrivée" : formulaire POST vers validation_entree (si non encore validé)
       * "Voir le séjour" : lien vers la fiche détaillée du séjour
       * "Fiche patient" : lien vers la fiche du patient (si patient existe)
     - Affiche un message si la liste est vide
     - Utilise des badges de couleur pour l'état de validation
     - Protection CSRF sur les formulaires de validation
   Variables attendues : sejours (collection d'objets Sejour)
   Formulaires : validation_entree (POST)
   Liens de navigation : app_detail_sejour, app_detail_patient

4. sortie_patient.html.twig
   Chemin : templates/infirmier/sortie_patient.html.twig
   But : Gérer et valider les sorties des patients
   Fonctionnalités :
     - Étend le template de base
     - Structure identique à arrivee_patient.html.twig mais pour les sorties
     - Affiche un tableau Bootstrap responsive avec tous les séjours
     - Colonnes du tableau :
       * Numéro de séjour
       * Nom et prénom du patient (avec ID)
       * Chambre et étage
       * Date d'entrée et date de sortie
       * Libellé du séjour
       * Statut du jour
       * État de validation de la sortie (badge vert si validé)
       * Actions (boutons)
     - Gestion des valeurs nulles (patient ou chambre non renseignés)
     - Boutons d'action alignés horizontalement :
       * "Valider la sortie" : formulaire POST vers validation_sortie (si non encore validé)
       * "Voir le séjour" : lien vers la fiche détaillée du séjour
       * "Fiche patient" : lien vers la fiche du patient (si patient existe)
     - Affiche un message si la liste est vide
     - Utilise des badges de couleur pour l'état de validation
     - Protection CSRF sur les formulaires de validation
   Variables attendues : sejours (collection d'objets Sejour)
   Formulaires : validation_sortie (POST)
   Liens de navigation : app_detail_sejour, app_detail_patient

5. detail_patient.html.twig
   Chemin : templates/infirmier/detail_patient.html.twig
   But : Afficher la fiche complète d'un patient
   Fonctionnalités :
     - Étend le template de base
     - Affiche toutes les informations personnelles du patient :
       * Nom et prénom
       * Date de naissance (avec calcul de l'âge)
       * Adresse complète (rue, code postal, ville, pays via relation Localite)
       * Téléphone, email
       * Numéro de sécurité sociale
     - Section "Séjours du patient" :
       * Liste tous les séjours associés dans un tableau
       * Pour chaque séjour : numéro, dates, chambre, libellé, statut
       * Lien vers le détail de chaque séjour
     - Bouton "Retour" pour revenir à la page précédente
     - Affiche un message si le patient n'a aucun séjour
     - Formatage des dates en français (dd/mm/yyyy)
     - Carte Bootstrap pour une présentation claire
   Variables attendues : patient (objet Patient avec ses relations)
   Liens de navigation : app_detail_sejour, retour page précédente

6. detail_sejour.html.twig
   Chemin : templates/infirmier/detail_sejour.html.twig
   But : Afficher les informations détaillées d'un séjour
   Fonctionnalités :
     - Étend le template de base
     - Affiche toutes les informations du séjour :
       * Numéro du séjour
       * Date d'entrée et date de sortie
       * Libellé du séjour
       * Statut du jour (planning, effectif, terminé)
       * État de validation de l'arrivée (badge)
       * État de validation de la sortie (badge)
     - Section "Patient associé" :
       * Nom, prénom, date de naissance, adresse
       * Lien vers la fiche complète du patient
       * Affiche "Non renseigné" si pas de patient
     - Section "Chambre" :
       * Numéro de chambre et étage
       * Affiche "Non renseignée" si pas de chambre
     - Bouton "Retour" contextuel :
       * Si paramètre 'backDate' présent : retour vers la consultation avec la date
       * Sinon : retour simple à la page précédente
     - Formatage des dates en français
     - Utilise des badges colorés pour les états
     - Carte Bootstrap pour organisation visuelle
   Variables attendues : sejour (objet Sejour), backDate (string optionnel)
   Liens de navigation : app_detail_patient, app_consultation_sejour_date_donnee (avec date)

7. index_consultation.html.twig
   Chemin : templates/infirmier/index_consultation.html.twig
   But : Menu principal de consultation des séjours
   Fonctionnalités :
     - Étend le template de base
     - Affiche un titre "Consultation des séjours"
     - Présente trois types de consultations sous forme de cartes :
       * "Consultation par date donnée" : voir les séjours effectifs à une date
       * "Consultation par date de commencement" : voir les séjours débutant à une date
       * "Consultation des séjours à venir" : voir tous les séjours futurs
     - Chaque carte inclut :
       * Un titre
       * Une description de la fonctionnalité
       * Un bouton de navigation
     - Design responsive avec Bootstrap
     - Icônes pour différencier les types de consultation
   Variables attendues : Aucune
   Liens de navigation : app_consultation_sejour_date_donnee, app_consultation_sejour_commencement, app_consultation_sejour_a_venir

8. consultation_sejour_date_donnee.html.twig
   Chemin : templates/infirmier/consultation_sejour_date_donnee.html.twig
   But : Consultation des séjours effectifs à une date donnée
   Fonctionnalités :
     - Étend le template de base
     - Inclut la feuille de style patient.css
     - Titre dynamique affichant la date sélectionnée (format dd/mm/yyyy)
     - Formulaire de sélection de date :
       * Champ input type="date"
       * Valeur pré-remplie avec selectedDate
       * Bouton "Valider" pour soumettre
       * Méthode GET pour navigation facile
     - Affichage des messages flash (erreurs)
     - Tableau Bootstrap responsive avec les séjours :
       * Numéro de séjour
       * Patient (nom, prénom, ID) avec gestion null
       * Localisation (chambre, étage) avec gestion null
       * Date d'entrée et date de sortie
       * Libellé
       * Statut du jour
       * Actions : "Voir le séjour" et "Fiche patient" (si existe)
     - Message "Aucun séjour effectif à cette date" si liste vide
     - Passage du paramètre 'date' dans les liens pour navigation contextuelle
     - Formatage des dates en français
   Variables attendues : selectedDate (DateTimeImmutable), sejours (collection)
   Formulaires : sélection de date (GET)
   Liens de navigation : app_detail_sejour (avec date), app_detail_patient

9. consultation_sejour_commencement.html.twig
   Chemin : templates/infirmier/consultation_sejour_commencement.html.twig
   But : Consultation des séjours commençant à une date donnée
   Fonctionnalités :
     - Étend le template de base
     - Inclut la feuille de style patient.css
     - Titre dynamique "Séjours commençant le [date]" (format dd/mm/yyyy)
     - Formulaire de sélection de date de commencement :
       * Champ input type="date" avec label "Date de commencement"
       * Valeur pré-remplie avec selectedDate
       * Bouton "Valider" pour soumettre
       * Méthode GET
     - Affichage des messages flash (erreurs)
     - Tableau Bootstrap responsive avec les séjours :
       * Numéro de séjour
       * Patient (nom, prénom, ID) avec gestion null
       * Localisation (chambre, étage) avec gestion null
       * Date d'entrée et date de sortie
       * Libellé
       * Statut du jour
       * Actions : "Voir le séjour" et "Fiche patient" (si existe)
     - Message "Aucun séjour commençant à cette date" si liste vide
     - Passage du paramètre 'date' dans les liens pour retour contextuel
     - Formatage des dates en français
     - Structure similaire à consultation_sejour_date_donnee mais filtre différent
   Variables attendues : selectedDate (DateTimeImmutable), sejours (collection)
   Formulaires : sélection de date (GET)
   Liens de navigation : app_detail_sejour (avec date), app_detail_patient

10. consultation_sejour_a_venir.html.twig
    Chemin : templates/infirmier/consultation_sejour_a_venir.html.twig
    But : Consultation de tous les séjours à venir (futurs)
    Fonctionnalités :
      - Étend le template de base
      - Inclut la feuille de style patient.css
      - Titre dynamique "Séjours à venir (à partir du [date])" (format dd/mm/yyyy)
      - Formulaire de sélection avec deux boutons :
        * Champ input type="date" avec label "Date de référence"
        * Valeur pré-remplie avec referenceDate
        * Bouton "Valider" pour soumettre la nouvelle date
        * Bouton "Réinitialiser (aujourd'hui)" pour revenir à la date du jour
        * Méthode GET
      - Affichage des messages flash (erreurs)
      - Tableau Bootstrap responsive avec tous les séjours futurs :
        * Numéro de séjour
        * Patient (nom, prénom, ID) avec gestion null
        * Localisation (chambre, étage) avec gestion null
        * Date d'entrée et date de sortie
        * Libellé
        * Statut du jour
        * Actions : "Voir le séjour" et "Fiche patient" (si existe)
      - Message "Aucun séjour à venir à partir de cette date" si liste vide
      - Affichage d'un compteur total (alert info) :
        * "X séjour(s) à venir affiché(s) par ordre chronologique"
        * Visible seulement si sejours|length > 0
      - Passage de la date d'entrée du séjour dans le lien "Voir le séjour"
      - Formatage des dates en français
      - Affichage par ordre chronologique (date_entree ASC)
    Variables attendues : referenceDate (DateTimeImmutable), sejours (collection)
    Formulaires : sélection de date de référence (GET)
    Liens de navigation : app_detail_sejour (avec date d'entrée), app_detail_patient, app_consultation_sejour_a_venir (réinitialisation)

*/