<?php
include_once('../lib/Connexion.php');
include_once('../lib/MotCle_CRUD.php');
include_once('../lib/MotCategorie_CRUD.php');
include_once('../lib/Categorie_CRUD.php');
include_once('../lib/MotCle.php');
include_once('../lib/MotVotes_CRUD.php');
include_once('../lib/Vote.php');
include_once('../lib/Vote_CRUD.php');
include_once('../lib/Utilisateur.php');
include_once('../lib/Utilisateur_CRUD.php');
include_once('../lib/consts.php');

use lib\Categorie_CRUD;
use lib\Connexion;
use lib\MotCle_CRUD;
use lib\MotCle;
use lib\MotCategorie_CRUD;
use lib\Vote_CRUD;
use lib\MotVotes_CRUD;
use lib\Vote;
use lib\Utilisateur;
use lib\Utilisateur_CRUD;

$id_session = session_id();
if ($id_session):
    $pdo = new Connexion("user");
    $connexion = $pdo->setConnexion();
    if(isset($_SESSION['utilisateur'])):
        $utilisateur = $_SESSION['utilisateur'];
    endif;
    //récupérer le nombre de vote de la semaine en cours par l'utilisateur
    $voteCRUD = new Vote_CRUD($connexion);
    $nbVotesSemaine = $voteCRUD->recupNbVotesSemaineEnCoursParUtilisateurId($utilisateur->getId());
    $nbVotesRestants = 5 - $nbVotesSemaine;
    $motVotesCRUD = new MotVotes_CRUD($connexion);
    $motsPopulaires = $motVotesCRUD->recupTousLesMotsPopulairesDateDESC();
    $_SESSION['motsPopulaires'] = $motsPopulaires;
endif;
?>
    <div class="vote-section">
        <h2 class="section-title">
            📝 Mots proposés par la communauté
        </h2>

        <div class="votes-remaining">
            <strong><?= $nbVotesRestants?> votes restants</strong> cette semaine (renouvellement tous les lundis)
        </div>


        <div class="word-list" id="wordList">
        <?php include_once('afficherMotLexique.php'); ?>
        </div>
    </div>
