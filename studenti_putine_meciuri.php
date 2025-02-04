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

// Interogare pentru a obține studenții cu cele mai puține meciuri
$query = "
    SELECT 
        S.nume AS nume_student,
        COUNT(EM.id_meci) AS numar_meciuri
    FROM 
        student S
    LEFT JOIN 
        echipa_meci EM ON S.id_echipa IN (EM.id_echipa_1, EM.id_echipa_2)
    GROUP BY 
        S.id_student
    ORDER BY 
        numar_meciuri ASC
    LIMIT 10
";

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studenții cu cele mai puține meciuri</title>
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
    <h1>Studenții cu cele mai puține meciuri</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nume Student</th>
                    <th>Număr Meciuri</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nume_student']); ?></td>
                        <td><?php echo htmlspecialchars($row['numar_meciuri']); ?></td>
                    </tr>
                <?php endwhile; ?>
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
