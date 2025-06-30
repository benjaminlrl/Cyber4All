<?php
/**
 * AFFICHAGE DES MOTS DU LEXIQUE
 *
 * Ce fichier gère l'affichage des mots selon différents contextes :
 * - Lexique complet (lexiques.php)
 * - Mots populaires (communautaire.php)
 * - Recherche et filtrage par catégorie
 *
 * Fonctionnalités :
 * - Système de vote pour contributeurs
 * - Redirection intelligente selon la provenance
 * - Gestion des tokens anti-CSRF
 */
include_once(__DIR__ . '/../lib/Connexion.php');
include_once(__DIR__ . '/../lib/MotCle_CRUD.php');
include_once(__DIR__ . '/../lib/MotCleValidation.php');
include_once(__DIR__ . '/../lib/MotCleValidation_CRUD.php');
include_once(__DIR__ . '/../lib/MotCle.php');
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
use lib\MotCle;
use lib\MotCategorie_CRUD;
use lib\MotCleValidation;
use lib\MotCleValidation_CRUD;
use lib\Vote;
use lib\Vote_CRUD;
use lib\Utilisateur_CRUD;
use lib\Utilisateur;

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

<?php
    if(isset($_GET['mot'])): //n'afficher que le mot passer en url
        $id_mot = $_GET['mot'];
        $mot = $crudMot->recupMotParId($id_mot);
        if($mot === null):?>
            <div class="lexique-container mot-seul">
                <h3 class="lexique__title mot-seul"
                    title="Aucun résultat">Aucun terme ne correspond a votre recherche...</h3>
            </div>
        <?php else:?>
            <div class="lexiques-wrapper mot-seul">
                <div class="lexique-container mot-seul">
                    <div class="lexique__header">
                        <h3 class="lexique__title mot-seul"
                            title="Nom du mot"><?= $mot->getMot() ?></h3>
                        <?php if(isset($utilisateur) && $utilisateur->getRole() === "contributeur"): ?>
                            <form action="communautaire.php" method="POST" class="btn-vote-communautaire">
                                <!-- Champ caché pour passer l'ID du mot -->
                                <input type="hidden" name="id_mot" value="<?= $mot->getId() ?>">
                                <?php // Si on n'est PAS sur la page communautaire (pas de $motsPopulaires)
                                // alors on ajoute l'input pour rediriger vers lexiques.php après vote.
                                if(!isset($motsPopulaires)):?>
                                    <!-- Permet d'obtenir la provenance de la soumission du formulaire -->
                                    <input type="hidden" name="from_lexique" value="true">
                                <?php
                                endif;
                                // Générer un token unique pour ce formulaire
                                $token = uniqid('vote_', true);
                                $_SESSION['vote_tokens'][] = $token;?>
                                <input type="hidden" name="vote_token" value="<?= $token //pour ne pas ressoumettre le
                                // formulaire au refresh de la page?>">


                                <?php
                                $voteCRUD = new Vote_CRUD($connexion);
                                // Récupère tous les id des mots votés depuis lundi
                                $id_mots = $voteCRUD->recupMotsVotesSemaineEnCoursParUtilisateurId($utilisateur->getId());
                                // récupère le nombre de votes effectué durant la semaine
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
                        <?php endif;?>
                    </div>
                    <div class="lexique_categories mot-seul">
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
                    <p class="lexique__definition"
                       title="Définition du mot"><?= $mot->getDefinition() ?></p>
                </div>
            </div>
<?php  endif;
    else:;//si pas de mot en url alors son affiche tous le lexique?>
<div class="lexiques-wrapper">
    <?php if (!empty($mots)):
        if(!isset($_GET['categorie'])):?>
        <div class="lexique-container-static">
            <div class="lexiques__title-static">
                <h3 class="lexique__categorie-static"
                    data-category="8">
                    <?= isset($motsPopulaires) ? "Les favoris de la communauté": "Lexique de la cybersécurité";?>
                    <?= !empty($recherche) ? " - ".$recherche : "" ;//afficher la recherche?>
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
            if(isset($motsPopulaires)):
                $mots = array_slice($motsPopulaires, 0, 10);
                //vérifie si on est sur la page comunautaire, la variable $motsPuplairs
                // ne s'instancie que dans lapage communautaire - limite aux 10 premiers
            endif;
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
                        <?php // Si on n'est PAS sur la page communautaire (pas de $motsPopulaires)
                              // alors on ajoute l'input pour rediriger vers lexiques.php après vote.
                        if(!isset($motsPopulaires)):?>
                            <!-- Permet d'obtenir la provenance de la soumission du formulaire -->
                            <input type="hidden" name="from_lexique" value="true">
                        <?php
                        endif;
                        // Générer un token unique pour ce formulaire
                        $token = uniqid('vote_', true);
                        $_SESSION['vote_tokens'][] = $token;?>
                        <input type="hidden" name="vote_token" value="<?= $token //pour ne pas ressoumettre le
                        // formulaire au refresh de la page?>">


                        <?php
                        $voteCRUD = new Vote_CRUD($connexion);
                        // Récupère tous les id des mots votés depuis lundi
                        $id_mots = $voteCRUD->recupMotsVotesSemaineEnCoursParUtilisateurId($utilisateur->getId());
                        // récupère le nombre de votes effectué durant la semaine
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
            <div class="btn-more">
                    <a href="lexiques.php?mot=<?= $mot->getId() ?>"
                       title="En savoir plus">
                        En savoir plus <i class="fa-solid fa-right"></i>
                    </a>
            </div>

        </div>
    <?php endforeach; ?>
<?php  endif;
if (empty($mots)):?>
    <div class="lexique-container">
    <?php if(isset($motsPopulaires) && !isset($_POST['recherche'])):?>
        <h3 class="lexique__title"
            title="Aucun résultat">Sois le premier de la communauté à voter pour un terme cette semaine !</h3>
        <br>
        <a href="lexiques.php" class="btn-connexion">Aller voter
            <i class="fa-solid fa-shield"></i></a>
    <?php else: ?>
        <h3 class="lexique__title"
            title="Aucun résultat">Aucun terme ne correspond a votre recherche...</h3>
    <?php endif; ?>
        </div>
    <?php endif; ?>
    </div>
<?php endif; ?>
<?php endif; ?>
