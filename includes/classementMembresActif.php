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
    if(isset($_POST['utilisateur'])):
        $utilisateur = $_POST['utilisateur'];
    endif;
    $votesCRUD = new Vote_CRUD($connexion);
    $utilisateurCRUD = new Utilisateur_CRUD($connexion);
    $classements = $utilisateurCRUD->recupClassementVotes();

?>
<div class="leaderboard">
            <h2 class="section-title">
🏆 Classement des membres les plus actifs
</h2>
        <?php $place = 0;
            foreach ($classements as $classement):
            $place++;?>
            <div class="leaderboard-list">
                <div class="leaderboard-item">
                    <div class="rank"><?= $place?></div>
                    <div class="user-avatar"><?= substr($classement->getPseudo(), 0,2) ?></div>
                    <div class="user-details">
                        <div class="user-name"><?= $classement->getPseudo() ?></div>
                        <div class="user-votes"><?= //récupère le nombre de vote total
                            $votesCRUD->recupVotesTotalParUtilisateurId($classement->getId());?></div>
                    </div>
                </div>
        <?php endforeach; ?>
            </div>
        </div>
<?php endif; ?>