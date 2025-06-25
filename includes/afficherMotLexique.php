<?php
include_once(__DIR__ . '/../lib/Connexion.php');
include_once(__DIR__ . '/../lib/MotCle_CRUD.php');
include_once(__DIR__ . '/../lib/MotCategorie_CRUD.php');
include_once(__DIR__ . '/../lib/Categorie_CRUD.php');
include_once(__DIR__ . '/../lib/MotCle.php');

use lib\Categorie;
use lib\Categorie_CRUD;
use lib\Connexion;
use lib\MotCle;
use lib\MotCle_CRUD;
use lib\MotCategorie_CRUD;


$id_session = session_id();
if ($id_session):
    $pdo = new Connexion("user");
    $connexion = $pdo->setConnexion();
    $categorieId = null;
    if (is_a($connexion, "PDO")):
        $crudMot = new MotCle_CRUD($connexion);
        if(isset($_POST['recherche'])){
            $recherche = htmlspecialchars(trim($_POST['recherche']));
            $mots = $crudMot->recupTousLesMotsAvecFiltre($recherche);
        }else if(isset($_GET['categorie']) && !empty($_GET['categorie'])) {
            $categorieId = (int) $_GET['categorie'];
            $MotCategorieCRUD= new MotCategorie_CRUD($connexion);
            $mots = $MotCategorieCRUD->recupTousLesMotsDuneCategorieParID($categorieId);
        }else{
            $mots = $crudMot->recupTousLesMots();
        }
        $crudMotCategorie = new MotCategorie_CRUD($connexion);
        foreach ($mots as $mot):
            $listeCategories[$mot->getId()] = $crudMotCategorie->recupToutesLesCategoriesDunMot($mot);
        endforeach;
    endif ?>

<div class="lexiques-wrapper">
    <?php if (!empty($mots)):
    foreach ($mots as $mot):?>
        <div class="lexique-container">
            <h3 class="lexique__title"
                title="Nom du mot"><?= $mot->getMot() ?></h3>
            <p class="lexique__definition"
               title="Définition du mot"><?= $mot->getDefinition() ?></p>
            <div class="lexique_categories">
                <?php
                $categoriesDuMot = $listeCategories[$mot->getId()] ?? [];
                foreach ($categoriesDuMot as $categorie):
                    // Debug : voir ce que retourne getId()
                    echo "<!-- Debug: " . var_export($categorie->getId(), true) . " -->";
                    echo "<!-- Nom: " . $categorie->getNom() . " -->";
                    ?>

                 <a href="lexiques.php?categorie=<?= $categorie->getId()?>" class="lexique__categorie"
                   title="Catégorie du mot" data-category="<?= $categorie->getId() ?>">
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
