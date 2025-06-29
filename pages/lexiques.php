<?php
include_once(__DIR__ . '/../lib/Connexion.php');
include_once(__DIR__ . '/../lib/MotCle_CRUD.php');
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
use lib\MotCategorie_CRUD;
use lib\Vote;
use lib\Vote_CRUD;
use lib\Utilisateur_CRUD;
use lib\Utilisateur;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('../enTete.html');
?>
<body id="top">
<?php
require_once("../includes/header.php");?>
<div class="container">
    <div class="container-recherche">
    <?php require_once("../includes/recherche.php");?>
    </div>
<?php require_once("../includes/afficherMotLexique.php");?>
</div>
<?php
require_once("../includes/footer.php");
?>
</body>
</html>