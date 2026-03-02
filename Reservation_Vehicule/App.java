import java.util.Scanner;

public class App {
    public static void main(String[] args) {
        // Initialisation
        Scanner s = new Scanner(System.in);
        Passerelle db = new Passerelle();
        Menu menu = new Menu();

        boolean connecte = menu.menuConnexion(s, db);

        if (connecte) {
            menu.afficherMenu(s, db);
        }

        // Fermeture des ressources
        s.close();
        db.closeConnection();
    }
}
