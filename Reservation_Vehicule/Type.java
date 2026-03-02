public class Type {
    //Attributs
    private int numero;
    private String libelle;

    public Type(int numero, String libelle) {
        this.numero = numero;
        this.libelle = libelle;
    }

    public int getNumero() {
        return numero;
    }

    public void setNumero(int numero) {
        this.numero = numero;
    }

    public String getLibelle() {
        return libelle;
    }

    public void setLibelle(String libelle) {
        this.libelle = libelle;
    }

    @Override
    public String toString() {
        return "Type{" + "numero=" + numero + ", libelle=" + libelle + '}';
    }
    
    
    
}

