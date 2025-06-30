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
     * Obtient l'id de la demande de validation du mot clé
     * @return int
     */
    public function getIdMotcleValidation(): int
    {
        return $this->id_motcle_validation;
    }

    /**
     * Modifie l'id de la demande de validation du mot clé
     * Privée pour préserver l'intégrité des données de la base de données
     * @param int $id_motcle_validation
     * @return void
     */
    private function setIdMotcleValidation(int $id_motcle_validation): void
    {
        $this->id_motcle_validation = $id_motcle_validation;
    }

    /**
     * Obtient l'id de l'utilisateur qui soumet
     * la demande de validation
     * @return int
     */
    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }

    /**
     * Obtient le nom du mot soumit à la demande de validation
     * @return string
     */
    public function getMotCle(): string
    {
        return $this->mot_cle;
    }

    /**
     * Obtient la definition du mot soumit à la demande de validation
     * @return string
     */
    public function getDefinition(): string
    {
        return $this->definition;
    }

    /**
     * Obtient date de création de la demande de validation
     * @return DateTime
     */
    public function getDateDemande(): DateTime
    {
        return $this->date_demande;
    }

    /**
     * Obtient le statut du mot soumit à la demande de validation
     * ('en attente','valider','refuser')
     * @return string
     */
    public function getStatut(): string
    {
        return $this->statut;
    }

    /**
     * Modifie le statut du mot soumit à la demande de validation
     * @param string $statut
     * @return void
     */
    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }


}