<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_POST['deconnexion'])){
    session_destroy();
    session_start();
}
include_once('../enTete.html');
?>
<body id="top">
<?php
require_once("../includes/header.php");
require_once("../includes/recherche.php");
require_once("../includes/carroussel_definitions.php");
require_once("../includes/footer.php");
?>
</body>
</html>
