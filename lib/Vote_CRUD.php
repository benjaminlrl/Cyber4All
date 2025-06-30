<?php
namespace lib;
include_once('Connexion.php');

use PDO;
use PDOException;
use lib\Connexion;
use DateTime;

/**
 * Cette classe représente la gestion des votes de la table votes
 */
class Vote_CRUD
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
     * creerVote permet d'ajouter un nouveau vote à la table votes
     * @param \lib\Vote $vote
     * @return bool vraie si la création a réussi
     */
    public function creerVote(Vote $vote): bool
    {
        //préparation de la requête
        $req_insert = $this->db->prepare("INSERT INTO votes
        (id_utilisateur, id_motcle, date_vote) 
         VALUES (:id_utilisateur, :id_motcle, :date_vote)");

        $req_insert->bindValue(':id_utilisateur', $vote->getIdUtilisateur(), PDO::PARAM_INT);
        $req_insert->bindValue(':id_motcle', $vote->getIdMot(),PDO::PARAM_INT);
        $req_insert->bindValue(':date_vote', $vote->getDateVote()->format('Y-m-d H:i:s'), PDO::PARAM_STR );

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
     * deleteVote permet de supprimer un vote par son id de la table votes
     * @param \lib\Vote $vote
     * @return bool vraie si la suppression a réussi
     */
    public function deleteVoteParIdVote(int $id_vote): bool
    {
        //préparation de la requête
        $req_delete = $this->db->prepare("DELETE FROM votes
                                                WHERE id_vote= :id_vote");
        $req_delete->bindValue(':id_vote', $id_vote ,PDO::PARAM_INT);
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
     * recupVotesTotalParUtilisateurId permet de récupérer le nombre
     * total de votes d'un utilisateur
     * @param int $id_utilisateur
     * @return int Nombres de vote
     */
    public function recupVotesTotalParUtilisateurId(int $id_utilisateur):int{
        $req_select = $this->db->prepare("SELECT COUNT(id_vote) AS nb_votes
                            FROM votes 
                            WHERE id_utilisateur= :id_utilisateur");
        $req_select->bindValue(':id_utilisateur', $id_utilisateur ,PDO::PARAM_INT);
        try {
            $req_select->execute();
            $result = $req_select->fetch(PDO::FETCH_ASSOC);
            $nbVotes = $result["nb_votes"];
        }
        catch(PDOException $e) {
            error_log("Erreur getVotesTotalParUtilisateurId : " . $e->getMessage());
            $retour = false;
        }
        return $nbVotes!=null ? $nbVotes : 0;
    }

    /**
     * recupNbVotesDepuisJourParUtilisateurId permet de récupérer le nombre
     * total de votes d'un utilisateur depuis le lundi précédent
     * @param int $id_utilisateur
     * @return int Nombre de votes
     */
    public function recupNbVotesSemaineEnCoursParUtilisateurId(int $id_utilisateur):int{
        // Lundi de la semaine précédente
        $debutSemaine = new DateTime('monday this week');

        $req_select = $this->db->prepare("SELECT COUNT(id_vote) AS nb_votes
                            FROM votes 
                            WHERE id_utilisateur= :id_utilisateur
                            AND DATE(date_vote) >= :date");
        $req_select->bindValue(':date', $debutSemaine->format('Y-m-d H:i:s') ,PDO::PARAM_STR);
        $req_select->bindValue(':id_utilisateur', $id_utilisateur ,PDO::PARAM_INT);
        try {
            $req_select->execute();
            $result = $req_select->fetch(PDO::FETCH_ASSOC);
            $nbVotes = $result["nb_votes"];
        }
        catch(PDOException $e) {
            error_log("Erreur getVotesDepuisJourParUtilisateurId : " . $e->getMessage());
            $nbVotes = null;
        }
        return $nbVotes!=null ? $nbVotes : 0;
    }

    /**
     * recupUnVoteSemaineEnCours permet de récupérer un
     * Vote d'un utilisateur depuis le lundi de cette semaine
     * @param int $id_utilisateur
     * @return Vote Le Vote de l'utilisateur
     */
    public function recupUnVoteSemaineEnCours(int $id_utilisateur, int $id_motcle):Vote{
        $debutSemaine = new DateTime('monday this week');

        $req_select = $this->db->prepare("SELECT *
                            FROM votes 
                            WHERE id_utilisateur= :id_utilisateur
                            AND id_motcle= :id_motcle
                            AND DATE(date_vote) >= :date");
        $req_select->bindValue(':date', $debutSemaine->format('Y-m-d H:i:s') ,PDO::PARAM_STR);
        $req_select->bindValue(':id_motcle', $id_motcle ,PDO::PARAM_INT);
        $req_select->bindValue(':id_utilisateur', $id_utilisateur ,PDO::PARAM_INT);
        $vote = [];
        try {
            $req_select->execute();
            $result = $req_select->fetch(PDO::FETCH_OBJ);
                $vote = new Vote(
                    $result->id_utilisateur,
                    $result->id_motcle,
                    $result->id_vote,
                    new DateTime($result->date_vote)
                );
                $votes []= $vote;

        }
        catch(PDOException $e) {
            error_log("Erreur getVotesDepuisJourParUtilisateurId : " . $e->getMessage());
        }
        return $vote;
    }

    /**
     * recupVotesTotaux permet de récupérer le nombre
     * total de votes
     * @return int
     */
    public function recupVotesTotaux():int{
        $req_select = $this->db->prepare("SELECT COUNT(id_vote) AS nb_votes
                            FROM votes");
        try {
            $req_select->execute();
            $result = $req_select->fetch(PDO::FETCH_OBJ);
            $nbVotes = $result->nb_votes;
        }
        catch(PDOException $e) {
            error_log("Erreur getVotesDepuisJourParUtilisateurId : " . $e->getMessage());
            $retour = false;
        }
        return $nbVotes!=null ? $nbVotes : 0;
    }

    /**
     * recupMotsVotesSemainePrecedenteParUtilisateurId
     * permet de récupérer les id des mots
     * deja voter par l'id d'un utilisateur depuis
     * le lundi de la semaine courante
     * @param int $id_utilisateur
     * @return array Tableau contenant les id des mots
     */
    public function recupMotsVotesSemaineEnCoursParUtilisateurId(int $id_utilisateur):array{
        // Lundi de la semaine courante
        $debutSemaine = new DateTime('monday this week');

        $req_select = $this->db->prepare("SELECT id_motcle
                            FROM votes 
                            WHERE id_utilisateur= :id_utilisateur
                            AND DATE(date_vote) >= :date");
        $req_select->bindValue(':date', $debutSemaine->format('Y-m-d H:i:s') ,PDO::PARAM_STR);
        $req_select->bindValue(':id_utilisateur', $id_utilisateur ,PDO::PARAM_INT);
        $listeIdMots = [];
        try {
            $req_select->execute();
            $results = $req_select->fetchAll(PDO::FETCH_OBJ);
            if($results){
                foreach ($results as $result){
                    $listeIdMots[] = $result->id_motcle;
                }
            }
        }
        catch(PDOException $e) {
            error_log("Erreur getVotesDepuisJourParUtilisateurId : " . $e->getMessage());
            $listeIdMots = [];
        }
        return $listeIdMots;
    }

    /**
     * Récupère les mots les plus votés de la semaine précédente
     * (du lundi au dimanche précédent)
     * @return array Tableau contenant des MotCle
     * @throws \DateMalformedStringException si la date n'est pas en string
     */
    public function recupMotsPopulairesSemainePrecedente(): array {
        // Lundi de la semaine précédente
        $debutSemaine = new DateTime('last monday');

        // Dimanche de la semaine précédente
        $finSemaine = clone $debutSemaine;
        $finSemaine->modify('+6 days');

        $req_select = $this->db->prepare("SELECT id_mot, COUNT(*) as nb_votes 
                                        FROM votes 
                                        WHERE DATE(date_vote) >= :debut 
                                        AND DATE(date_vote) <= :fin
                                        GROUP BY id_motcle 
                                        ORDER BY nb_votes DESC
                                    ");
        $req_select->bindValue(':debut', $debutSemaine->format('Y-m-d H:i:s') ,PDO::PARAM_STR);
        $req_select->bindValue(':fin', $finSemaine->format('Y-m-d H:i:s') ,PDO::PARAM_STR);
        $listeMots = [];
        try {
            $req_select->execute();
            $results = $req_select->fetchAll(PDO::FETCH_OBJ);
            if($results){
                foreach ($results as $result){
                    $listeMots[] = $result->id_mot;
                }
            }
        } catch(PDOException $e) {
            error_log("Erreur getMotsPopulairesSemainePrecedente : " . $e->getMessage());
            $listeMots = [];
        }
        return $listeMots;
    }
}