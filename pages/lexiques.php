<?php
include_once('../index.html');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<body id="top">
<?php
require_once("../includes/header.php");
require_once("../includes/recherche.php");
require_once("../includes/afficherMotLexique.php");
require_once("../includes/footer.php");
?>
</body>
</html>