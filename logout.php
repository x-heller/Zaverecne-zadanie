
<?php

session_start();
$_SESSION["loggedin"] = false;
// Uvolnenie session premennych. Tieto dva prikazy su ekvivalentne.
$_SESSION = array();
session_unset();

// Vymazanie session.
session_destroy();

// Presmerovanie na hlavnu stranku.
header("location: login.php");
exit;