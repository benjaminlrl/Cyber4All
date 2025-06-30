<?php
namespace lib;
use DateTime;
/**
 * Cette classe représente un vote,
 * un vote dispose d'un id de vote,
 * d'un id d'utilistaeur,
 * un id de mot clé d'une note de 0 à 5
 * et d'une date de vote
 */
class Vote
{
    private int $id_vote;
    private int $id_utilisateur;
    private int $id_mot;
    private ?DateTime $dateVote;

    /**
     * Initialise une nouvelle instance de la classe Vote
     * Un vote dispose d'un id d'utilisateur, d'un id de mot : obligatoire.
     * Un id de vote autoincrémenté dans la table votes
     * et une date de vote correspond à la date du vote
     * @param int $id_utilisateur
     * @param int $id_mot
     * @param int $id_vote
     * @param ?DateTime $dateVote
     */
    public function __construct(int $id_utilisateur, int $id_mot, int $id_vote = 0, ?DateTime $dateVote = null)
    {
        $this->id_utilisateur = $id_utilisateur;
        $this->id_mot = $id_mot;
        $this->id_vote = $id_vote;

        // Si pas de date fournie, utilise la date/heure actuelle
        $this->dateVote = $dateVote ?? new DateTime();
    }

    /**
     * Obtient l'id du vote actuel
     * @return int
     */
    public function getIdVote(): int
    {
        return $this->id_vote;
    }

    /**
     * Modifie l'id du vote actuel
     * Privée afin de préserver l'intégrité
     * des données de la base de données
     * @param int $id_vote
     * @return void
     */
    private function setIdVote(int $id_vote): void
    {
        $this->id_vote = $id_vote;
    }

    /**
     * Obtient l'id de l'utilisateur du vote actuel
     * @return int
     */
    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }

    /**
     * Modifie l'id de l'utilisateur du vote actuel
     * Privée afin de préserver l'intégrité
     * des données de la base données
     * @param int $id_utilisateur
     * @return void
     */
    private function setIdUtilisateur(int $id_utilisateur): void
    {
        $this->id_utilisateur = $id_utilisateur;
    }

    /**
     * Obtient l'id du mot du vote actuel
     * @return int
     */
    public function getIdMot(): int
    {
        return $this->id_mot;
    }

    /**
     * Modifie l'id du mot du vote actuel
     * Privée afin de préserver l'intégrité
     * des données de la base de données
     * @param int $id_mot
     * @return void
     */
    public function setIdMot(int $id_mot): void
    {
        $this->id_mot = $id_mot;
    }

    /**
     * Obtient la date du vote actuel
     * @return DateTime
     */
    public function getDateVote(): DateTime
    {
        return $this->dateVote;
    }

    /**
     * Modifie la date du vote actuel
     * Privée afin de préserver l'intégrité
     * des données de la base de données
     * @param DateTime $dateVote
     * @return void
     */
    private function setDateVote(DateTime $dateVote): void
    {
        $this->dateVote = $dateVote;
    }


}