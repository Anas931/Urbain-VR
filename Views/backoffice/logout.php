<?php
// filepath: c:\xampp\htdocs\ttt\Views\backoffice\logout.php
session_start();
session_unset();
session_destroy();
header("Location: admin-login.php");
exit();
?>