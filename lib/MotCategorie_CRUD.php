<?php
namespace lib;

include_once('Connexion.php');

use PDO;
use PDOException;
use lib\Connexion;
/**
 *Cette classe représente les associations des mots avec les catégories
 *ELle dispose d'un mot et d'une catégorie
 */
class MotCategorie_CRUD
{
    private PDO $db;

    /**
     * Initialise une nouvelle instance de la classe MotCategorie_CRUD
     * @param PDO $db
     */
    public function __construct(PDO $connexion)
    {
        $this->db = $connexion;
    }

    /**
     * recupTousLesMotsDuneCategorie permet de récuperer tous les monts d'une catégorie
     * reoturne une liste de mots
     * @param Categorie $categorie
     * @return array
     */
    public function recupTousLesMotsDuneCategorie(Categorie $categorie): array{
        $id_categorie = $categorie->getId();
        $req_select=$this->db->prepare("SELECT * FROM motcle M      
             INNER JOIN motcle_categorie Mc ON Mc.id_motcle=M.id 
             INNER JOIN categorie C ON Mc.id_categorie=C.id
             WHERE C.id = :id");
        $req_select->bindParam(":id",$id_categorie, PDO::PARAM_INT);

        $results=$req_select->fetchAll(PDO::FETCH_OBJ);
        try {
            $req_select->execute();
            $results=$req_select->fetchAll(PDO::FETCH_OBJ);
            $mots=[];
            if($results){
                foreach($results as $result){
                    $mot = new motCle(
                        $result->mot,
                        $result->definition,
                        $result->id
                    );
                    $mots[] = $mot;
                }
            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupTousLesMotsDuneCategorie : " . $e->getMessage());
        }
        return $mots;
    }


    /**
     * recupTousLesMotsDuneCategorieByID permet de récuperer tous les mots d'une catégorie par son id
     * reoturne une liste de mots
     * @param Categorie $categorie
     * @return array
     */
    public function recupTousLesMotsDuneCategorieParID(int $categorieID): array{
        $req_select=$this->db->prepare("SELECT M.id, M.mot, M.definition FROM motcle M      
             INNER JOIN motcle_categorie Mc ON Mc.id_motcle=M.id 
             WHERE Mc.id_categorie = :id
             ORDER BY M.mot ASC");
        $req_select->bindParam(":id",$categorieID, PDO::PARAM_INT);

        try {
            $req_select->execute();
            $results=$req_select->fetchAll(PDO::FETCH_OBJ);
            $mots=[];
            if($results){
                foreach($results as $result){
                    $mot = new motCle(
                        $result->mot,
                        $result->definition,
                        $result->id
                    );
                    $mots[] = $mot;
                }
            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupTousLesMotsDuneCategorie : " . $e->getMessage());
            return [];
        }
        return $mots;
    }

    /**
     * recupToutesLesCategoriesDunMot permet de récuperer toutes les catégories d'un mot
     * retourne une liste de catégories
     * @param MotCle $mot
     * @return array
     */
    public function recupToutesLesCategoriesDunMot(MotCle $mot): array{
        $id_mot = $mot->getId();
        $req_select=$this->db->prepare("SELECT C.id , C.nom 
            FROM categorie C
            INNER JOIN motcle_categorie Mc ON Mc.id_categorie=C.id
            WHERE Mc.id_motcle = :id_mot");
        $req_select->bindParam(":id_mot",$id_mot, PDO::PARAM_INT);

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
            error_log("Erreur recupToutesLesCategoriesDunMot : " . $e->getMessage());
        }
        return $categories;
    }
}