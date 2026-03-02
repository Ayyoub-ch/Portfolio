public class Service {
    // Attributs
    private int numero;
    private String libelle;

    public Service(int numero, String libelle) {
        this.numero = numero;
        this.libelle = libelle;
    }
    // Getters et Setters

    public int getNumero() {
        return numero;
    }

    public String getLibelle() {
        return libelle;
    }

    public void setNumero(int numero) {
        this.numero = numero;
    }

    public void setLibelle(String libelle) {
        this.libelle = libelle;
    }

    @Override
    public String toString() {
        return "Service{" +
                "numero=" + numero +
                ", libelle='" + libelle + '\'' +
                '}';
    }
}
