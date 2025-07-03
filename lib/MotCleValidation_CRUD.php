<?php
namespace lib;
include_once('Connexion.php');

use lib\MotCleValidation;
use PDO;
use PDOException;
use lib\Connexion;
use lib\MotCle;
use DateTime;
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
     * @return array tableau contenant tous les mots "en attente" par l'admin
     */
    public function recupToutesDemandesEnAttente():array{
        $req_select=$this->db->prepare('SELECT * 
                                        FROM motcle_validation
                                        WHERE statut = "en attente"');
        try {
            $req_select->execute();
            $results = $req_select->fetchAll(PDO::FETCH_OBJ);
            $motsValides = [];
            if($results){
                foreach($results as $result){
                    $dateDemande = new DateTime($result->date_demande);
                    $result->date_validation != null ?
                        $date_validation = new DateTime($result->date_validation) :
                        $date_validation = null;
                        $motsValide = new MotCleValidation(
                        $result->id_utilisateur,
                        $result->motcle,
                        $result->definition,
                        $result->statut,
                        $result->id_motcle_validation,
                        $dateDemande,
                        $date_validation,
                        $result->id_admin
                    );
                    $motsValides[]=$motsValide;
                }
            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupToutesDemandesEnAttente : " . $e->getMessage());
            $motsValides = [];
        }
        return $motsValides;
    }

    /**
     * recupToutesDemandesEnAttenteParUtilisateurID permet de récupérer
     * toutes les demandes de validation par l'id de l'utilisateur
     * de mots en attente de validation par un admin.
     * @param int $id_utilisateur
     * @return array Tableau contenant tout les demandes par l'id de l'utilisateur
     */
    public function recupToutesDemandesParUtilisateurID(int $id_utilisateur):array{
        $req_select=$this->db->prepare('SELECT * 
                                        FROM motcle_validation
                                        WHERE id_utilisateur = :id_utilisateur
                                        ORDER BY date_validation DESC');
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

    /**
     * recupTousMotsValideParAdminID permet de récupérer
     * tous les mots validés par l'id de l'utilisateur
     * de mots en attente de validation par un admin.
     * @param int $id_admin
     * @return array Tableau contenant tous les mots validés par l'admin
     */
    public function recupTousMotsValideParAdminID(int $id_admin):array{
        $req_select=$this->db->prepare('SELECT id, mot, definition
                                        FROM motcle M
                                        INNER JOIN votes V ON V.id_motcle = M.id
                                        INNER JOIN utilisateurs U ON U.id = V.id_utilisateur
                                        INNER JOIN motcle_validation McV ON McV.id_admin = U.id
                                        WHERE id_admin = :id_admin
                                        AND statut = "valider"
                                        ORDER BY date_validation DESC
                                        LIMIT 10');
        $req_select->bindValue(':id_admin', $id_admin, PDO::PARAM_INT);
        try {
            $req_select->execute();
            $results = $req_select->fetchAll(PDO::FETCH_OBJ);
            $motsEnAttente = [];
            if($results){
                foreach($results as $result){
                    $motEnAttente = new MotCle(
                        $result->id,
                        $result->motcle,
                        $result->definition,
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
    /**
     * recupDemandeParIdDemande permet de récupérer
     * une demande de validation par l'id de la demande
     * de mot en attente de validation par un admin.
     * @param int $id_motcle_validation
     * @return MotCleValidation|null
     */
    public function recupDemandeParIdDemande(int $id_motcle_validation): ?MotCleValidation{
        $req_select=$this->db->prepare('SELECT * 
                                        FROM motcle_validation
                                        WHERE id_motcle_validation = :id_motcle_validation');
        $req_select->bindValue(':id_motcle_validation', $id_motcle_validation, PDO::PARAM_INT);
        try {
            $req_select->execute();
            $result = $req_select->fetch(PDO::FETCH_OBJ);
            if($result){
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
            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupDemandeParIdDemande : " . $e->getMessage());
            $motEnAttente = null;
        }
        return $motEnAttente;
    }

    /**
     * recupDemandesParOrdDESC permet de récupérer
     * les demandes déjà traiter par l'id de l'admin.
     * @param int $id_motcle_validation
     * @return array
     */
    public function recupDemandesParOrdDESC(int $id_admin): array{
        $req_select=$this->db->prepare('SELECT * 
                                        FROM motcle_validation
                                        WHERE id_admin = :id_admin
                                        ORDER BY date_validation ASC
                                        LIMIT 10');
        $req_select->bindValue(':id_admin', $id_admin, PDO::PARAM_INT);
        try {
            $req_select->execute();
            $results = $req_select->fetchAll(PDO::FETCH_OBJ);
            $motTraites= [];
            if($results){
                foreach($results as $result){
                    $date_demande = new DateTime($result->date_demande);
                    $date_validation = new DateTime($result->date_validation);

                    $motTraite = new MotCleValidation(
                        $result->id_utilisateur,
                        $result->motcle,
                        $result->definition,
                        $result->statut,
                        $result->id_motcle_validation,
                        $date_demande,
                        $date_validation,
                        $result->id_admin
                    );
                    $motTraites[] = $motTraite;
                }

            }
        }
        catch(PDOException $e) {
            error_log("Erreur recupDemandesParOrdDESC : " . $e->getMessage());
            $motEnAttente = null;
        }
        return $motEnAttente;
    }


    /**
     * decisionDemandeValidation permet de changer
     * le statut de la demande de validation afin
     * de prendre une decision sur la creation du mot
     * @param \lib\MotCleValidation $motCleValidation
     * @param Utilisateur $admin
     * @param string $statutValidation "refuser", "valider"
     * @return bool vraie si réussi
     */
    public function decisionDemandeValidation(MotCleValidation $motCleValidation,
                                            Utilisateur $admin,
                                            string $statutValidation):bool
    {
        //requete pour supprimer la dmeande de valdiation de la table motcle_valdiation
        $id_motcle_validation = $motCleValidation->getidMotcleValidation();
        $motCle = $motCleValidation->getMotCle();
        $definition = $motCleValidation->getDefinition();
        $date_validation = new DateTime()->format('Y-m-d H:i:s');
        $id_admin = $admin->getId();
        $retour = false;

        if ($statutValidation === "valider" || $statutValidation === "refuser"):
            $req_update = $this->db->prepare("UPDATE motcle_validation
                           SET date_validation = :date_validation,
                               id_admin = :id_admin,
                               statut = :statut
                           WHERE id_motcle_validation = :id_motcle_validation");
            $req_update->bindValue(':id_motcle_validation', $id_motcle_validation, PDO::PARAM_INT);
            $req_update->bindValue(':statut', $statutValidation, PDO::PARAM_STR);
            $req_update->bindValue(':date_validation', $date_validation, PDO::PARAM_STR);
            $req_update->bindValue(':id_admin', $id_admin, PDO::PARAM_INT);

            $req_insert = $this->db->prepare("INSERT INTO motcle
                                            (mot, definition)
                                            VALUES(:mot, :definition)");
            $req_insert->bindValue(':mot', $motCle, PDO::PARAM_STR);
            $req_insert->bindValue(':definition', $definition, PDO::PARAM_STR);

            try {
                // Execute UPDATE
                $req_update->execute();
                $result_update = $req_update->rowCount(); // Nombre de lignes affectées

                $result_insert = 0; // Initialisation

                // Execute INSERT only if validating
                if ($statutValidation === "valider") {
                    $req_insert->execute();
                    $result_insert = $req_insert->rowCount(); // Nombre de lignes insérées
                }

                // Check success based on status
                if ($statutValidation === "valider") {
                    // Both UPDATE and INSERT must succeed
                    if ($result_update > 0 && $result_insert > 0) {
                        $retour = true;
                    }
                } else {
                    // Only UPDATE must succeed for "refuser"
                    if ($result_update > 0) {
                        $retour = true;
                    }
                }

            } catch (PDOException $e) {
                error_log("Erreur decisionDemandeValidation: " . $e->getMessage());
                $retour = false;
            }
        endif;
        return $retour;
    }
}