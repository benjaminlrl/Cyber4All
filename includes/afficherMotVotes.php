<?php
include_once('../lib/Connexion.php');
include_once('../lib/MotCle_CRUD.php');
include_once('../lib/MotCategorie_CRUD.php');
include_once('../lib/Categorie_CRUD.php');
include_once('../lib/MotCle.php');
include_once('../lib/Vote.php');
include_once('../lib/Vote_CRUD.php');
include_once('../lib/MotVotes_CRUD.php');
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
    $categorieId = null;
    if(isset($_SESSION['utilisateur'])):
        $utilisateur = $_SESSION['utilisateur'];
    endif;

    if(!empty($_POST['recherche'])):
        $recherche = $_POST['recherche'];
    endif;

    if (is_a($connexion, "PDO")):
        $motVotes_CRUD = new MotVotes_CRUD($connexion);
        $crudCategorie = new Categorie_CRUD($connexion);
        $mots = $motVotes_CRUD->recupTousLesMotsVotesSemaineEnCoursParUtilisateurId($utilisateur->getId());
        if(!empty($mots)):
            $crudMotCategorie = new MotCategorie_CRUD($connexion);
            foreach ($mots as $mot):
                //récupere un tableau associatif avec tous le sid de categorie par mot
                $listeCategories[$mot->getId()] = $crudMotCategorie->recupToutesLesCategoriesDunMot($mot);
            endforeach;
        endif;
    endif;?>
    <div class="lexique-container-static">
        <div class="lexiques__title-static">
            <h3 class="lexique__categorie-static statistique"
                data-category="14">Mots votés de la semaine
            </h3>
        </div>
    </div>
    <?php foreach ($mots as $mot):?>
        <div class="lexique-container">
            <div class="lexique__header">
                <h3 class="lexique__title"
                title="Nom du mot"><?= $mot->getMot() ?></h3>
            </div>
            <?php if(isset($utilisateur) && $utilisateur->getRole() === "contributeur"): ?>
            <form action="communautaire.php" method="POST" class="btn-vote-communautaire">
                <!-- Champ caché pour passer l'ID du mot -->
                <input type="hidden" name="id_mot" value="<?= $mot->getId() ?>">

                <?php
                // Générer un token unique pour ce formulaire
                $token = uniqid('vote_', true);
                $_SESSION['vote_tokens'][] = $token;?>
                <input type="hidden" name="vote_token" value="<?= $token ?>">

                <?php
                $voteCRUD = new Vote_CRUD($connexion);
                // Récupère tous les id des mots votés depuis lundi
                $id_mots = $voteCRUD->recupMotsVotesSemaineEnCoursParUtilisateurId($utilisateur->getId());
                $nbVoteEnCours = $voteCRUD->recupNbVotesSemaineEnCoursParUtilisateurId($utilisateur->getId());

                //L'utilisateur a déjà voté pour ce mot
                if (in_array($mot->getId(), $id_mots)): ?>
                    <input type="submit" name="suppVote" value="Supprimer mon vote"
                           class="suppvote" title="Supprimer mon vote">
                    <input type="hidden" value="<?= $voteCRUD->recupUnVoteSemaineEnCours(
                        $utilisateur->getId(), $mot->getId())->getIdVote() ?>"
                           name="suppVote" class="suppvote" title="Supprimer mon vote">
                <?php endif; ?>
            </form>
    <?php endif; ?>
        </div>
<?php
    endforeach;
endif; ?>