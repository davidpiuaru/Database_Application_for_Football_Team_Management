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

// Valoare implicită pentru vârstă
$varsta_minima = isset($_GET['varsta_minima']) ? intval($_GET['varsta_minima']) : 45;

// Interogare pentru afișarea echipelor, locațiilor facultăților și informațiilor antrenorilor
$query = "
    SELECT 
        e.nume AS nume_echipa, 
        f.locatie AS locatie_facultate,
        (
            SELECT CONCAT(a.nume, ' ', a.prenume)
            FROM antrenor a
            WHERE a.id_echipa = e.id_echipa AND a.varsta > $varsta_minima
            LIMIT 1
        ) AS nume_antrenor,
        (
            SELECT a.varsta
            FROM antrenor a
            WHERE a.id_echipa = e.id_echipa AND a.varsta > $varsta_minima
            LIMIT 1
        ) AS varsta_antrenor
    FROM 
        echipa e
    JOIN 
        facultate f ON e.id_echipa = f.id_echipa
    WHERE 
        e.id_echipa IN (
            SELECT id_echipa
            FROM antrenor
            WHERE varsta > $varsta_minima
        );
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrenori și Echipe</title>
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
    <h1>Antrenori și Echipe</h1>

    <form method="get" action="">
        <label for="varsta_minima">Introduceți vârsta minimă a antrenorilor:</label>
        <input type="number" id="varsta_minima" name="varsta_minima" value="<?php echo htmlspecialchars($varsta_minima); ?>" required>
        <button type="submit">Filtrează</button>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nume Echipa</th>
                    <th>Locație Facultate</th>
                    <th>Nume Antrenor</th>
                    <th>Vârsta Antrenor</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nume_echipa']); ?></td>
                        <td><?php echo htmlspecialchars($row['locatie_facultate']); ?></td>
                        <td><?php echo htmlspecialchars($row['nume_antrenor']); ?></td>
                        <td><?php echo htmlspecialchars($row['varsta_antrenor']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nu există date disponibile pentru vârsta minimă selectată.</p>
    <?php endif; ?>

    <form action="dashboard.php" method="post">
        <button type="submit">Înapoi la Dashboard</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
