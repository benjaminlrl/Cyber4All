<?php
include_once(__DIR__ . '/../includes/config.php');

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