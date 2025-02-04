<?php
session_start(); // Începe sesiunea
session_destroy(); // Distruge toate datele din sesiune
header("Location: index.php"); // Redirecționează către pagina de login
exit();
?>
