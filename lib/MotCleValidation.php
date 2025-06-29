<?php
namespace lib;
use DateTime;
use lib\MotCle;

/**
 * Cette classe représente une demande de validation d'un mot
 */
class MotCleValidation
{
    private int $id_motcle_validation;
    private int $id_utilisateur;
    private string $mot_cle;
    private string $definition;

    private DateTime $date_demande;
    private ?DateTime $date_validation;
    private string $statut;
    private ?int $id_admin;

    /**
     * Initialise une nouvelle instance de la classe MotCleValidation
     * Elle dispose d'un id d'utilisateur, d'un mot cle, d'une definition, d'un statut d'un
     * @param int $id_motcle_validation
     * @param int $id_utilisateur
     * @param string $mot_cle
     * @param string $definition
     * @param ?DateTime $date_demande
     * @param string $statut
     */
    public function __construct(int $id_utilisateur,
                                string $mot_cle, string $definition, string $statut,
                                int $id_motcle_validation=0, ?DateTime $date_demande = null,
                                ?DateTime $date_validation = null, ?int $id_admin=null)
    {
        $this->id_motcle_validation = $id_motcle_validation;
        $this->id_utilisateur = $id_utilisateur;
        $this->mot_cle = $mot_cle;
        $this->definition = $definition;
        $this->date_demande = $date_demande;
        $this->date_validation = $date_validation;
        $this->statut = $statut;
        $this->id_admin = $id_admin;
    }


    /**
     * @return int
     */
    public function getIdMotcleValidation(): int
    {
        return $this->id_motcle_validation;
    }

    /**
     * @param int $id_motcle_validation
     * @return void
     */
    public function setIdMotcleValidation(int $id_motcle_validation): void
    {
        $this->id_motcle_validation = $id_motcle_validation;
    }

    /**
     * @return int
     */
    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }

    /**
     * @param int $id_utilisateur
     * @return void
     */
    public function setIdUtilisateur(int $id_utilisateur): void
    {
        $this->id_utilisateur = $id_utilisateur;
    }

    /**
     * @return string
     */
    public function getMotCle(): string
    {
        return $this->mot_cle;
    }

    /**
     * @param string $mot_cle
     * @return void
     */
    public function setMotCle(string $mot_cle): void
    {
        $this->mot_cle = $mot_cle;
    }

    /**
     * @return string
     */
    public function getDefinition(): string
    {
        return $this->definition;
    }

    /**
     * @param string $definition
     * @return void
     */
    public function setDefinition(string $definition): void
    {
        $this->definition = $definition;
    }

    /**
     * @return DateTime
     */
    public function getDateDemande(): DateTime
    {
        return $this->date_demande;
    }

    /**
     * @param DateTime $date_demande
     * @return void
     */
    public function setDateDemande(DateTime $date_demande): void
    {
        $this->date_demande = $date_demande;
    }

    /**
     * @return string
     */
    public function getStatut(): string
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     * @return void
     */
    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }


}