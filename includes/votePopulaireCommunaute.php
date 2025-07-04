<?php
include_once('../includes/config.php');
use lib\Connexion;
use lib\Vote_CRUD;
use lib\MotVotes_CRUD;

$id_session = session_id();
if ($id_session):
    $pdo = new Connexion("user");
    $connexion = $pdo->setConnexion();
    if(isset($_SESSION['utilisateur'])):
        $utilisateur = $_SESSION['utilisateur'];

        //récupérer le nombre de vote de la semaine en cours par l'utilisateur
        $voteCRUD = new Vote_CRUD($connexion);
        $nbVotesSemaine = $voteCRUD->recupNbVotesSemaineEnCoursParUtilisateurId($utilisateur->getId());
        $nbVotesRestants = 5 - $nbVotesSemaine;
        $motVotesCRUD = new MotVotes_CRUD($connexion);
        $motsPopulaires = $motVotesCRUD->recupTousLesMotsPopulairesDateDESC();
        $_SESSION['motsPopulaires'] = $motsPopulaires;
?>
    <div class="vote-section">
        <div class="header-populaire">
            <h2 class="section-title">
                📝 Mots proposés par la communauté
            </h2>
            <a href="ajoutLexique.php" class="btn-connexion">Ajouter un mot  <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="votes-remaining">
            <strong><?= $nbVotesRestants?> votes restants</strong> cette semaine (renouvellement tous les lundis)
        </div>


        <div class="word-list" id="wordList">
        <?php include_once('afficherMotLexique.php'); ?>
        </div>
    </div>
<?php endif; ?>
<?php endif; ?>
