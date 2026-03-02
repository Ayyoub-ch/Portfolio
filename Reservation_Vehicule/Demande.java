public class Demande {
    // Attributs
    private String datereserv;
    private int numero;
    private String datedebyt;
    private Personne personne;
    private String notype;
    private Vehicule vehicule;
    private int duree;
    private String dateretoureffectif;
    private String etat;

    public Demande(String datereserv, int numero, String datedebyt, Personne personne, String notype, Vehicule vehicule,
            int duree, String dateretoureffectif, String etat) {
        this.datereserv = datereserv;
        this.numero = numero;
        this.datedebyt = datedebyt;
        this.personne = personne;
        this.notype = notype;
        this.vehicule = vehicule;
        this.duree = duree;
        this.dateretoureffectif = dateretoureffectif;
        this.etat = etat;
    }
// Getters et setters
    public String getDatereserv() {
        return datereserv;
    }

    public void setDatereserv(String datereserv) {
        this.datereserv = datereserv;
    }

    public int getNumero() {
        return numero;
    }

    public void setNumero(int numero) {
        this.numero = numero;
    }

    public String getDatedebyt() {
        return datedebyt;
    }

    public void setDatedebyt(String datedebyt) {
        this.datedebyt = datedebyt;
    }

    public Personne getPersonne() {
        return personne;
    }

    public void setPersonne(Personne personne) {
        this.personne = personne;
    }

    public String getNotype() {
        return notype;
    }

    public void setNotype(String notype) {
        this.notype = notype;
    }

    public Vehicule getVehicule() {
        return vehicule;
    }

    public void setVehicule(Vehicule vehicule) {
        this.vehicule = vehicule;
    }

    public int getDuree() {
        return duree;
    }

    public void setDuree(int duree) {
        this.duree = duree;
    }

    public String getDateretoureffectif() {
        return dateretoureffectif;
    }

    public void setDateretoureffectif(String dateretoureffectif) {
        this.dateretoureffectif = dateretoureffectif;
    }

    public String getEtat() {
        return etat;
    }

    public void setEtat(String etat) {
        this.etat = etat;
    }

    @Override
    public String toString() {
        return "Demande [datereserv=" + datereserv + ", numero=" + numero + ", datedebyt=" + datedebyt + ", personne="
                + personne + ", notype=" + notype + ", vehicule=" + vehicule + ", duree=" + duree
                + ", dateretoureffectif="
                + dateretoureffectif + ", etat=" + etat + "]";
    }

}
