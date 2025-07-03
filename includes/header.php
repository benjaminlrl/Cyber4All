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
    if(isset($_SESSION['utilisateur'])):
        $utilisateur = $_SESSION['utilisateur'];
    endif;
endif
?>

<header id="header-site" class="header-site">
    <div class="header__wrapper">
        <div class="header__marque">
            <img src="/Cyber4All_web/images/logos/CYBER4ALL/cyber4all_nobg_notxt.png"
                 alt="Logo CYBER4ALL"
                 class="header__logo">
            <a href="/Cyber4All_web/pages/accueil.php"
               title="Retour à l'accueil du site CYBER4ALL"
               class="header__lien-marque">
                <h1 class="header__titre">CYBER<span class="header__titre--accent">4ALL</span></h1>
            </a>
        </div>
        <!-- Bouton hamburger pour mobile -->
        <button class="menu-hamburger" id="menu-hamburger" aria-label="Menu de navigation">
            <span class="menu-hamburger__ligne"></span>
            <span class="menu-hamburger__ligne"></span>
            <span class="menu-hamburger__ligne"></span>
        </button>

        <nav class="header__navigation" id="header-navigation" role="navigation" aria-label="Navigation principale">
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
                        <a href="glossaire.php?categorie=<?= $categorie->getId()?>"
                           class="dropdown-menu__item"
                           title="Tout le lexique de la Catégorie <?= $categorie->getNom() ?>"
                           role="menuitem">
                            <?= $categorie->getNom() ?>
                        </a>
                        <?php endforeach;?>
                    </div>
                </li>
                <li class="menu-nav__item">
                    <a href="communautaire.php"
                       title="Lexique les plus importants aux yeux de la communauté"
                       class="menu-nav__lien">
                        Communautaire
                    </a>
                </li>
                <li class="menu-nav__item">
                    <a href="glossaire.php"
                       title="Tout le lexique de la cybersécurité"
                       class="menu-nav__lien">
                        Glossaire
                    </a>
                </li>
                <?php if(isset($utilisateur) && $utilisateur->getRole() === "admin"):?>
                    <li class="menu-nav__item">
                        <a href="administration.php"
                           title="Tout le lexique de la cybersécurité"
                           class="menu-nav__lien">
                            Administration
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="header__connexion">
            <?php if(!isset($_SESSION['utilisateur'])):?>
            <a href="identification.php"
               class="btn-connexion"
               title="Se connecter ou s'inscrire">
                Connexion
            </a>
            <?php endif; ?>
            <?php if(isset($_SESSION['utilisateur'])):?>
                <a href="monCompte.php"
                   class="btn-connexion"
                   title="Accéder à mon compte">
                    Mon compte
                </a>
            <?php endif; ?>
            <?php if(isset($_SESSION['utilisateur'])):?>
                    <a href="accueil.php?deconnexion"
                            class="btn-connexion">Se déconnecter</a>
        <?php endif; ?>
            </div>
    </div>
</header>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburger = document.getElementById('menu-hamburger');
        const navigation = document.getElementById('header-navigation');

        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navigation.classList.toggle('active');
        });

        // Fermer le menu quand on clique sur un lien
        const menuLinks = document.querySelectorAll('.menu-nav__lien');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                navigation.classList.remove('active');
            });
        });
    });</script>
<?php endif ?>