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

// Verificăm dacă tabela este specificată
if (isset($_GET['table'])) {
    $selectedTable = $_GET['table'];

    // Obține detaliile coloanelor din tabel
    $columnsQuery = "SHOW COLUMNS FROM $selectedTable";
    $columnsResult = $conn->query($columnsQuery);

    if (!$columnsResult || $columnsResult->num_rows == 0) {
        echo "Nu s-au găsit coloane pentru tabelul selectat.";
        exit();
    }

    $columns = [];
    while ($row = $columnsResult->fetch_assoc()) {
        $columns[] = $row;
    }

    // Procesarea formularului de adăugare
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $insertValues = [];
        $columnNames = [];

        foreach ($columns as $column) {
            $columnName = $column['Field'];
            $isAutoIncrement = strpos($column['Extra'], 'auto_increment') !== false;

            if (!$isAutoIncrement && isset($_POST[$columnName])) {
                $columnNames[] = $columnName;
                $insertValues[] = "'" . $conn->real_escape_string($_POST[$columnName]) . "'";
            }
        }

        // Construim interogarea INSERT
        $insertQuery = "INSERT INTO $selectedTable (" . implode(", ", $columnNames) . ") 
                        VALUES (" . implode(", ", $insertValues) . ")";

        if ($conn->query($insertQuery)) {
            echo "Elementul a fost adăugat cu succes!";
            header("Location: dashboard.php?table=$selectedTable");
            exit();
        } else {
            echo "Eroare la adăugarea elementului: " . $conn->error;
        }
    }
} else {
    echo "Tabelul nu a fost specificat.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adaugă Element - <?php echo htmlspecialchars($selectedTable); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 20px;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Adaugă un nou element în tabelul <?php echo htmlspecialchars($selectedTable); ?></h1>
    <form action="" method="post">
        <?php
        foreach ($columns as $column) {
            $columnName = $column['Field'];
            $isAutoIncrement = strpos($column['Extra'], 'auto_increment') !== false;

            if (!$isAutoIncrement) {
                echo "<label for='$columnName'>" . htmlspecialchars($columnName) . ":</label>";
                echo "<input type='text' id='$columnName' name='$columnName'><br>";
            }
        }
        ?>
        <button type="submit">Adaugă</button>
    </form>
    <a href="dashboard.php?table=<?php echo htmlspecialchars($selectedTable); ?>">Înapoi la tabel</a>
</body>
</html>
