<?php
include_once(__DIR__ . '/../includes/config.php');
use lib\Connexion;
use lib\MotCle;
use lib\MotCleValidation_CRUD;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_session = session_id();
if ($id_session):
    $pdo = new Connexion("user");
    $connexion = $pdo->setConnexion();
    if (isset($_SESSION['utilisateur']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajout_token'])):
        $utilisateur = $_SESSION['utilisateur'];
        $submitted_token = $_POST['ajout_token'];

            // Vérifier si le token existe dans la session
            if (!isset($_SESSION['ajout_tokens']) || !in_array($submitted_token, $_SESSION['ajout_tokens'])):
                // Token invalide ou déjà utilisé
                header('Location: communautaire.php?erreur=token_invalide');
                exit();
            endif;

            // Supprimer le token de la session (ne peut plus être réutilisé)
            $_SESSION['ajout_tokens'] = array_filter($_SESSION['ajout_tokens'], function($token) use ($submitted_token) {
                return $token !== $submitted_token;
            });

            if((isset($_POST['nouvMot']) && !empty($_POST['nouvMot'])) &&
                (isset($_POST['nouvDefinition']) && !empty($_POST['nouvDefinition']))):
                $nouvMot = htmlspecialchars(trim($_POST['nouvMot']));
                $nouvDefinition = htmlspecialchars(trim($_POST['nouvDefinition']));
                $motCleValidation = new MotCle(
                        $nouvMot,
                        $nouvDefinition);
                $motCleValidationCRUD = new MotCleValidation_CRUD($connexion);
                $demandeValidation = $motCleValidationCRUD->creerDemandeValidation($utilisateur, $motCleValidation);
                if($demandeValidation):
                    // demandeCreation = succes si la demande a bien ete envoyé
                    header("location: communautaire.php?demandeCreation=succes");
                    exit;
                else:
                    // demandeCreation = echec si la demande a bien ete envoyé
                    header("location: communautaire.php?demandeCreation=echec");
                    exit;
                endif;
            endif;
        endif;


include_once('../enTete.html');
?>
<body id="top">
<?php require_once("../includes/header.php");?>
<section>
    <?php require_once("../includes/formCreationMot.php");?>
</section>
<?php require_once("../includes/footer.php"); ?>
</body>
</html>
<?php endif; ?>
