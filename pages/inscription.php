<?php
include_once('../includes/config.php');
use lib\Utilisateur_CRUD;
use lib\Connexion;
use lib\BandeauNotification;

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

        if(isset($_GET['erreur'])):
            $erreur = $_GET['erreur'];
            if($erreur == "96"){
                $notification = new BandeauNotification("ERREUR",
                    "Inscription",
                    "Votre mot de passe ne peut pas contenir votre pseudo !");
                $notification = $notification->afficherNotification($notification);
            }
            if($erreur == "97"){
                $notification = new BandeauNotification("ERREUR",
                    "Inscription",
                    "L'adresse email est déjà associé à un compte existant, 
                    veuillez en saisir une autre!");
                $notification = $notification->afficherNotification($notification);
            }
            if($erreur == "98"){
                $notification = new BandeauNotification("ERREUR",
                    "Inscription",
                    "Le pseudo saisie existe déjà, veuillez en choisir un autre!");
                $notification = $notification->afficherNotification($notification);
            }
            if($erreur == "99"){
                $notification = new BandeauNotification("ERREUR",
                    "Inscription",
                    "Les mots de passes saisie ne sont pas identiques !");
                $notification = $notification->afficherNotification($notification);
            }
        endif;
        // Vérification que le pseudo et le mot de passe ne se contiennent pas mutuellement
        if ((!empty($pseudo) && !empty($mdp)) &&
            (str_contains(strtolower($mdp), strtolower($pseudo)) ||
                str_contains(strtolower($pseudo), strtolower($mdp)))) :
            $_SESSION['utilisateur_pseudo'] = $pseudo;
            $_SESSION['utilisateur_email'] = $email;
            header("Location: inscription.php?erreur=96");
            exit;
        endif;
        // Vérification si l'adresse email existe
        if (!empty($email) && $utilisateurCRUD->adresseEmailExiste($email)) :
            $_SESSION['utilisateur_pseudo'] = $pseudo;
            $_SESSION['utilisateur_email'] = $email;
            header("Location: inscription.php?erreur=97");
            exit;
        endif;
        // Vérification si le pseudo existe
        if ($utilisateurCRUD->pseudoExiste($pseudo)) :
            if (!empty($email)) {
                $_SESSION['utilisateur_email'] = $email;
            }
            header("Location: inscription.php?erreur=98");
            exit;
        endif;
        // Vérification des mots de passe
        if ($mdp !== $mdpValidation) :
            $_SESSION['utilisateur_pseudo'] = $pseudo;
            if (!empty($email)) {
                $_SESSION['utilisateur_email'] = $email;
            }
            header("Location: inscription.php?erreur=99");
            exit;
        endif;
        //créer l'utilisateur contriuteur de base.
        $creerUtilisateur = $utilisateurCRUD->creerUtilisateur($pseudo, "contributeur", $mdp, $email);
        if($creerUtilisateur):
            $utilisateur= $utilisateurCRUD->recupUtilisateurParPseudo($pseudo);
            $_SESSION['utilisateur'] = $utilisateur;
            header("Location: acceuil.php?inscription=succes");
        endif;

    }
?>
<?php require_once('../enTete.html'); ?>
<body id="top">
<?php
require_once("../includes/header.php"); ?>
<?= isset($notification)? $notification: null?>
<section>
    <?php require_once("../includes/formInscription.php"); ?>
</section>
<?php
require_once("../includes/footer.php");
?>
</body>
</html>
<?php endif; ?>
