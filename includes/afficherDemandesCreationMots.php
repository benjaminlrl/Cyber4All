<?php
/**
 * AFFICHAGE DES MOTS DU LEXIQUE
 *
 * Ce fichier gère l'affichage des mots selon différents contextes :
 * - Lexique complet (glossaire.php)
 * - Mots populaires (communautaire.php)
 * - Recherche et filtrage par catégorie
 *
 * Fonctionnalités :
 * - Système de vote pour contributeurs
 * - Redirection intelligente selon la provenance
 * - Gestion des tokens anti-CSRF
 */
include_once('../lib/Connexion.php');
include_once('../lib/MotCle_CRUD.php');
include_once('../lib/MotCategorie_CRUD.php');
include_once('../lib/MotCleValidation_CRUD.php');
include_once('../lib/MotCleValidation.php');
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
use lib\MotCleValidation_CRUD;
use lib\MotCleValidation;
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

    if (isset($_SESSION['utilisateur'])):
        $utilisateur = $_SESSION['utilisateur'];

        if (is_a($connexion, "PDO")):
            $crudMotClevalidation = new MotCleValidation_CRUD($connexion);
            $demandes = $crudMotClevalidation->recupToutesDemandesParUtilisateurID($utilisateur->getid());

        endif;
    endif;
?>

    <div class="compte-stats-placeholder demande">
        <h3>Mes demandes de création de mot en cours</h3>
        <?php if(!empty($demandes)):
                foreach ($demandes as $demande):?>
                    <div class="lexique-container demande">
                        <div class="lexique__header demande">
                            <h3 class="lexique__title"
                            title="Nom du mot"><?= $demande->getMotCle() ?></h3>
                            <p class="lexique__definition"
                               title="Définition du mot"><?= $demande->getDefinition() ?></p>
                        </div>
                        <?php if($demande->getStatut() === "en attente"): ?>
                        <input type="submit" name="suppVote" value="En attente"
                               class="vote-disabled" title="Demande de création en attente" disabled>
                        <?php elseif($demande->getStatut() === "refuser"): ?>
                        <input value="Refuser"
                               class="suppvote" title="Demande de création refusée" disabled>
                    <?php else: ?>
                    <input value="Accépter"
                               class="vote" title="Demande de création acceptée" disabled>
                    <?php endif;?>
                    </div>
                <?php endforeach;
         endif;?>
    </div>
<?php endif; ?>