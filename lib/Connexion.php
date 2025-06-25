<?php
namespace lib;

include_once 'consts.php';

use PDO;
use PDOException;


/**
 *Connexion est une classe
 *qui permet de gérer la connexion à la base de données.
 *Elle prend ses paramètres dans le fichier qui contient les constantes
 */
class Connexion
{
    private string $serveur;
    private string $user;
    private string $pwd;
    private string $bdd;

    /**
     * Initialise une nouvelle instance de la classe connexion
     * en fonction du type d'utilisateur user ou admin.
     */
    public function __construct($typeUser)
    {
        if($typeUser === "user"){
            $this->serveur = SERVEUR;
            $this->user = USER;
            $this->pwd = PWD_USER;
            $this->bdd = BDD;
        }
        if($typeUser === "admin"){
            $this->serveur = SERVEUR;
            $this->user = ADMIN;
            $this->pwd = PWD_ADMIN;
            $this->bdd = BDD;
        }

    }

    /**
     * setConnexion permet de se connecter à la bdd
     * @return PDO|Exception
     */
    public function setConnexion(): PDO|Exception
    {
        try {
            $connexion = new PDO('mysql:host=' . $this->serveur
                . ';dbname=' . $this->bdd,
                $this->user,
                $this->pwd);
            // $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connexionOk = $connexion;
        } catch (PDOException $e) {
            $connexionOk = $e;
        }
        catch (Exception $e)
        {
            $connexionOk = $e;
        }
        return $connexionOk;
    }
}