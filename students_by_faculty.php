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

// Funcția pentru a obține studenții pe baza facultății
function getStudentsByFaculty($conn, $facultate_selectata) {
    $sql = "
        SELECT S.nume AS nume_student, E.nume AS nume_echipa, F.nume AS nume_facultate
        FROM student S
        JOIN echipa E ON S.id_echipa = E.id_echipa
        JOIN facultate F ON F.id_echipa = E.id_echipa
        WHERE F.nume = ?
        LIMIT 25
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $facultate_selectata); // legăm parametrul facultății
    $stmt->execute();
    $result = $stmt->get_result();

    // Afișăm rezultatele
    if ($result && $result->num_rows > 0) {
        echo "<h3>Studenți la " . htmlspecialchars($facultate_selectata) . ":</h3>";
        echo "<table><thead><tr><th>Nume Student</th><th>Nume Echipa</th><th>Facultate</th></tr></thead><tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nume_student']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nume_echipa']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nume_facultate']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>Nu există studenți în această facultate.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studenți la Facultate</title>
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
    </style>
</head>
<body>
    <h1>Studenți la Facultate</h1>

    <h2>Selectează o Facultate</h2>
    <form method="get" action="">
        <label for="facultate">Selectează Facultatea:</label>
        <select name="facultate" id="facultate">
            <?php
            // Obține lista de facultăți
            $facultatiQuery = "SELECT * FROM facultate";
            $facultatiResult = $conn->query($facultatiQuery);

            if ($facultatiResult && $facultatiResult->num_rows > 0) {
                while ($row = $facultatiResult->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['nume']) . "'>" . htmlspecialchars($row['nume']) . "</option>";
                }
            } else {
                echo "<option>No facultăți available</option>";
            }
            ?>
        </select>
        <button type="submit">Caută Studenți</button>
    </form>

    <?php
    // Dacă s-a selectat o facultate, afișăm studenții
    if (isset($_GET['facultate'])) {
        $facultate_selectata = $_GET['facultate'];
        getStudentsByFaculty($conn, $facultate_selectata);
    }
    ?>

    <h2>Acțiuni</h2>
    <!-- Buton pentru întoarcerea la dashboard -->
    <form method="get" action="dashboard.php">
        <button type="submit">Întoarce-te la Dashboard</button>
    </form>

</body>
</html>
