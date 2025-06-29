<?php
include_once('../lib/Utilisateur.php');
include_once('../lib/Utilisateur_CRUD.php');
include_once('../lib/Connexion.php');

use lib\Utilisateur;
use lib\Utilisateur_CRUD;
use lib\Connexion;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//se connecte en user, vérification admin plus tard
$pdo = new Connexion("user");
$connexion = $pdo->setConnexion();

if (is_a($connexion,"PDO")):
    if(!empty($_POST['pseudo']) && !empty($_POST['mdp'] && !empty($_POST['mdp_validation']))) {
        //traitement des données
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $mdp = trim($_POST['mdp']);
        $mdpValidation = trim(($_POST['mdp_validation']));
        $utilisateurCRUD = new Utilisateur_CRUD($connexion);
        $email = isset($_POST['email']) ? trim($_POST['email']) : null;

        // Instanciation
        $utilisateurCRUD = new Utilisateur_CRUD($connexion);

        // Vérification si l'adresse email existe
        if (!empty($email) && $utilisateurCRUD->adresseEmailExiste($email)) {
            $_SESSION['utilisateur_pseudo'] = $pseudo;
            $_SESSION['utilisateur_email'] = $email;
            header("Location: inscription.php?erreur=97");
            exit;
        }

        // Vérification si le pseudo existe
        if ($utilisateurCRUD->pseudoExiste($pseudo)) {
            if (!empty($email)) {
                $_SESSION['utilisateur_email'] = $email;
            }
            header("Location: inscription.php?erreur=98");
            exit;
        }

        // Vérification des mots de passe
        if ($mdp !== $mdpValidation) {
            $_SESSION['utilisateur_pseudo'] = $pseudo;
            if (!empty($email)) {
                $_SESSION['utilisateur_email'] = $email;
            }
            header("Location: inscription.php?erreur=99");
            exit;
        }
        //créer l'utilisateur contriuteur de base.
        $creerUtilisateur = $utilisateurCRUD->creerUtilisateur($pseudo, "contributeur", $mdp, $email);
        if($creerUtilisateur){
            $utilisateur= $utilisateurCRUD->recupUtilisateurParPseudo($pseudo);
            $_SESSION['utilisateur'] = $utilisateur;
            header("Location: index.php?inscription=succes");
        }

    }
?>
<?php require_once('../enTete.html'); ?>
<body id="top">
<?php
require_once("../includes/header.php"); ?>
<section>
    <?php require_once("../includes/formInscription.php"); ?>
</section>
<?php
require_once("../includes/footer.php");
?>
</body>
</html>
<?php endif; ?>
