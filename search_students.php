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

// Logica de căutare a studenților
$studentsResult = null;
if (isset($_GET['antrenor']) && !empty($_GET['antrenor'])) {
    $antrenorSelectat = $_GET['antrenor'];
    $query = "
        SELECT S.nume AS nume_jucator, E.nume AS nume_echipa, A.nume AS nume_antrenor 
        FROM student S 
        JOIN Echipa E ON S.id_echipa = E.id_echipa 
        JOIN Antrenor A ON E.id_echipa = A.id_echipa 
        WHERE A.nume = ? 
        LIMIT 25
    ";

    // Pregătirea interogării pentru a preveni SQL Injection
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $antrenorSelectat);
    $stmt->execute();
    $studentsResult = $stmt->get_result();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Căutare Studenți - Echipe de Fotbal</title>
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
    <h1>Căutare Studenți</h1>
    <form method="get" action="">
        <label for="antrenor">Selectează un antrenor:</label>
        <select name="antrenor" id="antrenor">
            <option value="">Alege un antrenor</option>
            <?php
            // Obținem lista de antrenori
            $sql = "SELECT nume FROM Antrenor";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['nume']) . '">' . htmlspecialchars($row['nume']) . '</option>';
                }
            }
            ?>
        </select>
        <button type="submit">Căută studenți</button>
    </form>

    <?php if ($studentsResult): ?>
        <h2>Studenții antrenați de "<?php echo htmlspecialchars($_GET['antrenor']); ?>"</h2>
        <?php if ($studentsResult->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nume Jucător</th>
                        <th>Nume Echipa</th>
                        <th>Nume Antrenor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $studentsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nume_jucator']); ?></td>
                            <td><?php echo htmlspecialchars($row['nume_echipa']); ?></td>
                            <td><?php echo htmlspecialchars($row['nume_antrenor']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nu există studenți pentru acest antrenor.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">Înapoi la Dashboard</a>
</body>
</html>
