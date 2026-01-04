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
    #[Route('/infirmier/consultation_date_donnee', name: 'app_consultation_sejour_date_donnee')]
    public function consultationSejoursDateDonnee(): Response {
        return $this->render('infirmier/consultation_sejour_date_donnee.html.twig'); 
    }


    #[Route('/infirmier/consultation_commencement', name: 'app_consultation_sejour_commencement')]
    public function consultationSejoursCommencement(): Response {
        return $this->render('infirmier/consultation_sejour_commencement.html.twig');
    }

    #[Route('/infirmier/consultation_a_venir', name: 'app_consultation_sejour_a_venir')]
    public function consultationSejoursAVenir(): Response {
        return $this->render('infirmier/consultation_sejour_a_venir.html.twig');
    }

}



/*
===========================
Récapitulatif des fonctions du contrôleur Infirmier
===========================

index - but : Afficher le menu principal de l'espace infirmier.
explications : Affiche les liens vers la gestion et la consultation des séjours (index.html.twig).

gestionSejours - but : Afficher le menu de gestion des séjours.
explications : Permet d'accéder à la gestion des arrivées et des sorties (index_gestion.html.twig).

arriveePatient - but : Afficher la liste des séjours pour gérer les arrivées.
explications : Récupère tous les séjours et les passe à la vue pour valider l'arrivée des patients (arrivee_patient.html.twig).

arriveePatientAujourdhui - but : Afficher la liste des séjours dont la date d'entrée est aujourd'hui.
explications : Récupère les séjours dont la date d'entrée est comprise entre minuit et minuit+1 jour, puis les affiche dans la vue arrivee_patient.html.twig.

sortiePatient - but : Afficher la liste des séjours pour gérer les sorties.
explications : Récupère tous les séjours et les passe à la vue pour valider la sortie des patients (sortie_patient.html.twig).

sortiePatientAujourdhui - but : Afficher la liste des séjours dont la date de sortie est aujourd'hui.
explications : Récupère les séjours dont la date de sortie est comprise entre minuit et minuit+1 jour, puis les affiche dans la vue sortie_patient.html.twig.

detailPatient - but : Afficher le détail d'un patient.
explications : Récupère un patient par son id et affiche ses informations détaillées (detail_patient.html.twig).

validation_entree - but : Valider l'arrivée d'un patient pour un séjour.
explications : Met à jour le champ arrivee_etat du séjour à true, affiche un message de succès et redirige vers la liste des arrivées.

validation_sortie - but : Valider la sortie d'un patient pour un séjour.
explications : Met à jour le champ sortie_etat du séjour à true, affiche un message de succès et redirige vers la liste des sorties.

consultationSejours - but : Afficher le menu de consultation des séjours.
explications : Permet d'accéder à différentes consultations de séjours (index_consultation.html.twig).

consultationSejoursDateDonnee - but : Consulter les séjours à une date donnée.
explications : Affiche une page pour consulter les séjours à une date précise (consultation_date_donnee.twig).

consultationSejoursCommencement - but : Consulter les séjours commençant à une date donnée.
explications : Affiche une page pour consulter les séjours commençant à une date précise (consultation_sejour_commencement.html.twig).

consultationSejoursAVenir - but : Consulter les séjours à venir.
explications : Affiche une page pour consulter les séjours à venir (consultation_sejour_a_venir.html.twig).


===========================
Récapitulatif des principaux fichiers Twig
===========================

index.html.twig - but : Menu principal de l'espace infirmier.
explication : Propose les accès à la gestion et à la consultation des séjours.

index_gestion.html.twig - but : Menu de gestion des séjours.
explication : Permet d'accéder à la gestion des arrivées et des sorties.

arrivee_patient.html.twig - but : Gérer les arrivées des patients.
explication : Affiche la liste des séjours, permet de valider l'arrivée d'un patient (bouton/état).

sortie_patient.html.twig - but : Gérer les sorties des patients.
explication : Affiche la liste des séjours, permet de valider la sortie d'un patient (bouton/état).

detail_patient.html.twig - but : Afficher le détail d'un patient.
explication : Affiche toutes les informations d'un patient sélectionné.

index_consultation.html.twig - but : Menu de consultation des séjours.
explication : Propose différents types de consultation (par date, à venir, etc.).

consultation_date_donnee.twig - but : Consultation des séjours à une date donnée.
explication : (Page à compléter) Affiche les séjours correspondant à une date précise.

consultation_sejour_commencement.html.twig - but : Consultation des séjours commençant à une date donnée.
explication : (Page à compléter) Affiche les séjours dont le début correspond à une date précise.

consultation_sejour_a_venir.html.twig - but : Consultation des séjours à venir.
explication : (Page à compléter) Affiche les séjours prévus dans le futur.

*/