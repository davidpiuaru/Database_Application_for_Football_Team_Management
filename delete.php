<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Echipe_de_fotbal";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

// Verificăm dacă tabela și ID-ul sunt specificate
if (isset($_GET['table']) && isset($_GET['id'])) {
    $selectedTable = $_GET['table'];
    $id = intval($_GET['id']); // Convertim ID-ul în număr întreg pentru siguranță

    // Obținem cheia primară a tabelului
    $primaryKey = getPrimaryKey($selectedTable);

    // Ștergem rândul din tabel
    $deleteQuery = "DELETE FROM $selectedTable WHERE $primaryKey = $id";

    if ($conn->query($deleteQuery)) {
        echo "Rândul a fost șters cu succes!";
        header("Location: dashboard.php?table=$selectedTable");
        exit();
    } else {
        echo "Eroare la ștergerea rândului: " . $conn->error;
    }
} else {
    echo "Tabelul sau ID-ul nu a fost specificat.";
}

// Funcție pentru a obține cheia primară a unui tabel
function getPrimaryKey($table) {
    global $conn;
    $query = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['Column_name'];
}
?>
