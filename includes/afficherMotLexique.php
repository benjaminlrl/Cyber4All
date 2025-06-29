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
    $categorieId = null;

    if(!empty($_POST['recherche'])):
        $recherche = $_POST['recherche'];
    endif;

    if (is_a($connexion, "PDO")):
        $crudMot = new MotCle_CRUD($connexion);
        $crudCategorie = new Categorie_CRUD($connexion);

        if(isset($_POST['recherche'])){
            $recherche = htmlspecialchars(trim($_POST['recherche']));
            $mots = $crudMot->recupTousLesMotsAvecFiltre($recherche);
        }
        else if(isset($_GET['categorie']) && !empty($_GET['categorie'])) {
            $categorieId = (int) $_GET['categorie'];
            $MotCategorieCRUD= new MotCategorie_CRUD($connexion);
            $mots = $MotCategorieCRUD->recupTousLesMotsDuneCategorieParID($categorieId);
            $categorie = $crudCategorie->recupCategorieParID($categorieId);
        }
        else{
            $mots = $crudMot->recupTousLesMots();
        }
        
        $crudMotCategorie = new MotCategorie_CRUD($connexion);
        foreach ($mots as $mot):
            //récupere un tableau associatif avec tous le sid de categorie par mot
            $listeCategories[$mot->getId()] = $crudMotCategorie->recupToutesLesCategoriesDunMot($mot);
        endforeach;
        if(isset($_SESSION['utilisateur'])):
            $utilisateur = $_SESSION['utilisateur'];
        endif;
    endif ?>

<div class="lexiques-wrapper">
    <?php if (!empty($mots)):
        if(!isset($_GET['categorie'])):?>
        <div class="lexique-container-static">
            <div class="lexiques__title-static">
                <h3 class="lexique__categorie-static"
                    data-category="8">Lexique de la cybersécurité
                    <?= !empty($recherche) ? " - ".$recherche : "" ;?>
                </h3>
            </div>
        </div>
            <?php endif;
                if(isset($_GET['categorie']) && !empty($_GET['categorie'])):
            ?>
        <div class="lexique-container-static">
            <div class="lexiques__title-static">
                <h3 class="lexique__categorie-static"
                    data-category="<?= $categorie->getId() ?>">
                    Lexique de la catégorie : <?= $categorie->getNom()?></h3>
            </div>
        </div>
           <?php endif;
    foreach ($mots as $mot):
                ?>
        <div class="lexique-container">
            <div class="lexique__header">
                <h3 class="lexique__title"
                title="Nom du mot"><?= $mot->getMot() ?></h3>
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

                        // CAS 1: L'utilisateur a déjà voté pour ce mot
                        if (in_array($mot->getId(), $id_mots)): ?>
                            <input type="submit" name="suppVote" value="Supprimer mon vote"
                                   class="suppvote" title="Supprimer mon vote">
                            <input type="hidden" value="<?= $voteCRUD->recupUnVoteSemaineEnCours(
                                $utilisateur->getId(), $mot->getId())->getIdVote() ?>"
                               name="suppVote" class="suppvote" title="Supprimer mon vote">

                        <?php // CAS 2: L'utilisateur n'a pas voté pour ce mot ET n'a pas atteint la limite
                        elseif ($nbVoteEnCours < LIMITEVOTE): ?>
                        <input type="submit" value="voter" name="voter" class="vote" title="Voter pour ce mot">

                        <?php // CAS 3: L'utilisateur n'a pas voté pour ce mot MAIS a atteint sa limite
                        else: ?>
                        <input type="submit" value="Limite atteinte"
                               class="vote-disabled" disabled title="Vous avez atteint votre limite de votes">

                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
            <p class="lexique__definition"
               title="Définition du mot"><?= $mot->getDefinition() ?></p>
            <div class="lexique_categories">
                <?php
                $categoriesDuMot = $listeCategories[$mot->getId()] ?? [];
                foreach ($categoriesDuMot as $categorie):?>
                 <a href="lexiques.php?categorie=<?= $categorie->getId() ?>"
                    class="lexique__categorie"
                    title="Catégorie du mot"
                    data-category="<?= $categorie->getId() ?>">
                    <?= $categorie->getNom() ?>
                </a>
                <?php endforeach; ?>
            </div>

        </div>
    <?php endforeach; ?>
<?php  endif;
if (empty($mots)): ?>
    <div class="lexique-container">
        <h3 class="lexique__title"
            title="Aucun résultat">Aucun terme ne correspond à votre recherche.</h3>
    </div>
<?php endif; ?>
</div>
<?php endif; ?>
