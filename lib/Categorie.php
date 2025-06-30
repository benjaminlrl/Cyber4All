<?php
namespace lib;
/**
 * Cette classe représente une catégorie
 * Une catégorie dispose d'un id et d'un nom
 */
class Categorie
{
    /**
     * Représente l'identifiant unique de la catégorie.
     * @var int Identifiant autoincrémenté dans la table catégorie
     */
    private int $id;

    /**
     *  Nom descriptif de la catégorie
     * @var string Nom de la catégorie
     */
    private string $nom;

    /**
     * Initialise une nouvelle instance de la classe Categorie
     * avec un nom obligatoire et un identifiant optionnel.
     * L'identifiant par défaut est 0,
     * indiquant un nouvel enregistrement.
     * @param int $id
     * @param string $nom
     */
    public function __construct( string $nom, int $id = 0){
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
     * Privée afin de garder l'intégrité des données de la base de données
     * @param int $id
     * @return void
     */
    private function setId(int $id): void
    {
        $this->id = $id;
    }
}