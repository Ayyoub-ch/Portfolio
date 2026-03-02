import java.util.Scanner;
import java.time.LocalDate;

public class Menu {

    public void afficherBienvenue() {
        System.out.println("===== BIENVENUE SUR LE SYSTEME DE RESERVATION DU CENTRE HOSPITALIER =====");
    }

    public void afficherMenu(Scanner scanner, Passerelle db) {
        boolean continuer = true;

        while (continuer) {
            clearConsole();
            afficherBienvenue();
            System.out.println("\n===== MENU PRINCIPAL =====");
            System.out.println("1. Faire une réservation");
            System.out.println("2. Vérifier la disponibilité d'un véhicule");
            System.out.println("3. Modifier une réservation");
            System.out.println("0. Quitter");

            System.out.print("Veuillez choisir une option : ");

            int choix = scanner.nextInt();
            scanner.nextLine();

            switch (choix) {
                case 1:
                    menuReservation(scanner);
                    break;
                case 2:
                    menuVerificationDisponibilite(scanner, db);
                    break;
                case 3:
                    menuModification(scanner, db);
                    break;
                case 0:
                    System.out.println("Au revoir !");
                    continuer = false;
                    break;
                default:
                    System.out.println("Option invalide. Veuillez réessayer.");
                    break;
            }
        }
    }

    public boolean menuConnexion(Scanner s, Passerelle db) {
        clearConsole();
        afficherBienvenue();

        System.out.println("===== CONNEXION =====");

        boolean connecte = false;

        while (!connecte) {
            System.out.print("Entrez votre matricule (par exemple : 1001) : ");
            int matricule = s.nextInt();
            s.nextLine();

            System.out.print("Entrez votre mot de passe : ");
            String mdp = s.nextLine();

            connecte = db.verifierConnexion(matricule, mdp);

            if (!connecte) {
                System.out.println("Veuillez réessayer.\n");
            }
        }

        return connecte;
    }

    public void menuReservation(Scanner s) {
        clearConsole();
        // Réservation des Véhicules
        System.out.println("\n===== RESERVATION =====");
        System.out.print("Combien de vehicules souhaitez-vous reserver ? ");
        System.out.println(); // ligne vide pour aérer
        // Ajout des véhicules
        // Avec type, heure, priorité etc ....

        // Simulation du processus de réservation
        // ... code de réservation ...

        System.out.println("\nReservation terminee !");
        System.out.println("\nAppuyez sur Entree pour revenir au menu principal...");
        s.nextLine();
    }

    public void menuVerificationDisponibilite(Scanner s, Passerelle db) {
        // Vérification de la disponibilité du véhicule
        System.out.println("\n===== VERIFICATION DE DISPONIBILITE =====");
        // Fonction de vérif de la base de données
        // Afin de vérifier si le véhicule est présent et disponible ou bien pris

        // Demande des informations au utilisateur
        System.out.print("Entrez la marque du véhicule : ");
        String marque = s.nextLine();

        System.out.print("Entrez le modèle du véhicule : ");
        String modele = s.nextLine();

        System.out.print("Entrez le numéro du type : ");
        int numType = s.nextInt();
        s.nextLine(); // consomme le retour à la ligne

        System.out.print("Entrez l'immatriculation du véhicule : ");
        String immat = s.nextLine();

        // Création de l'objet Type à partir du numéro saisi
        Type unType = db.recupererTypeParNumero(numType);

        if (unType == null) {
            System.out.println("\nERREUR : le type de véhicule saisi n'existe pas.");
            System.out.println("Appuyez sur Entrée pour revenir au menu principal...");
            s.nextLine();
            return; // quitte la méthode
        }

        // Appel de la fonction de vérification de réservation
        boolean existe = db.verifierReservation(marque, modele, unType, immat);

        // Si le véhicule est dispo
        if (!existe) {
            System.out.println("Ce vehicule " + modele + " est disponible.");

            System.out.println("\nVotre reservation a ete validee pour les vehicules suivants :");
            // TO STRING des véhicules les affichant en réservation
            // Avec le nom, le prénom et les véhicules pour telle heure
        } else {
            System.out.println("Desole ce vehicule " + modele + " n est pas disponible.");
            // Autrement

            // Refaire la demande au besoin
            System.out.println("Aucun vehicule disponible. Merci de reessayer plus tard.");
        }

        System.out.println("\nVerification terminee !");
        System.out.println("\nAppuyez sur Entree pour revenir au menu principal...");
        s.nextLine();
    }

    public void menuModification(Scanner s, Passerelle db) {
        System.out.println("\n===== MODIFIER UNE RESERVATION =====");

        System.out.print("Numéro de la réservation : ");
        int numero = s.nextInt();
        s.nextLine();

        System.out.print("Date de la réservation (AAAA-MM-JJ) : ");
        LocalDate datereserv = LocalDate.parse(s.nextLine());

        if (!db.reservationExiste(numero, datereserv)) {
            System.out.println("Aucune réservation trouvée avec ce numéro et cette date !");
        } else {

            System.out.println("✅ Réservation trouvée. Saisissez les champs à modifier :");
            System.out.print("Nouvelle date de début (AAAA-MM-JJ) : ");
            LocalDate dateDebut = LocalDate.parse(s.nextLine());

            System.out.print("Matricule du personnel : ");
            String matricule = s.nextLine();

            System.out.print("Numéro du type : ");
            int noType = s.nextInt();
            s.nextLine();

            System.out.print("Immatriculation du véhicule : ");
            String immat = s.nextLine();

            System.out.print("Durée (jours) : ");
            int duree = s.nextInt();
            s.nextLine();

            System.out.print("Date retour effectif (AAAA-MM-JJ ou vide) : ");
            String dateRetourStr = s.nextLine();
            LocalDate dateRetourEffectif = dateRetourStr.isBlank() ? null : LocalDate.parse(dateRetourStr);

            System.out.print("État de la réservation : ");
            String etat = s.nextLine();

            // Appel à la passerelle
            db.modifierReservation(numero, datereserv, dateDebut, matricule, noType, immat, duree, dateRetourEffectif,
                    etat);
        }
    }

    public void menuResumeFinal() {
        // Message Final
        System.out.println("\n===== RESUME FINAL =====");
        System.out.println("Vehicules reserves :");
        // Affichage du nombre de Véhicules via To STRING

        System.out.println("\nMerci d avoir utilise notre service de reservation !");
        System.out.println(" Au revoir !");
    }

    public static void clearConsole() {
        System.out.print("\033[2J\033[H");
        System.out.flush();
    }

}
