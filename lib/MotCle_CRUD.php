<?php
namespace lib;

include_once('Connexion.php');

use PDO;
use PDOException;
use lib\Connexion;

/**
 * Cette classe représente la gestion des mots de la table motcle
 */
class MotCle_CRUD
{
    private PDO $db;

    /**
     * Initialise une nouvelle instance de la classe MotCle_CRUD
     * @param PDO $db
     */
    public function __construct(PDO $connexion)
    {
        $this->db = $connexion;
    }

    /**
     * insertMotCle permet d'insérer un nouveau mot dans la table motcle
     * @param MotCle $motCle
     * @return bool vraie si création réussie
     */
    public function insertMotCle(MotCle $motCle): bool{
        $req_insert=$this->db->prepare("INSERT INTO motcle(mot, definition) 
                                        VALUES(:mot, :definition)");
        $req_insert->bindValue(':mot', $motCle->getMot(), PDO::PARAM_STR);
        $req_insert->bindValue(':definition', $motCle->getDefinition(), PDO::PARAM_STR);

        try {
            $req_insert->execute();
            $retour = true;
        }
        catch(PDOException $e) {
            $retour = false;
        }
        return $retour;
    }

    /**
     * updateMotCle permet de modifier un mot dans la table motcle
     * @param MotCle $motCle
     * @return bool vraie si modification réussie
     */
    public function updateMotCle(MotCle $motCle): bool{
        $req_update=$this->db->prepare("UPDATE motcle
                                        SET mot = :mot, definition = :definition
                                        WHERE id = :id");
        $req_update->bindValue(':mot', $motCle->getMot(), PDO::PARAM_STR);
        $req_update->bindValue(':definition', $motCle->getDefinition(), PDO::PARAM_STR);
        $req_update->bindValue(':id', $motCle->getId(), PDO::PARAM_INT);

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
     * deleteMotCle permet de supprimer un mot de la table motcle, categorie et motcle_categorie
     * @param MotCle $motCle
     * @return bool vraie si création réussie
     */
    public function deleteMotCle(MotCle $motCle): bool{
        $req_delete=$this->db->prepare("DELETE FROM motcle
                                        WHERE id = :id");
        $req_delete->bindValue(':id', $motCle->getId(), PDO::PARAM_INT);
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
     * recupTousLesMots permet d'afficher tous les mots de la table motcle
     * par ordre alphabétiques
     * @return array
     */
    public function recupTousLesMots(): array{
        $req_select=$this->db->prepare("SELECT * FROM motcle ORDER BY mot ASC");
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
            error_log("Erreur recupTousLesMots : " . $e->getMessage());
        }
        return $mots;
    }

    /**
     * recup10MotsAleatoires permet de récupérer 10 mots aléatoire de la table motcle
     * @return array
     */
    public function recup10MotsAleatoires(): array{
        //récupère tous les mots
        $mots= $this->recupTousLesMots();
        $liste10mots=[];
        try {
            if(!empty($mots)){
                // générer un nombre aléatoire selectionne le mot,
                // ajoute à la liste qui contiendra les 10 mots le mot
                // supprime de la liste des mots le mot présent à l'indice du nbAleatoire
                // réindexe le tableau contenant les mots

                for($i=0;$i<10;$i++){
                    $nbAleatoire = rand(0,count($mots)-1); //génère le premier
                    $liste10mots[]= $mots[$nbAleatoire]; //ajoute le mot à l'inidce aleatoire dans la liste des mots

                    unset($mots[$nbAleatoire]);
                    $mots=array_values($mots); //réindexer le tableau contenant les mots
                }
            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupTousLesMots : " . $e->getMessage());
        }
        return $liste10mots;
    }


    /**
     * recupTousLesMotsAvecFiltre permet d'afficher tous les mots qui contient le filtre
     * par ordre alphabétiques
     * @return array
     */
    public function recupTousLesMotsAvecFiltre(string $filtre=""): array{
        $req_select=$this->db->prepare("SELECT * FROM motcle M
             INNER JOIN motcle_categorie Mc ON Mc.id_motcle=M.id 
             INNER JOIN categorie C ON Mc.id_categorie=C.id
             WHERE M.mot LIKE :filtre
             OR C.nom LIKE :filtre
             OR M.definition LIKE :filtre
             ORDER BY M.mot ASC");
        $req_select->bindValue(':filtre','%'.$filtre.'%', PDO::PARAM_STR);
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
            error_log("Erreur recupTousLesMots : " . $e->getMessage());
            return [];
        }
        return $mots;
    }
}