<?php
namespace lib;

/**
 * Cette classe représente un Mot clé
 * Un mot dispose d'un id, d'un Mot
 * et d'une définition.
 */
class MotCle
{
    private int $id;
    private string $mot;
    private string $definition;

    /**
     * Instancie une nouvelle instance de la classe MotCle
     * Un mot dispose d'un id, d'un Mot
     * d'une définition optionnelle.
     * @param int $id
     * @param string $mot
     * @param string $definition
     */
    public function __construct(string $mot, string $definition, int $id=0)
    {
        //id à 0 pour incrémenter
        $this->id = $id;
        $this->mot = $mot;
        $this->definition = $definition;
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