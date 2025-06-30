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
//se connecte en user, vérification admin plus tard
$pdo = new Connexion("user");
$connexion = $pdo->setConnexion();
if (is_a($connexion,"PDO")):
    if(isset($_POST['connexion_token'])):
        $submitted_token = $_POST['connexion_token'];

        // CORRECTION : Utiliser le bon nom de variable avec 's'
        if (!isset($_SESSION['connexion_tokens']) || !in_array($submitted_token, $_SESSION['connexion_tokens'])):
            // Token invalide ou déjà utilisé
            header('Location: identification.php?erreur=token_invalide');
            exit();
        endif;

        // CORRECTION : Utiliser les bons noms de variables
        $_SESSION['connexion_tokens'] = array_filter($_SESSION['connexion_tokens'], function($token) use ($submitted_token) {
            return $token !== $submitted_token;
        });
    endif;
    if(isset($_GET['erreur'])):
        $erreur = $_GET['erreur'];
        if($erreur == "99"){
            $notification = new BandeauNotification("ERREUR",
                "Déconnexion",
                "Utilisateur non identifié, mot de passe ou pseudo incorrect");
            $notification = $notification->notificationInfo($notification);
        }
    endif;
    if(!empty($_POST['pseudo']) && !empty($_POST['mdp'])):
        // Traitement des données
        $pseudo = htmlspecialchars(trim($_POST['pseudo']));
        $mdp = trim($_POST['mdp']);

        // Vérifier l'identification de l'utilisateur
        $utilisateurCRUD = new Utilisateur_CRUD($connexion);
        $utilisateur = $utilisateurCRUD->verifUtilisateur($pseudo, $mdp);

        if ($utilisateur === null) {
            // Erreur 99 pour mdp ou pseudo incorrect
            header("location: identification.php?erreur=99");
            exit();
        } else {
            // Connexion réussie
            $_SESSION["utilisateur"] = $utilisateur;

            // Redirection vers la page demandée ou accueil
            header("location: index.php?connexion");
            exit();
        }
    endif;
?>
<?php require_once('../enTete.html'); ?>
<body id="top">
<?php
require_once("../includes/header.php");
echo isset($notification) ? $notification : null;?>

<section>
    <?php require_once("../includes/formConnexion.php"); ?>
</section>
<?php
require_once("../includes/footer.php");
?>
</body>
</html>
<?php endif; ?>
