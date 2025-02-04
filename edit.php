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

// Verifică dacă parametrii table și id sunt în URL
if (isset($_GET['table']) && isset($_GET['id'])) {
    $selectedTable = $_GET['table'];
    $id = $_GET['id'];

    // Prevenirea SQL injection-ului
    $id = intval($id);

    // Obține cheia primară a tabelului
    $primaryKey = getPrimaryKey($selectedTable);

    // Obține datele curente ale rândului
    $query = "SELECT * FROM $selectedTable WHERE $primaryKey = $id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Rândul nu a fost găsit.";
        exit();
    }

    // Procesarea formularului de actualizare
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updatedValues = [];
        foreach ($row as $column => $value) {
            if ($column != $primaryKey && isset($_POST[$column])) {
                $updatedValues[$column] = $_POST[$column];
            }
        }

        // Construim interogarea de actualizare
        $updateQuery = "UPDATE $selectedTable SET ";
        foreach ($updatedValues as $column => $newValue) {
            $updateQuery .= "$column = '" . $conn->real_escape_string($newValue) . "', ";
        }
        $updateQuery = rtrim($updateQuery, ", ") . " WHERE $primaryKey = $id";

        if ($conn->query($updateQuery)) {
            echo "Datele au fost actualizate cu succes!";
            header("Location: dashboard.php?table=$selectedTable");
            exit();
        } else {
            echo "Eroare la actualizarea datelor: " . $conn->error;
        }
    }
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

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editare - Tabel</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 4px;
        }
        input[type="text"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            font-size: 14px;
            color: #007bff;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editare rând</h1>
        <form action="" method="post">
            <?php
            foreach ($row as $column => $value) {
                if ($column != $primaryKey) {
                    echo "<label for='$column'>" . htmlspecialchars($column) . ":</label>";
                    echo "<input type='text' id='$column' name='$column' value='" . htmlspecialchars($value) . "'>";
                }
            }
            ?>
            <button type="submit">Actualizează</button>
        </form>
        <a class="back-link" href="dashboard.php?table=<?php echo htmlspecialchars($selectedTable); ?>">Înapoi la tabel</a>
    </div>
</body>
</html>
