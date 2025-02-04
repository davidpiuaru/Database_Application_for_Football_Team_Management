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

// Obține lista stadionelor din baza de date
$stadion_query = "SELECT DISTINCT loc FROM meci";
$stadion_result = $conn->query($stadion_query);

// Stadion selectat de utilizator (valoare implicită)
$stadion = isset($_GET['stadion']) ? $conn->real_escape_string($_GET['stadion']) : 'Stadion National Arena';

// Interogare SQL
$query = "
    SELECT 
        e.nume AS nume_echipa,
        (
            SELECT COUNT(*)
            FROM echipa_meci em
            WHERE em.id_echipa_1 = e.id_echipa OR em.id_echipa_2 = e.id_echipa
        ) AS numar_total_meciuri
    FROM 
        echipa e
    WHERE 
        e.id_echipa IN (
            SELECT DISTINCT em.id_echipa_1
            FROM echipa_meci em
            JOIN meci m ON em.id_meci = m.id_meci
            WHERE m.loc = '$stadion'
            UNION
            SELECT DISTINCT em.id_echipa_2
            FROM echipa_meci em
            JOIN meci m ON em.id_meci = m.id_meci
            WHERE m.loc = '$stadion'
        );
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Echipe și Meciuri</title>
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
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Echipe care au jucat pe stadion</h1>

    <form method="get" action="">
        <label for="stadion">Selectați stadionul:</label>
        <select id="stadion" name="stadion" required>
            <?php if ($stadion_result && $stadion_result->num_rows > 0): ?>
                <?php while ($row = $stadion_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['loc']); ?>" 
                        <?php echo ($row['loc'] === $stadion) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['loc']); ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
        <button type="submit">Filtrează</button>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nume Echipa</th>
                    <th>Număr Total Meciuri</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nume_echipa']); ?></td>
                        <td><?php echo htmlspecialchars($row['numar_total_meciuri']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nu există date disponibile pentru stadionul selectat.</p>
    <?php endif; ?>

    <form action="dashboard.php" method="post">
        <button type="submit">Înapoi la Dashboard</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
