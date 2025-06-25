<?php
namespace lib;

/**
 * Cette classe représente un Mot clé
 * Un mot dispose d'un id, d'un Mot
 * d'une catégorie optionnelle et d'une définition.
 */
class MotCle
{
    private int $id;
    private string $mot;
    private string $definition;
    private ?string $categorie;

    /**
     * Instancie une nouvelle instance de la classe MotCle
     * Un mot dispose d'un id, d'un Mot
     * d'une définition et d'une catégorie optionnelle.
     * @param int $id
     * @param string $mot
     * @param string $definition
     * @param ?string $categorie
     */
    public function __construct(int $id=0, string $mot, string $definition, ?string $categorie=null)
    {
        //id à 0 pour incrémenter
        $this->id = $id;
        $this->mot = $mot;
        $this->definition = $definition;
        $this->categorie = $categorie;
    }

    /**
     * Obtient l'id du mot actuel
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Modifie l'id du mot actuel
     * Privée puisque non modifiable
     * @param int $id
     * @return void
     */
    private function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Obtient le mot actuel
     * @return string
     */
    public function getMot(): string
    {
        return $this->mot;
    }

    /**
     * Obtient la catégorie du mot actuel
     * @return string|null
     */
    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    /**
     * Modifie la catégorie du mot actuel
     * @param string|null $categorie
     * @return void
     */
    public function setCategorie(?string $categorie): void
    {
        $this->categorie = $categorie;
    }

    /**
     * Modifie le mot actuel
     * @param string $mot
     * @return void
     */
    public function setMot(string $mot): void
    {
        $this->mot = $mot;
    }

    /**
     * Obtient la définition du mot actuel
     * @return string
     */
    public function getDefinition(): string
    {
        return $this->definition;
    }

    /**
     * Modifie la définition du mot actuel
     * @param string $definition
     * @return void
     */
    public function setDefinition(string $definition): void
    {
        $this->definition = $definition;
    }
}