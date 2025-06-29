<?php
include_once(__DIR__ . '/../lib/Connexion.php');
include_once(__DIR__ . '/../lib/MotCle_CRUD.php');
include_once(__DIR__ . '/../lib/MotCategorie_CRUD.php');
include_once(__DIR__ . '/../lib/Categorie_CRUD.php');
include_once(__DIR__ . '/../lib/MotCle.php');
include_once(__DIR__ . '/../lib/Vote.php');
include_once(__DIR__ . '/../lib/Vote_CRUD.php');
include_once(__DIR__ . '/../lib/Utilisateur.php');
include_once(__DIR__ . '/../lib/Utilisateur_CRUD.php');

use lib\Categorie_CRUD;
use lib\Connexion;
use lib\MotCle_CRUD;
use lib\MotCategorie_CRUD;
use lib\Vote;
use lib\Vote_CRUD;
use lib\Utilisateur_CRUD;
use lib\Utilisateur;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_session = session_id();
if ($id_session):
    $pdo = new Connexion("user");
    $connexion = $pdo->setConnexion();

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
                if (isset($_POST['from_lexique']) && $_POST['from_lexique'] === 'true'):
                    // Si on vient de lexique.php, on y retourne
                    header("Location: lexiques.php");
                    exit();
                endif;
                header('Location: communautaire.php?success=vote_supprime');
            endif;
            exit();
        endif;

        // Traitement ajout de vote
        if ((!empty($_POST['voter']) && $_POST['voter'] === 'voter') && !empty($_POST['id_mot'])):
            $vote = new Vote($utilisateur->getId(), $_POST['id_mot']);
            $voteCrud = new Vote_CRUD($connexion);
            $vote = $voteCrud->creerVote($vote);
            if (!$vote):
                header('Location: communautaire.php?erreur=98');
            else:
                if (isset($_POST['from_lexique']) && $_POST['from_lexique'] === 'true'):
                    // Si on vient de lexique.php, on y retourne
                    header("Location: lexiques.php");
                    exit();
                endif;
                header('Location: communautaire.php?success=vote_ajoute');
                // Redirection conditionnelle
            endif;
            exit();
        endif;

    elseif ($_SERVER['REQUEST_METHOD'] === 'POST'):
        // Si c'est une requête POST mais que l'utilisateur n'est pas connecté ou pas de token
        header('Location: communautaire.php?erreur=non_autorise');
        exit();
    endif;
endif;
?>
<?php include_once('../enTete.html'); ?>
    <body id="top">
    <?php require_once("../includes/header.php"); ?>
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