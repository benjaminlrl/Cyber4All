<?php
namespace lib;

/**
 *Cette classe représente l'objet User
 * Un utilisateur dispose d'un id, d'un pseudo, d'un mot de passe,
 * d'un role, d'un email optionnel nullable.
 */
class Utilisateur
{
    private int $id;
    private string $pseudo;
    private ?string $email;
    private string $motPasse;
    private string $role;

    /**
     * Initialise une nouvelle instance de la classe Utilisateur
     * @param int $id
     * @param string $pseudo
     * @param string $motPasse
     * @param string $role
     * @param ?string $email
     */
    public function __construct(string $pseudo, string $motPasse, string $role, ?string $email = null, int $id = 0,)
    {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->motPasse = $motPasse;
        $this->role = $role;
    }

    /**
     * Obitent le role de l'utilisateur actuel
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Modifie le role de l'utilisateur actuel
     * @param string $role
     * @return void
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * Obtient le mot de passe de l'utilisateur actuel
     * @return string
     */
    public function getMotPasse(): string
    {
        return $this->motPasse;
    }

    /**
     * Modifie le mot de passe de l'utilisateur actuel
     * @param string $motPasse
     * @return void
     */
    public function setMotPasse(string $motPasse): void
    {
        $this->motPasse = $motPasse;
    }

    /**
     * Obtient l'email de l'utilisateur actuel
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Modifie l'email de l'utilisateur actuel
     * @param string|null $email
     * @return void
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * Obtient le pseudo de l'utilisateur actuel
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * Modifie le pseudo de l'utilisateur actuel
     * @param string $pseudo
     * @return void
     */
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * Obtient l'id de l'utilisateur actuel
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Modifie l'id de l'utilisateur actuel
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}