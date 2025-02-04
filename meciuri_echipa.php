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

// Obținem lista de echipe
$echipeQuery = "SELECT id_echipa, nume FROM echipa";
$echipeResult = $conn->query($echipeQuery);

// Preluăm echipa selectată din query string
$echipaSelectata = isset($_GET['echipa']) ? intval($_GET['echipa']) : 0;

// Interogare pentru meciuri ale echipei selectate
$meciuri = [];
if ($echipaSelectata > 0) {
    $query = "
        SELECT 
            M.loc AS loc_meci,
            M.ora AS ora_meci,
            CASE 
                WHEN EM.id_echipa_1 = $echipaSelectata THEN 'Acasă'
                WHEN EM.id_echipa_2 = $echipaSelectata THEN 'Deplasare'
            END AS loc_joc,
            CASE 
                WHEN EM.id_echipa_1 = $echipaSelectata THEN E2.nume
                WHEN EM.id_echipa_2 = $echipaSelectata THEN E1.nume
            END AS echipa_adversa
        FROM 
            meci M
        INNER JOIN 
            echipa_meci EM ON M.id_meci = EM.id_meci
        INNER JOIN 
            echipa E1 ON EM.id_echipa_1 = E1.id_echipa
        INNER JOIN 
            echipa E2 ON EM.id_echipa_2 = E2.id_echipa
        WHERE 
            EM.id_echipa_1 = $echipaSelectata OR EM.id_echipa_2 = $echipaSelectata
    ";

    $meciuriResult = $conn->query($query);
    if ($meciuriResult && $meciuriResult->num_rows > 0) {
        $meciuri = $meciuriResult->fetch_all(MYSQLI_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meciuri ale echipei</title>
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
    <h1>Meciuri pentru o echipă</h1>

    <form method="get" action="">
        <label for="echipa">Alege echipa:</label>
        <select id="echipa" name="echipa">
            <option value="0">-- Selectează echipa --</option>
            <?php while ($row = $echipeResult->fetch_assoc()): ?>
                <option value="<?php echo $row['id_echipa']; ?>" <?php echo ($echipaSelectata == $row['id_echipa']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['nume']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Caută</button>
    </form>

    <?php if (!empty($meciuri)): ?>
        <table>
            <thead>
                <tr>
                    <th>Loc</th>
                    <th>Ora</th>
                    <th>Loc joc</th>
                    <th>Echipa adversă</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($meciuri as $meci): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($meci['loc_meci']); ?></td>
                        <td><?php echo htmlspecialchars($meci['ora_meci']); ?></td>
                        <td><?php echo htmlspecialchars($meci['loc_joc']); ?></td>
                        <td><?php echo htmlspecialchars($meci['echipa_adversa']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($echipaSelectata > 0): ?>
        <p>Nu există meciuri pentru această echipă.</p>
    <?php endif; ?>

    <form action="dashboard.php" method="post">
        <button type="submit">Înapoi la Dashboard</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
