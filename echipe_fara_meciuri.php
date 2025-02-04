<?php
// Activează afișarea erorilor pentru depanare
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start sesiune
session_start();

// Conectare la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Echipe_de_fotbal";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

// Interogare SQL pentru echipe care nu au jucat niciun meci
$query = "
    SELECT 
        e.nume AS nume_echipa
    FROM 
        echipa e
    WHERE 
        e.id_echipa NOT IN (
            SELECT em.id_echipa_1 
            FROM echipa_meci em
            UNION
            SELECT em.id_echipa_2
            FROM echipa_meci em
        );
";

$result = $conn->query($query);

// Verificare rezultat interogare
if ($result === false) {
    die("Eroare în interogare: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Echipe fără meciuri</title>
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
    <h1>Echipe fără meciuri</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nume Echipa</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nume_echipa']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nu există echipe fără meciuri înregistrate.</p>
    <?php endif; ?>

    <form action="dashboard.php" method="post">
        <button type="submit">Înapoi la Dashboard</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
