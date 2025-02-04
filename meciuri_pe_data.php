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

// Obținem data din query string sau setăm o valoare implicită
$dataMeci = isset($_GET['data']) ? $_GET['data'] : '2025-01-04';

// Interogare pentru a obține meciurile de la data specificată
$query = "
    SELECT 
        M.loc AS loc_meci,
        M.ora AS ora_meci,
        E1.nume AS echipa_1,
        E2.nume AS echipa_2
    FROM 
        meci M
    INNER JOIN 
        echipa_meci EM ON M.id_meci = EM.id_meci
    INNER JOIN 
        echipa E1 ON EM.id_echipa_1 = E1.id_echipa
    INNER JOIN 
        echipa E2 ON EM.id_echipa_2 = E2.id_echipa
    WHERE 
        M.data = '$dataMeci'
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meciuri pe data</title>
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
    <h1>Meciuri pe data: <?php echo htmlspecialchars($dataMeci); ?></h1>

    <form method="get" action="">
        <label for="data">Alege o dată:</label>
        <input type="date" id="data" name="data" value="<?php echo htmlspecialchars($dataMeci); ?>">
        <button type="submit">Caută</button>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Loc</th>
                    <th>Ora</th>
                    <th>Echipa 1</th>
                    <th>Echipa 2</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['loc_meci']); ?></td>
                        <td><?php echo htmlspecialchars($row['ora_meci']); ?></td>
                        <td><?php echo htmlspecialchars($row['echipa_1']); ?></td>
                        <td><?php echo htmlspecialchars($row['echipa_2']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nu există meciuri programate pentru această dată.</p>
    <?php endif; ?>

    <form action="dashboard.php" method="post">
        <button type="submit">Înapoi la Dashboard</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
