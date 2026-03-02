# Explication de la fonction `verifierConnexion`

## Rôle général

Cette fonction sert à **vérifier si un utilisateur existe dans la base
de données** à partir de : - son **matricule** - son **mot de passe**

Elle retourne : - `true` → si les identifiants sont corrects\
- `false` → sinon

------------------------------------------------------------------------

## Déroulement étape par étape

### 1. Préparation de la requête SQL

On prépare une requête pour chercher une personne avec un matricule et
un mot de passe donnés.

### 2. Remplacement des paramètres

Les `?` sont remplacés par les valeurs saisies.

### 3. Exécution de la requête

La requête est envoyée à PostgreSQL.

### 4. Vérification du résultat

-   Si une ligne existe → utilisateur trouvé → connexion valide\
-   Sinon → identifiants incorrects

### 5. Affichage + retour

-   Si trouvé → message de bienvenue + `true`\
-   Sinon → message d'erreur + `false`

### 6. Gestion d'erreur

Si un problème survient, l'erreur est affichée et la fonction retourne
`false`.

------------------------------------------------------------------------

# Erreurs possibles

## 1. Connexion null

Si `conn` n'est pas initialisée → `NullPointerException`.

------------------------------------------------------------------------

## 2. Fuite de ressources JDBC

`PreparedStatement` et `ResultSet` ne sont jamais fermés → risque de
saturation.

------------------------------------------------------------------------

## 3. Mauvaise gestion des exceptions

`catch (Exception e)` est trop général et masque les détails.

------------------------------------------------------------------------

## 4. Mot de passe stocké en clair (grave)

Comparer directement les mots de passe signifie qu'ils sont stockés en
clair dans la base.

------------------------------------------------------------------------

## 5. Mélange logique / affichage

Une méthode d'accès aux données ne devrait pas afficher des messages.

------------------------------------------------------------------------

## 6. Aucun contrôle des entrées

Si matricule ou mot de passe sont vides ou null → comportements
inattendus.

------------------------------------------------------------------------

## 7. Requête non optimisée

On récupère `nom` et `prenom` alors qu'on veut juste vérifier
l'existence.

------------------------------------------------------------------------

## 8. Sensibilité à la casse

Un matricule tapé en minuscule peut ne pas correspondre à celui stocké.

------------------------------------------------------------------------

## 9. Debug difficile

En cas d'erreur JDBC, seule une partie du message est affichée.

------------------------------------------------------------------------

# Résumé

La fonction : - vérifie les identifiants dans la base - affiche un
message - retourne true/false

Principales corrections à faire : 1. Vérifier la connexion 2. Fermer les
ressources JDBC 3. Améliorer la gestion des exceptions 4. Hasher les
mots de passe 5. Séparer affichage et logique métier
