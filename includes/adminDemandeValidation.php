<?php
include_once('../includes/config.php');
use lib\Connexion;
use lib\MotCleValidation_CRUD;
use lib\Vote_CRUD;
use lib\MotVotes_CRUD;

$id_session = session_id();
if ($id_session):
    $pdo = new Connexion("user");
    $connexion = $pdo->setConnexion();
    if(isset($_SESSION['utilisateur'])):
        $utilisateur = $_SESSION['utilisateur'];

        $motCleValidationCRUD = new MotCleValidation_CRUD($connexion);
        $nbDemandesRestantes = count($motCleValidationCRUD->recupToutesDemandesEnAttente());
?>
    <div class="vote-section">
        <div class="header-populaire">
            <h2 class="section-title">
                 Valider les créations de la communauté
            </h2>
            <a href="ajoutLexique.php" class="btn-connexion">Ajouter un mot  <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="votes-remaining">
            <strong><?= $nbDemandesRestantes?> termes</strong> en attente de votre validation
        </div>


        <div class="word-list" id="wordList">
        <?php include_once('afficherDemandesValidations.php'); ?>
        </div>
    </div>
<?php endif; ?>
<?php endif; ?>
