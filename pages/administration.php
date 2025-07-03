<?php
include_once(__DIR__ . '/../includes/config.php');
use lib\Connexion;
use lib\Vote;
use lib\Vote_CRUD;
use lib\BandeauNotification;
use lib\MotCleValidation;
use lib\MotCleValidation_CRUD;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_session = session_id();
if ($id_session):
    $pdo = new Connexion("user");
    $connexion = $pdo->setConnexion();
    if(isset($_SESSION['utilisateur'])){
        $utilisateur = $_SESSION['utilisateur'];
    }

    //gestion des notifications
    if(isset($_GET['demandeCreation']) && $_GET['demandeCreation'] == "succes"):
        $notification = new BandeauNotification("SUCCES",
            "Demande de création réussi",
            "Nous sommes très heureux de vous voir contribuer à la communauté de CYBER4ALL, votre demande
                de création va être examiné par nos admin, vous recevrez une notification dans votre espace compte 
                si celle ci sera accepté ou refusé !");
        $notification = $notification->afficherNotification($notification);
    endif;
    // si le token est bien présent alors on fait le traitement
    if(isset($_POST['vote_token'])):
        $submitted_token = $_POST['vote_token'];

        // Vérifier si le token existe dans la session
        if (!isset($_SESSION['vote_tokens']) || !in_array($submitted_token, $_SESSION['vote_tokens'])):
            // Token invalide ou déjà utilisé
            header('Location: communautaire.php?erreur=token_invalide');
            exit();
        endif;

        // Supprimer le token de la session (ne peut plus être réutilisé)
        $_SESSION['vote_tokens'] = array_filter($_SESSION['vote_tokens'], function($token) use ($submitted_token) {
            return $token !== $submitted_token;
        });
        if(isset($_POST['valdier']) && !empty($_POST['valdier']) && !empty($_POST['id_demande'])): //valider = id_demande
            $idDemande = intval($_POST['id_demande']);
            $motCleValitionCRUD = new MotCleValidation_CRUD($connexion);
            $motCleValidation = $motCleValitionCRUD->recupDemandeParIdDemande($idDemande);
            $valider = $motCleValitionCRUD->decisionDemandeValidation($motCleValidation, $utilisateur, "valider");
            if($valider):
                $notification = new BandeauNotification("SUCCES",
                    "Validation réussi",
                    "La demande de validation de création de mot a bien réussi, le mot a été ajouté");
                $notification = $notification->afficherNotification($notification);
            else:
                $notification = new BandeauNotification("ECHEC",
                    "ERREUR",
                    "une erreur s'est produit lors de la validation de la demande de création du mot.");
                $notification = $notification->afficherNotification($notification);
            endif;
        elseif(isset($_POST['refuser']) && !empty($_POST['refuser']) && !empty($_POST['id_demande'])): //valider = id_demande
            $idDemande = intval($_POST['id_demande']);
            $motCleValitionCRUD = new MotCleValidation_CRUD($connexion);
            $motCleValidation = $motCleValitionCRUD->recupDemandeParIdDemande($idDemande);
            $refuser = $motCleValitionCRUD->decisionDemandeValidation($motCleValidation, $utilisateur, "refuser");
            if($refuser):
                $notification = new BandeauNotification("SUCCES",
                    "Demande refusée",
                    "La demande de création a bien été refusée.");
                $notification = $notification->afficherNotification($notification);
            else:
                $notification = new BandeauNotification("ECHEC",
                    "ERREUR",
                    "une erreur s'est produit lors du refus de la demande de création du mot.");
                $notification = $notification->afficherNotification($notification);
            endif;
        endif;
    endif;
endif;
?>
<?php include_once('../enTete.html'); ?>
<body id="top">
<?php include_once('../includes/header.php'); ?>
<?= isset($notification) ? $notification : null; ?>
<section>
    <div class="container">
        <div class="dashboard">
            <?php require_once("../includes/adminDemandeValidation.php"); ?>
            <?php require_once("../includes/statistiquesAdmin.php"); ?>
            <?php require_once("../includes/classementMembresActif.php"); ?>
        </div>
    </div>
</section>
<?php require_once("../includes/footer.php"); ?>
</body>
</html>