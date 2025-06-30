<?php
namespace lib;

include_once('Connexion.php');

use PDO;
use PDOException;
use lib\Connexion;


/**
 * Cette classe représente la gestion des catégories de la table categorie
 */
class Categorie_CRUD
{
    private PDO $db;

    /**
     * Initialise une nouvelle instance de la classe Categorie_CRUD
     * @param PDO $db
     */
    public function __construct(PDO $connexion)
    {
        $this->db = $connexion;
    }

    /**
     * creerCategorie permet de créer une nouvelle catégorie dans la table categorie
     * @param string $nomCategorie
     * @return bool vraie si la création a réussi
     */
    public function creerCategorie(string $nomCategorie): bool{
        $retour = false;
        $req_insert=$this->db->prepare("INSERT INTO categorie(nom)
                                        VALUES(:nomCategorie)");
        $req_insert->bindValue(':nomCategorie',$nomCategorie, PDO::PARAM_STR);
        try {
            $req_insert->execute();
            $retour = true;
        }catch (PDOException $e){
            echo $e->getMessage();
            $retour = false;
        }
        return $retour;
    }

    /**
     * supprimerCategorie permet de créer une nouvelle catégorie dans la table categorie
     * @param Categorie $categorie
     * @return bool vraie si la suppression a réussi
     */
    public function supprimerCategorie(Categorie $categorie): bool{
        $retour = false;
        $req_delete=$this->db->prepare("DELETE FROM categorie
                                        WHERE id = :id");
        $req_delete->bindValue(':id',$categorie->getId(), PDO::PARAM_INT);
        try {
            $req_delete->execute();
            $retour = true;
        }catch (PDOException $e){
            echo $e->getMessage();
            $retour = false;
        }
        return $retour;
    }

    /**
     * updateCategorie permet de modifier une categorie dans la table categorie
     * @param Categorie $categorie
     * @return bool vraie si la modification a réussie
     */
    public function updateCategorie(Categorie $categorie): bool{
        $req_update=$this->db->prepare("UPDATE categorie
                                        SET nom = :nom
                                        WHERE id = :id");
        $req_update->bindValue(':nom', $categorie->getNom(), PDO::PARAM_STR);
        $req_update->bindValue(':id', $categorie->getId(), PDO::PARAM_INT);

        try {
            $req_update->execute();
            $retour = true;
        }
        catch(PDOException $e) {
            $retour = false;
        }
        return $retour;
    }

    /**
     * recupToutesLesCategories permet de récupérer toutes les catégories de la table categorie
     * par ordre alphabétiques
     * @return array Tableau de Categorie contenant toutes les catégories
     */
    public function recupToutesLesCategories(): array{
        $req_select=$this->db->prepare("SELECT * FROM categorie ORDER BY nom ASC");
        try {
            $req_select->execute();
            $results=$req_select->fetchAll(PDO::FETCH_OBJ);
            $categories=[];
            if($results){
                foreach($results as $result){
                    $categorie = new Categorie(
                        $result->nom,
                        $result->id
                    );
                    $categories[] = $categorie;
                }
            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupToutesLesCategories : " . $e->getMessage());
        }
        return $categories;
    }

    /**
     * recupCategorieParID permet de récupérer la catégorie par son id
     * de la table categorie.
     * @return Categorie  Une Categorie
     */
    public function recupCategorieParID(int $id): Categorie{
        $req_select=$this->db->prepare("SELECT * FROM categorie 
                                        WHERE id = :id");
        $req_select->bindValue(':id', $id, PDO::PARAM_INT);
        try {
            $req_select->execute();
            $result=$req_select->fetch(PDO::FETCH_OBJ);
            $categories=[];
            if($result){
                $categorie = new Categorie(
                    $result->nom,
                    $result->id
                );
            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupToutesLesCategories : " . $e->getMessage());
        }
        return $categorie;
    }
}