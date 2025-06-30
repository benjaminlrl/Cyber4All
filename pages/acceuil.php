<?php
include_once ('../lib/BandeauNotification.php');
use \lib\BandeauNotification;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//gestion de la deconnnexion
if(isset($_GET['deconnexion'])){
    session_destroy();
    session_start();
    $notification = new BandeauNotification("SUCCES",
            "Déconnexion réussi",
            "Hâte de vous revoir !");
    $notification = $notification->afficherNotification($notification);
}

//Gestion des notifications
if(isset($_GET['connexion']) && isset($_SESSION['utilisateur'])):
    $notification = new BandeauNotification("SUCCES",
        "Connexion réussi",
        "C'est l'heure d'en apprendre plus sur la cybersécurité !");
    $notification = $notification->afficherNotification($notification);
endif;
if(isset($_GET['connexion']) && !isset($_SESSION['utilisateur'])):
    header("location: identification.php");
endif;
if(isset($_GET['inscription']) && isset($_SESSION['utilisateur'])):
    $notification = new BandeauNotification("SUCCES",
        "Incription réussi",
        "Nous sommes très heureux de vous voir rejoindre la communauté de CYBER4ALL, explorez, 
        votez et contribuez pleinement à l'évolution de notre site !");
    $notification = $notification->afficherNotification($notification);
endif;
include_once('../enTete.html');
?>
<body id="top">
<?php
require_once("../includes/header.php");?>
<?= isset($notification)? $notification : ""; ?>
<section id="recherche">
    <div class="container-recherche">
<?php require_once("../includes/recherche.php"); ?>
    </div>
</section>
<?php
require_once("../includes/carroussel_definitions.php");
require_once("../includes/footer.php");
?>
</body>
</html>
