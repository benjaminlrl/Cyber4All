<?php
include_once(__DIR__ . '/../includes/config.php');
use lib\Connexion;
use lib\Vote;
use lib\Vote_CRUD;
use lib\BandeauNotification;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_session = session_id();
if ($id_session):
    $pdo = new Connexion("user");
    $connexion = $pdo->setConnexion();

    //gestion des notifications
    if(isset($_GET['demandeCreation']) && $_GET['demandeCreation'] == "succes"):
        $notification = new BandeauNotification("SUCCES",
            "Demande de création réussi",
            "Nous sommes très heureux de vous voir contribuer à la communauté de CYBER4ALL, votre demande
                de création va être examiné par nos admin, vous recevrez une notification dans votre espace compte 
                si celle ci sera accepté ou refusé !");
        $notification = $notification->afficherNotification($notification);
    endif;

    // TRAITEMENT DES VOTES : Seulement si utilisateur connecté ET formulaire soumis
    if (isset($_SESSION['utilisateur']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote_token'])):
        $utilisateur = $_SESSION['utilisateur'];
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

// Traitement suppression de vote
        if (!empty($_POST['suppVote'])):
            $id_vote = $_POST['suppVote'];
            $voteCrud = new Vote_CRUD($connexion);
            $suppVote = $voteCrud->deleteVoteParIdVote($id_vote);
            if (!$suppVote):
                header('Location: communautaire.php?erreur=99');
            else:
                //CAS 1 : L'utilisateur vote depuis lexique.php?mot= ...
                if($_POST['from_lexiqueMotSeul'] === 'true'):
                    $id_mot = $_POST['id_mot'];
                    header("Location: lexiques.php?mot=$id_mot");
                //CAS 2 : L'utilisateur vote depuis lexique.php
                elseif ($_POST['from_lexique'] === 'true'):
                    header("Location: lexiques.php");
                //CAS 3 : L'utilisateur vote depuis communautaire.php
                else:
                    header('Location: communautaire.php?success=vote_supprime');
                endif;
            endif;
            exit;
        endif;

// Traitement ajout de vote
        if ((!empty($_POST['voter']) && $_POST['voter'] === 'voter') && !empty($_POST['id_mot'])):
            $vote = new Vote($utilisateur->getId(), $_POST['id_mot']);
            $voteCrud = new Vote_CRUD($connexion);
            $vote = $voteCrud->creerVote($vote);
            if (!$vote):
                header('Location: communautaire.php?erreur=98');
            else:
                //CAS 1 : L'utilisateur vote depuis lexique.php?mot= ...
                if($_POST['from_lexiqueMotSeul'] === 'true'):
                    $id_mot = $_POST['id_mot'];
                    header("Location: lexiques.php?mot=$id_mot");
                //CAS 2 : L'utilisateur vote depuis lexique.php
                elseif ($_POST['from_lexique'] === 'true'):
                    header("Location: lexiques.php");
                //CAS 3 : L'utilisateur vote depuis communautaire.php
                else:
                    header('Location: communautaire.php?success=vote_ajoute');
                endif;
            endif;
            exit;
        endif;

    elseif ($_SERVER['REQUEST_METHOD'] === 'POST'):
        // Si c'est une requête POST, mais que l'utilisateur n'est pas connecté ou pas de token
        header('Location: communautaire.php?erreur=non_autorise');
        exit();
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
                <?php require_once("../includes/votePopulaireCommunaute.php"); ?>
                <?php require_once("../includes/statistiquesCommunautaire.php"); ?>
                <?php require_once("../includes/classementMembresActif.php"); ?>
            </div>
        </div>
    </section>
    <?php require_once("../includes/footer.php"); ?>
    </body>
</html>