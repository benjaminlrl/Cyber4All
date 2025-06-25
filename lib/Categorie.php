<?php
namespace lib;
/**
 * Cette classe représente une catégorie
 * Une catégorie dispose d'un id et d'un nom
 */
class Categorie
{
    private int $id;
    private string $nom;

    /**
     * Initialise une nouvelle instance de la classe Categorie
     * @param int $id
     * @param string $nom
     */
    public function __construct(int $id = 0, string $nom){
        //id autoincrément
        $this->id = $id;
        $this->nom = $nom;
    }

    /**
     * Obtient le nom de la catégorie actuel
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * Modifie le nom de la catégorie actuel
     * @param string $nom
     * @return void
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * Obtient l'id de la catégorie actuel
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Modifie l'id de la catégorie actuel
     * Privée puisqu'on ne peut pas le modifier
     * @param int $id
     * @return void
     */
    private function setId(int $id): void
    {
        $this->id = $id;
    }
}