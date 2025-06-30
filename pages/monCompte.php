<?php
include_once('../lib/Utilisateur.php');
include_once('../lib/Utilisateur_CRUD.php');
include_once('../lib/Connexion.php');
include_once('../lib/BandeauNotification.php');

use lib\Utilisateur;
use lib\Utilisateur_CRUD;
use lib\Connexion;
use lib\BandeauNotification;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$sessionId = session_id();
if($sessionId):
    //se connecte en user, vérification admin plus tard
    $pdo = new Connexion("user");
    $connexion = $pdo->setConnexion();
    if(isset($_SESSION['utilisateur'])):
        $utilisateur = $_SESSION['utilisateur'];
        $nouvEmail= null;
        $nouvPseudo="";
        //Gestion des notifications
            if (isset($_GET['modification'])):
                $succes = $_GET['modification'];
                if($succes == "succes") {
                    $notification = new BandeauNotification("SUCCES",
                        "Les modification ont réussie",
                        "Les modifications ont bien été prises en compte");
                    $notification = $notification->afficherNotification($notification);
                }
                if($succes == "aucune") {
                    $notification = new BandeauNotification("INFO",
                        "Aucune modification effectuée",
                        "Ni le pseudo ni l'adresse email ont été modifié");
                    $notification = $notification->afficherNotification($notification);
                }
            endif;
            if(!empty($_GET['erreur'])):
            $erreur = $_GET['erreur'];
            switch ($erreur) {
                case 98:
                    $notification = new BandeauNotification("ERREUR",
                        "Echec de la modification",
                        "Pseudo déjà existant, veuillez en choisir un autre");
                    $notification = $notification->afficherNotification($notification);
                    isset($_SESSION['nouvEmail']) ?
                        $nouvEmail = $_SESSION['nouvEmail']:
                        $nouvEmail = null;
                    break;
                case 97:
                    $notification = new BandeauNotification("ERREUR",
                        "Echec de la modification",
                        "Cette adresse email est déjà associé à un compte");
                    $notification = $notification->afficherNotification($notification);
                    isset($_SESSION['nouvPseudo']) ?
                        $nouvPseudo = $_SESSION['nouvPseudo'] :
                        $nouvPseudo = "";
                    break;
                case 96:
                    $notification = new BandeauNotification("ERREUR",
                        "Echec de la modification",
                        "L'addresse email renseigné n'est pas valide");
                    $notification = $notification->afficherNotification($notification);
                    isset($_SESSION['nouvPseudo']) ?
                        $nouvPseudo = $_SESSION['nouvPseudo'] :
                        $nouvPseudo = "";
                    break;
            }
            endif;


            //traitement du formulaire et des données
            if(!empty($_POST['nouvPseudo']) && isset($_POST['nouvEmail'])):

                $nouvPseudo = htmlspecialchars($_POST['nouvPseudo']);
                $nouvEmail = isset($_POST['nouvEmail']) ? trim($_POST['nouvEmail']) : null;
                if($nouvEmail === $utilisateur->getEmail() &&
                    $nouvPseudo === $utilisateur->getPseudo() &&
                    !isset($_POST['suppEmail'])):
                    header("Location: monCompte.php?modification=aucune");
                    exit;
                endif;
                if($nouvPseudo === $utilisateur->getPseudo() && empty($_POST['nouvEmail'])):
                    header("Location: monCompte.php?modification=aucune");
                    exit;
                endif;
                if(filter_var($nouvEmail, FILTER_VALIDATE_EMAIL)===false):
                    header("Location: monCompte.php?erreur=96");
                    exit;
                endif;
                if(!empty($_POST['suppEmail']) && $_POST['suppEmail'] === "true"):
                    $nouvEmail = null;
                endif;

                $utilisateurCRUD = new Utilisateur_CRUD($connexion);

                // Vérification si l'adresse email existe sur un autre compte.

                if(!empty($nouvEmail) && $nouvEmail!== $utilisateur->getEmail()):
                    $emailExiste = $utilisateurCRUD->adresseEmailExiste($nouvEmail);
                    if ($emailExiste):
                        $_SESSION['nouvPseudo'] = $nouvPseudo;
                        $_SESSION['nouvEmail'] = $nouvEmail;
                        header("Location: monCompte.php?erreur=97");
                        exit;
                    endif;
                endif;

                // verifier si le pseudo existe que s'il ne s'agit pas du sien
                if($nouvPseudo!== $utilisateur->getPseudo()):
                    $pseudoExiste = $utilisateurCRUD->pseudoExiste($nouvPseudo);
                    if ($pseudoExiste):
                        if (!empty($nouvEmail)) {
                            $_SESSION['nouvEmail'] = $nouvEmail;
                        }
                        header("Location: monCompte.php?erreur=98");
                        exit;
                    endif;
                endif;
                //modifier l'utilisateur
                $modifUtilisateur = $utilisateurCRUD->updateUtilisateur($utilisateur,
                    $nouvPseudo,
                    $utilisateur->getRole(),
                    $nouvEmail
                );

                if($modifUtilisateur){
                    $utilisateurCRUD = new Utilisateur_CRUD($connexion);
                    $utilisateur = $utilisateurCRUD->recupUtilisateurparId($_SESSION['utilisateur']->getId());

                    if($utilisateur === null) {
                        // Session corrompue, déconnexion
                        session_destroy();
                        header("Location: ../identification.php?erreur=99");
                        exit;
                    }

                    // Mettre à jour la session avec les données fraîches
                    $_SESSION['utilisateur'] = $utilisateur;
                    header("Location: monCompte.php?modification=succes");
                }
            endif;
?>
<?php require_once('../enTete.html'); ?>
<body id="top">
<?php
include_once('../includes/header.php');
echo isset($notification) ? $notification : ""; ?>
<div class="compte-container">
    <div class="compte-grid">
        <div class="compte-ligne-haut">
            <div class="compte-block gauche">
                <?php include_once("../includes/informationCompte.php"); ?>
            </div>
            <div class="compte-block droite">
                <?php include_once("../includes/formModificationCompte.php"); ?>
            </div>
        </div>
        <div class="compte-ligne-bas">
            <?php include_once("../includes/afficherDemandesCreationMots.php"); ?>
        </div>
    </div>
</div>
<?php
require_once("../includes/footer.php");
?>
</body>
</html>
<?php
    else:
        header('Location: identification.php');
    endif;
endif;?>
