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

// Interogare SQL pentru găsirea studenților și echipelor cu cele mai multe meciuri
$query = "
    SELECT 
        s.nume AS nume_student,
        s.prenume AS prenume_student,
        e.nume AS nume_echipa,
        COUNT(em.id_meci) AS numar_total_meciuri
    FROM 
        student s
    JOIN 
        echipa e ON s.id_echipa = e.id_echipa
    JOIN 
        echipa_meci em ON e.id_echipa = em.id_echipa_1 OR e.id_echipa = em.id_echipa_2
    WHERE 
        e.id_echipa = (
            SELECT em.id_echipa_1 
            FROM echipa_meci em
            GROUP BY em.id_echipa_1 
            ORDER BY COUNT(em.id_meci) DESC
            LIMIT 1
        )
    GROUP BY 
        s.id_student, e.nume;
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
    <title>Studenți și Echipe</title>
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
    <h1>Studenți din echipe cu cele mai multe meciuri</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nume Student</th>
                    <th>Prenume Student</th>
                    <th>Nume Echipa</th>
                    <th>Număr Total Meciuri</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nume_student']); ?></td>
                        <td><?php echo htmlspecialchars($row['prenume_student']); ?></td>
                        <td><?php echo htmlspecialchars($row['nume_echipa']); ?></td>
                        <td><?php echo htmlspecialchars($row['numar_total_meciuri']); ?></td>
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

<?php
$conn->close();
?>
