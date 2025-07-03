<?php
include_once('../includes/config.php');
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
        $voteCRUD = new Vote_CRUD($connexion);
        $nbVoteTotal = $voteCRUD->recupVotesTotalParUtilisateurId($utilisateur->getId());
        $nbVotesSemaine = $voteCRUD->recupNbVotesSemaineEnCoursParUtilisateurId($utilisateur->getId());
        ?>
        <div class="user-stats">
            <h2 class="section-title">
                👤 Vos statistiques
            </h2>

            <div class="user-info">
                <div class="username"><?= $utilisateur->getPseudo() ?></div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="stat-number"><?= $nbVoteTotal?></span>
                        <span class="stat-label">Votes total</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number"><?= $nbVotesSemaine?></span>
                        <span class="stat-label">Cette semaine</span>
                    </div>
                </div>
            </div>

            <div class="word-list statistique" id="wordList">
                <?php include_once('afficherDemandeTraitees.php'); ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
