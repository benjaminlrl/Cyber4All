<?php
include_once('../lib/Connexion.php');
include_once('../lib/MotCle_CRUD.php');
include_once('../lib/MotCategorie_CRUD.php');
include_once('../lib/Categorie_CRUD.php');
include_once('../lib/MotCle.php');
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
endif;
?>
    <div class="vote-section">
        <h2 class="section-title">
            📝 Mots proposés par la communauté
        </h2>

        <div class="votes-remaining">
            <strong><?= $nbVotesRestants?> votes restants</strong> cette semaine (renouvellement tous les lundis)
        </div>

        <div class="search-box">
                <form action="communautaire.php"
                      method="POST"
                      class="form-recherche">
                    <input type="search"
                           class="recherche-input"
                           name="recherche"
                           maxlength="100"
                           value=""
                           placeholder="Explorez les termes clés de la cybersécurité !">
                    <button
                        type="submit"
                        class="recherche-bouton-soumission">Rechercher</button>
                </form>
        </div>
        <div class="word-list" id="wordList">
        <?php include_once('afficherMotLexique.php'); ?>
        </div>
    </div>
