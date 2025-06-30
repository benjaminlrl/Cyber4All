<?php
namespace lib;
include_once('Connexion.php');

use PDO;
use PDOException;
use lib\Connexion;

/**
 * Cette classe représente la gestion des utilisateurs dans la table utilisateurs
 */
class Utilisateur_CRUD
{
    private PDO $db;

    /**
     * Initialise une nouvelle instance de la classe Utilisateur_CRUD
     * @param PDO $db
     */
    public function __construct(PDO $connexion)
    {
        $this->db = $connexion;
    }

    public function deconnexion():void
    {
        $this->db = null;
    }

    /**
     * creerUtilisateur permet de créer un nouvel utilisateur.
     * Il dispose d'un pseudo, d'un role "contributeur" ou "admin",
     * d'un mot de passe obligatoirement,
     * d'un email optionnel nullable.
     * Retourne vraie si bien créer
     * @param string $pseudo
     * @param string $role
     * @param string $mdp
     * @param string|null $email
 * @return bool
     */
    public function creerUtilisateur(string $pseudo, string $role, string $mdp, ?string $email=null):bool{
        //traitements des données
        $pseudo = htmlspecialchars(trim($pseudo));
        $role = htmlspecialchars(trim($role));
        $mdp = trim($mdp);
        //Une majuscule minimum, un chiffre, un symbole parmi !*%$£\[\]+\/\\\.&
        //entre 13 et 255 charactères
        $pattern = '/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\?!*@=%$£\[\]+\/\\\.&]).{13,255}$/';
        $mdp = preg_match($pattern, $mdp) === 1 ?
            $mdp = password_hash($mdp,MDP_HASH) :
            null; //peut devenir null si ne contient pas les règles de sécurités
        if ($mdp === null || ($role !== "contributeur" && $role !== "admin")) {
            return false;
        }
        if($email!== null){
            $email = $this->valideEmail($email); //peut devenir null si ne contient pas les règles de sécurités
        }
        $req_insert=$this->db->prepare("INSERT INTO utilisateurs(pseudo, email, mot_de_passe, role)
                                        VALUES(:pseudo, :email, :mdp, :role)");
        var_dump($req_insert);
        $req_insert->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $req_insert->bindParam(":email", $email, PDO::PARAM_STR);
        $req_insert->bindParam(":mdp", $mdp, PDO::PARAM_STR);
        $req_insert->bindParam(":role", $role, PDO::PARAM_STR);

        try {
            $req_insert->execute();
            $retour = true;
        }
        catch(PDOException $e) {
            if($mdp === null){
                error_log("Erreur création utilisateur: 
                le mot de passe ne valide pas les conditions de sécurités" . $e->getMessage());
            }else{
                $e->getMessage();
            }
            $retour = false;
        }
        return $retour;
    }

    /**
     * valideEmail permet de vérifier si l'adresse email fourni est valide
     * @param string $email
     * @return string|null Retourne un email ou null
     */
    public function valideEmail(string $email): ?string
    {
        $email = trim($email);
        $isValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;

        return $isValid ? $email : null;
    }

    /**
     * adresseEmailExiste permet de vérifier si une adresse email
     * existe déja dans la table utilisateurs
     * Retourne vraie si elle existe
     * @param string $email
     * @return bool|null Retourne vraie si l'email existe déjà
     */
    public function adresseEmailExiste(string $email): ?bool{
        $email = trim($email);
        $email = $this->valideEmail($email);
        $retour = false;
        if($email !== null){

            $req_select=$this->db->prepare("SELECT email FROM utilisateurs 
                                                    WHERE email = :email");
            $req_select->bindParam(":email", $email, PDO::PARAM_STR);

            try{
                $req_select->execute();
                $results = $req_select->fetchAll(PDO::FETCH_ASSOC);
                if ($results){
                    $retour = true;
                }
            }catch (PDOException $e){
                error_log("adresseEmailExiste : ".$e->getMessage());
                $retour = false;
            }
        }
        return $retour;
    }

    /**
     * pseudoExiste permet de vérifier si un pseudo
     * existe déja dans la table utilisateurs
     * @param string $pseudo
     * @return bool Retourne vraie si le pseudo existe déjà.
     */
    public function pseudoExiste(string $pseudo): bool{
        $pseudo = htmlspecialchars(trim($pseudo));
        $retour = false;
        $req_select=$this->db->prepare("SELECT pseudo FROM utilisateurs 
                                                    WHERE pseudo = :pseudo");
        $req_select->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);

        try{
            $req_select->execute();
            $results = $req_select->fetchAll(PDO::FETCH_ASSOC);
            if ($results){
                $retour = true;
            }
        }catch (PDOException $e){
            error_log("adresseEmailExiste : ".$e->getMessage());
            $retour = false;
        }
        return $retour;
    }



    /**
     * deleteUtilisateur permet de supprimer un utilisateur
     * de la table Utilisateur à partir de son id.
     * Retourne vrai si bien supprimé.
     * @param \lib\Utilisateur $unUtilisateur
     * @return bool Retourne vraie si la suppression a réussi.
     */
    public function deleteUtilisateur(Utilisateur $unUtilisateur): bool
    {
        $req_delete = $this->db->prepare("DELETE FROM utilisateurs
                                    WHERE id = :id");
        $req_delete->bindValue(':id', $unUtilisateur->getId(),PDO::PARAM_INT);
        try {
            $req_delete->execute();
            $retour = true;
        }
        catch(PDOException $e) {
            $retour = false;
        }
        return $retour;
    }

    /**
     * updateUtilisateur permet de modifier un Utilisateur de la table utilisateur.
     * Elle dispose obligatoirement d'un utilisateur,
     * d'un pseudo, d'un role, d'un mdp,
     * d'un email optionnel et nullable.
     * Retourne vrai si bien modifié.
     * @param Utilisateur $unUtilisateur
     * @param string $pseudo
     * @param string $role
     * @param ?string $email
     * @return bool Retourne vraie si la modification a réussi.
     */
    public function updateUtilisateur(Utilisateur $unUtilisateur, string $pseudo,
                                      string $role,  ?string $email=null): bool
    {
        $pseudo = htmlspecialchars(trim($pseudo));
        $role = htmlspecialchars(trim($role));
        //Une majuscule minimum, un chiffre, un symbole parmi !*%$£\[\]+\/\\\.&
        //entre 13 et 255 charactères
        $pattern = '/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\?!*@=%$£\[\]+\/\\\.&]).{13,255}$/';
        if($role !== "contributeur" && $role !== "admin"){
            return false;
        }
        if($email !== null){
            $email = $this->valideEmail($email); //peut devenir null si ne respecte pas les règles de sécurités
        }

        $req_update = $this->db->prepare("UPDATE utilisateurs
                                SET pseudo = :pseudo, 
                                    role = :role,
                                    email = :email
                                WHERE id = :id");

        $req_update->bindValue(':id', $unUtilisateur->getId() ,PDO::PARAM_INT);
        $req_update->bindValue(':pseudo', $pseudo ,PDO::PARAM_STR);
        $req_update->bindValue(':role', $role ,PDO::PARAM_STR);
        if($email == null){
            $req_update->bindValue(':email', $email ,PDO::PARAM_NULL);
        }else{
            $req_update->bindValue(':email', $email ,PDO::PARAM_STR);
        }

        try {
            $req_update->execute();
            $retour = true;
        }
        catch(PDOException $e) {
            $e->getMessage();
            $retour = false;
        }
        return $retour;
    }

    /**
     * recupUtilisateurParPseudo permet de récupérer un
     * Utilisateur à partir de son pseudo de la table utilisateurs.
     * Retourne un utilisateur
     * @param string $pseudo
     * @return \lib\Utilisateur|null Retourne un utilisateur ou null
     */
    public function recupUtilisateurParPseudo(string $pseudo): ?Utilisateur
    {
        $req_select = $this->db->prepare("SELECT * FROM utilisateurs 
                                            WHERE pseudo= :pseudo");
        $req_select->bindValue(':pseudo',$pseudo, PDO::PARAM_STR);

        try {
            $req_select->execute();
            $result=$req_select->fetch(PDO::FETCH_OBJ);
            if($result)
            {
                $Utilisateur = new Utilisateur(
                    strval($result->pseudo),
                    strval($result->mot_de_passe),
                    strval($result->role),
                    $result->email,
                    intval($result->id)
                );
            }else{
                $Utilisateur = null;
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de recupUtilisateurparId : " . $e->getMessage());
            $Utilisateur = null;
        }
        return $Utilisateur;
    }

    /**
     * recupUtilisateurParID permet de récupérer un
     * Utilisateur à partir de son id de la table utilisateurs.
     * Retourne un utilisateur
     * @param int $id
     * @return \lib\Utilisateur|null Retourne un utilisateur ou null
     */
    public function recupUtilisateurParID(int $id): ?Utilisateur
    {
        $req_select = $this->db->prepare("SELECT * FROM utilisateurs 
                                            WHERE id= :id");
        $req_select->bindValue(':id',$id, PDO::PARAM_STR);

        try {
            $req_select->execute();
            $result=$req_select->fetch(PDO::FETCH_OBJ);
            if($result)
            {
                $Utilisateur = new Utilisateur(
                    strval($result->pseudo),
                    strval($result->mot_de_passe),
                    strval($result->role),
                    $result->email,
                    intval($result->id)
                );
            }else{
                $Utilisateur = null;
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de recupUtilisateurparId : " . $e->getMessage());
            $Utilisateur = null;
        }
        return $Utilisateur;
    }

    /**
     * verifUtilisateur permet de vérifier si le pseudo de l'utilisateur
     * et son mot de passe correspondent bien dans la table Utilisateur
     * Si oui : elle retourne l'utilisateur de type Utilisateur.
     * Sinon : elle retourne null
     * @param string $pseudo
     * @param string $mdp
     * @return Utilisateur|null Retourne un utilisateur ou null
     */
    public function verifUtilisateur(string $pseudo, string $mdp): ?Utilisateur
    {
        // Traitement des données
        $pseudo = htmlspecialchars(trim($pseudo));
        $mdp = trim($mdp);

        // Validation du format du mot de passe
        // Une majuscule minimum, un chiffre, un symbole parmi ?!*@=%$£\[]+
        // entre 13 et 255 caractères
        $pattern = '/^(?=.*[A-Z])(?=.*[0-9])(?=.*[?!*@=%$£\[\]+\/\\\.&]).{13,255}$/';

        if (!preg_match($pattern, $mdp)) {
            error_log("Erreur connexion : le mot de passe ne respecte pas les conditions de sécurité");
            return null;
        }

        // Récupération de l'utilisateur par pseudo uniquement
        $req_select = "SELECT * FROM utilisateurs WHERE pseudo = :pseudo";
        $req_select = $this->db->prepare($req_select);
        $req_select->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);

        try {
            $req_select->execute();
            $result = $req_select->fetch(PDO::FETCH_OBJ);

            if ($result) {
                // Vérification du mot de passe avec le hash stocké
                if (password_verify($mdp, $result->mot_de_passe)) {
                    // Mot de passe correct, création de l'objet utilisateur
                    $utilisateur = new Utilisateur(
                        strval($result->pseudo),
                        strval($result->mot_de_passe),
                        strval($result->role),
                        $result->email,
                        intval($result->id)
                    );
                    $retour = $utilisateur;
                } else {
                    error_log("Erreur connexion : mot de passe incorrect pour l'utilisateur : " . $pseudo);
                    $retour = null;
                }
            } else {
                error_log("Erreur connexion : utilisateur non trouvé : " . $pseudo);
                $retour = null;
            }

        } catch (PDOException $e) {
            error_log("Erreur lors de verifUtilisateur : " . $e->getMessage());
            $retour = null;
        }
        return $retour;
    }

    /**
     * recupClassementVotes permet de récupérer
     * tous les utilisateurs qui ont le plus voté depuis toujours
     * @return array Tableau contenant des utilisateurs
     */
    public function recupClassementVotes(): array
    {
        $req_select = $this->db->prepare("SELECT * , COUNT(id_vote) AS nb_votes
                                            FROM utilisateurs U
                                            INNER JOIN votes V ON V.id_utilisateur = U.id 
                                            GROUP BY V.id_utilisateur
                                            ORDER BY nb_votes DESC
                                            LIMIT 8");
        $utilisateurs = [];
        try {
            $req_select->execute();
            $results=$req_select->fetchAll(PDO::FETCH_OBJ);
            if($results)
            {
                foreach ($results as $result) {
                    $utilisateur = new Utilisateur(
                        strval($result->pseudo),
                        strval($result->mot_de_passe),
                        strval($result->role),
                        $result->email,
                        intval($result->id)
                    );
                    $utilisateurs []= $utilisateur;
                }
            }
        } catch (PDOException $e) {
            $utilisateurs = [];
        }
        return $utilisateurs;
    }
}