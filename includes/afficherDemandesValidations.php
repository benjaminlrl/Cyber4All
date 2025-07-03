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
include_once('../includes/config.php');
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
            $crudMotCleValidation = new MotCleValidation_CRUD($connexion);
            $demandes = $crudMotCleValidation->recupToutesDemandesEnAttente();

        endif;
    endif;
    ?>

    <div class="compte-stats-placeholder demande">
        <div class="lexique-wrapper">
            <?php if(!empty($demandes)):
                foreach ($demandes as $demande):?>
                    <div class="lexique-container validation">
                        <div class="lexique__header">
                            <h3 class="lexique__title"
                                title="Nom du mot"><?= $demande->getMotCle()?></h3>
                            <?php if($demande->getStatut() === "en attente"): ?>
                                <form action="administration.php" method="POST">
                                    <?php // Générer un token unique pour ce formulaire
                                    $token = uniqid('vote_', true);
                                    $_SESSION['vote_tokens'][] = $token;?>
                                    <input type="hidden" name="vote_token" value="<?= $token //pour ne pas ressoumettre le
                                    // formulaire au refresh de la page?>">
                                    <input type="hidden" name="id_demande" value="<?= $demande->getIdMotcleValidation()?>"
                                           class="suppvote" title="Refuser la création du mot">
                                    <input type="submit" name="refuser" value="Refuser"
                                           class="suppvote" title="Refuser la création du mot">
                                    <input type="submit" name="valdier" value="valider"
                                           class="vote" title="Valider la création du mot">
                                </form>
                            <?php endif;?>
                        </div>
                        <p class="lexique__definition"
                           title="Définition du mot"><?= $demande->getDefinition() ?></p>
                    </div>
                <?php endforeach;
            endif;?>
        </div>
    </div>
<?php endif; ?>