<?php
namespace lib;
include_once('Connexion.php');

use PDO;
use PDOException;
use lib\Connexion;
use lib\MotCle;
use DateTime;
use lib\MotCleValidation;
/**
 * Cette classe représente la gestion des mots en attente de validation
 * d'un admin après création par un contributeur de la table motcle_validation
 */
class MotCleValidation_CRUD
{
    private PDO $db;

    /**
     * Initialise une nouvelle instance de la classe MotCleAttente_CRUD
     * @param PDO $db
     */
    public function __construct(PDO $connexion)
    {
        $this->db = $connexion;
    }

    /**
     * creerDemandeValidation permet de créer une demande de validation d'un mot
     * Elle dispose d'un utilisateur et d'un mot
     * @param Utilisateur $utilisateur
     * @param \lib\MotCle $motCle
     * @return bool vraie si la demande a été créer
     */
    public function creerDemandeValidation(Utilisateur $utilisateur, MotCle $motCle){
        $req_insert=$this->db->prepare("INSERT INTO motcle_validation (id_utilisateur, motcle, definition) 
                            VALUES(:id_utilisateur, :motcle, :definition)");
        $req_insert->bindValue(':id_utilisateur', $utilisateur->getId(), PDO::PARAM_INT);
        $req_insert->bindValue(':motcle', $motCle->getMot(), PDO::PARAM_STR);
        $req_insert->bindValue(':definition', $motCle->getDefinition(), PDO::PARAM_STR);
        try {
            $req_insert->execute();
            if($req_insert->rowCount() > 0){
                $retour = true;
            } else {
                $retour = false;
            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupTousLesMots : " . $e->getMessage());
            $retour = false;
        }
        return $retour;
    }

    /**
     * recupToutesDemandesEnAttente permet de récupérer toutes les demandes de validation
     * de mots en en attente de validation par un admin pour les admins
     * @param Utilisateur $utilisateur
     * @param \lib\MotCle $motCle
     * @return bool tableau contenant toutes les demandes 'en attente'
     */
    public function recupToutesDemandesEnAttente():array{
        $req_select=$this->db->prepare('SELECT * 
                                        FROM motcle_validation
                                        WHERE statut = "en attente"');
        try {
            $req_select->execute();
            $results = $req_select->fetchAll(PDO::FETCH_OBJ);
            $motsEnAttente = [];
            if($results){
                foreach($results as $result){
                    $motEnAttente = new MotCleValidation(
                        $result->id_utilisateur,
                        $result->motcle,
                        $result->definition,
                        $result->statut,
                        $result->id_motcle_validation,
                        $result->date_demande,
                        $result->date_validation,
                        $result->id_admin
                    );
                    $motsEnAttente[]=$motEnAttente;
                }
            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupToutesDemandesEnAttente : " . $e->getMessage());
            $motsEnAttente = [];
        }
        return $motsEnAttente;
    }

    /**
     * recupToutesDemandesEnAttenteParUtilisateurID permet de récupérer
     * toutes les demandes de validation par l'id de l'utilisateur
     * de mots en attente de validation par un admin.
     * @param int $id_utilisateur
     * @return array
     */
    public function recupToutesDemandesParUtilisateurID(int $id_utilisateur):array{
        $req_select=$this->db->prepare('SELECT * 
                                        FROM motcle_validation
                                        WHERE id_utilisateur = :id_utilisateur');
        $req_select->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        try {
            $req_select->execute();
            $results = $req_select->fetchAll(PDO::FETCH_OBJ);
            $motsEnAttente = [];
            if($results){
                foreach($results as $result){
                    $date_demande = new DateTime($result->date_demande);
                    $date_validation = $result->date_validation ? new DateTime($result->date_validation) : null;

                    $motEnAttente = new MotCleValidation(
                        $result->id_utilisateur,
                        $result->motcle,
                        $result->definition,
                        $result->statut,
                        $result->id_motcle_validation,
                        $date_demande,
                        $date_validation,
                        $result->id_admin
                    );
                    $motsEnAttente[]=$motEnAttente;
                }
            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupToutesDemandesEnAttenteParUtilisateurID : " . $e->getMessage());
            $motsEnAttente = [];
        }
        return $motsEnAttente;
    }

}