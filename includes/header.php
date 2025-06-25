<?php
include_once(__DIR__ . '/../lib/Connexion.php');
include_once(__DIR__ . '/../lib/MotCle_CRUD.php');
include_once(__DIR__ . '/../lib/MotCle.php');
include_once(__DIR__ . '/../lib/Categorie.php');
include_once(__DIR__ . '/../lib/Categorie_CRUD.php');

use lib\Connexion;
use lib\MotCle;
use lib\MotCle_CRUD;
use lib\Categorie;
use lib\Categorie_CRUD;


$id_session = session_id();
if ($id_session):

$pdo = new Connexion("user");
$connexion = $pdo->setConnexion();
if (is_a($connexion, "PDO")):
    $crudCategorie = new Categorie_CRUD($connexion);
    $categories = $crudCategorie->recupToutesLesCategories();
endif
?>

<header id="header-site" class="header-site">
    <div class="header__wrapper">
        <div class="header__marque">
            <img src="/Cyber4All_web/images/logos/CYBER4ALL/cyber4all_nobg_notxt.png"
                 alt="Logo CYBER4ALL"
                 class="header__logo">
            <a href="/Cyber4All_web/pages/index.php"
               title="Retour à l'accueil du site CYBER4ALL"
               class="header__lien-marque">
                <h1 class="header__titre">CYBER<span class="header__titre--accent">4ALL</span></h1>
            </a>
        </div>

        <nav class="header__navigation" role="navigation" aria-label="Navigation principale">
            <ul class="menu-nav">
                <li class="menu-nav__item menu-nav__item--dropdown">
                    <a href="#"
                       title="Catégories du lexique de la cybersécurité"
                       class="menu-nav__lien menu-nav__lien--dropdown"
                       aria-haspopup="true"
                       aria-expanded="false">
                        Catégories
                    </a>
                    <div class="dropdown-menu" role="menu">
                        <?php foreach ($categories as $categorie):?>
                        <a href="lexiques.php?categorie=<?= htmlspecialchars($categorie->getId())?>"
                           class="dropdown-menu__item"
                           title="Tout le lexique de la Catégorie <?= $categorie->getNom() ?>"
                           role="menuitem">
                            <?= $categorie->getNom() ?>
                        </a>
                        <?php endforeach;?>
                    </div>
                </li>
                <li class="menu-nav__item">
                    <a href="#"
                       title="Lexique les plus importants aux yeux de la communauté"
                       class="menu-nav__lien">
                        Les plus populaires
                    </a>
                </li>
                <li class="menu-nav__item">
                    <a href="lexiques.php"
                       title="Tout le lexique de la cybersécurité"
                       class="menu-nav__lien">
                        Lexique
                    </a>
                </li>
            </ul>
        </nav>

        <div class="header__connexion">
            <a href="#"
               class="btn-connexion"
               title="Se connecter ou s'inscrire">
                Connexion
            </a>
        </div>
    </div>
</header>
<?php endif ?>