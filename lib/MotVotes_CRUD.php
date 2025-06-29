<?php
namespace lib;
include_once('Connexion.php');

use PDO;
use PDOException;
use lib\Connexion;
use lib\MotCle;
use DateTime;
/**
 * Cette classe représente la gestion des mots avec les votes
 */
class MotVotes_CRUD
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
     * recupTousLesMotsVotesParUtilisateurId permet de récupérer
     * tous les mots votés de la semaine en cours
     * @param int $id_utilisateur
     * @return array
     */
    public function recupTousLesMotsVotesSemaineEnCoursParUtilisateurId(int $id_utilisateur):array{
        // Lundi de la semaine précédente
        $debutSemaine = new DateTime('monday this week');

        $req_select=$this->db->prepare("SELECT * 
                                    FROM motcle M
                                    INNER JOIN votes V ON V.id_motcle = M.id
                                    WHERE V.id_utilisateur = :id_utilisateur
                                    AND DATE(V.date_vote) >= :dateDebut");
        $req_select->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $req_select->bindValue(':dateDebut', $debutSemaine->format('Y-m-d'), PDO::PARAM_STR);
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
}