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

// Funcție pentru a obține cheia primară a unui tabel
function getPrimaryKey($table) {
    global $conn;
    $query = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['Column_name'];
}

// Logica pentru ștergere
if (isset($_GET['delete_table']) && isset($_GET['delete_id'])) {
    $selectedTable = $_GET['delete_table'];
    $id = intval($_GET['delete_id']); // Convertim ID-ul în număr întreg pentru siguranță
    $primaryKey = getPrimaryKey($selectedTable);

    // Ștergem rândul specificat
    $deleteQuery = "DELETE FROM $selectedTable WHERE $primaryKey = $id";
    if ($conn->query($deleteQuery)) {
        $_SESSION['message'] = "Rândul a fost șters cu succes!";
    } else {
        $_SESSION['message'] = "Eroare la ștergerea rândului: " . $conn->error;
    }

    // Redirecționăm înapoi la dashboard pentru tabelul curent
    header("Location: dashboard.php?table=$selectedTable");
    exit();
}

// Obține lista de tabele
$sql = "SHOW TABLES";
$result = $conn->query($sql);

// Variabila pentru afișarea conținutului tabelului selectat
$tableContent = "";
if (isset($_GET['table'])) {
    $selectedTable = $_GET['table']; // Tabelul selectat
    $query = "SELECT * FROM $selectedTable"; // Interogare pentru conținut
    $tableResult = $conn->query($query);

    // Creăm conținutul tabelului dinamic
    if ($tableResult && $tableResult->num_rows > 0) {
        $tableContent = "<table><thead><tr>";
        // Afișăm antetul tabelului (numele coloanelor)
        $columns = $tableResult->fetch_fields();
        foreach ($columns as $col) {
            $tableContent .= "<th>" . htmlspecialchars($col->name) . "</th>";
        }
        $tableContent .= "<th>Acțiuni</th>"; // Adăugăm o coloană pentru butonul de acțiuni
        $tableContent .= "</tr></thead><tbody>";

        // Afișăm datele din tabel
        while ($row = $tableResult->fetch_assoc()) {
            $tableContent .= "<tr>";
            foreach ($row as $value) {
                $tableContent .= "<td>" . htmlspecialchars($value) . "</td>";
            }
            // Adăugăm linkuri pentru editare și ștergere
            $primaryKey = getPrimaryKey($selectedTable);
            $tableContent .= '<td class="action-buttons">
                <a href="edit.php?table=' . htmlspecialchars($selectedTable) . '&id=' . $row[$primaryKey] . '" class="edit">Editează</a>
                <a href="dashboard.php?delete_table=' . htmlspecialchars($selectedTable) . '&delete_id=' . $row[$primaryKey] . '" 
                   class="delete" onclick="return confirm(\'Ești sigur că vrei să ștergi acest rând?\');">Șterge</a>
            </td>';
            $tableContent .= "</tr>";
        }
        $tableContent .= "</tbody></table>";
    } else {
        $tableContent = "<p>Tabelul selectat nu conține date.</p>";
    }

}


?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Echipe de Fotbal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .button-list {
            margin: 20px 0;
        }
        .button-list form {
            display: inline-block;
            margin-right: 10px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin: 20px 0;
            color: green;
        }
        .action-buttons a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            color: white;
            font-size: 14px;
            margin-right: 5px;
            display: inline-block;
        }
        .action-buttons a.edit {
            background-color: #28a745; /* Verde pentru editare */
        }
        .action-buttons a.edit:hover {
            background-color: #218838;
        }
        .action-buttons a.delete {
            background-color: #dc3545; /* Roșu pentru ștergere */
        }
        .action-buttons a.delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Bine ai venit, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Aici poți accesa informațiile despre echipele de fotbal.</p>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <h2>Lista tabelelor disponibile</h2>
    <div class="button-list">
        <?php
        // Generăm butoane pentru fiecare tabel
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array()) {
                $tableName = $row[0];
                // Exclude tabelul "utilizatori"
                if ($tableName === 'utilizatori') {
                    continue;
                }
                echo '<form method="get" action="">';
                echo '<button type="submit" name="table" value="' . htmlspecialchars($tableName) . '">' . htmlspecialchars($tableName) . '</button>';
                echo '</form>';
            }
        } else {
            echo "<p>Nu există tabele în baza de date.</p>";
        }
        ?>
    </div>


    <h2>Conținutul tabelului selectat</h2>
    <?php
    // Afișăm conținutul tabelului selectat
    echo $tableContent;
    ?>

    <h2>Acțiuni</h2>


    <!-- Buton Adauga un nou rand -->
    <?php if (isset($selectedTable)): ?>
        <form action="add.php" method="get">
            <input type="hidden" name="table" value="<?php echo htmlspecialchars($selectedTable); ?>">
            <button type="submit">Adaugă un nou rând</button>
        </form>
    <?php endif; ?>

    <br>


<!-- Buton pentru căutarea studenților -->
<form action="search_students.php" method="get">
    <button type="submit">Căutare Studenți după Antrenor</button>
</form>

    <br>

    <!-- Buton pentru a merge la pagina cu studenți la facultate -->
    <form method="get" action="students_by_faculty.php">
        <button type="submit">Studenți la Facultate</button>
    </form>

    <br>

    <form action="meciuri_pe_data.php" method="get">
        <button type="submit">Meciuri pe data</button>
    </form>

    <br>

    <form action="meciuri_echipa.php" method="get">
        <button type="submit">Meciuri ale unei echipe</button>
    </form>

    <br>

    <form action="facultate_meciuri.php" method="get">
        <button type="submit">Facultatea cu cele mai multe meciuri</button>
    </form>

    <br>

    <form action="studenti_putine_meciuri.php" method="get">
        <button type="submit">Studenți cu cele mai puține meciuri</button>
    </form>

    <br>

    <form action="antrenori_echipe.php" method="get">
        <button type="submit">Afișează Antrenori și Echipe</button>
    </form>

    <br>

    <form action="echipe_stadion.php" method="get">
        <button type="submit">Echipe pe Stadion</button>
    </form>

    <br>

    <form action="studenti_echipe_meciuri.php" method="get">
        <button type="submit">Studenți din echipe cu cele mai multe meciuri</button>
    </form>

    <br>

    <form action="echipe_fara_meciuri.php" method="get">
        <button type="submit">Echipe fără meciuri</button>
    </form>

    <br>
    <br>

    <!-- Buton Logout -->
    <form action="logout.php" method="post">
        <button type="submit">Deconectare</button>
    </form>
</body>
</html>
