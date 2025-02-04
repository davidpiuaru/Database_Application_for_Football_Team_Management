<?php
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

// Interogare pentru a afla facultatea cu cele mai multe meciuri
$query = "
    SELECT 
        F.nume AS nume_facultate,
        COUNT(*) AS numar_meciuri
    FROM 
        facultate F
    INNER JOIN 
        echipa E ON F.id_echipa = E.id_echipa
    INNER JOIN 
        echipa_meci EM ON E.id_echipa IN (EM.id_echipa_1, EM.id_echipa_2)
    GROUP BY 
        F.id_facultate
    ORDER BY 
        numar_meciuri DESC
    LIMIT 1
";

$result = $conn->query($query);
$facultateTop = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facultatea cu cele mai multe meciuri</title>
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
        .message {
            margin: 20px 0;
            color: green;
        }
    </style>
</head>
<body>
    <h1>Facultatea cu cele mai multe meciuri</h1>

    <?php if ($facultateTop): ?>
        <table>
            <thead>
                <tr>
                    <th>Facultate</th>
                    <th>Număr Meciuri</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($facultateTop['nume_facultate']); ?></td>
                    <td><?php echo htmlspecialchars($facultateTop['numar_meciuri']); ?></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nu există date disponibile.</p>
    <?php endif; ?>

    <form action="dashboard.php" method="post">
        <button type="submit">Înapoi la Dashboard</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
