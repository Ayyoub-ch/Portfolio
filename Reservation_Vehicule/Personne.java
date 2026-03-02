public class Personne {
    // Attributs
    private String matricule;
    private String  nom;
    private String telephone;
    private Service unService;

    public Personne(String matricule, String nom, String telephone) {
        this.matricule = matricule;
        this.nom = nom;
        this.telephone = telephone;
    }
    // getters et setters

    @Override
    public String toString() {
        return "Personne{" + "matricule=" + matricule + ", nom=" + nom + ", telephone=" + telephone + '}';
    }

    public String getMatricule() {
        return matricule;
    }

    public void setMatricule(String matricule) {
        this.matricule = matricule;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getTelephone() {
        return telephone;
    }

    public void setTelephone(String telephone) {
        this.telephone = telephone;
    } 
    
    
}

