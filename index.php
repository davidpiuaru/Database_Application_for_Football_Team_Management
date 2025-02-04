<?php
// Conexiune la baza de date
$servername = "localhost";
$username = "root"; // utilizator MySQL
$password = ""; // parola MySQL
$dbname = "Echipe_de_fotbal";

// Creare conexiune
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificare conexiune
if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

// Pornire sesiune
session_start();

// Verificare dacă formularul a fost trimis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $conn->real_escape_string($_POST['username']);
    $pass = $_POST['password']; // Fără criptare

    // Verificare utilizator și parolă
    $stmt = $conn->prepare("SELECT username FROM utilizatori WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Salvare detalii sesiune
        $_SESSION['username'] = $user;
        header("Location: dashboard.php"); // Redirecționare către dashboard
        exit();
    } else {
        $error = "Utilizator sau parolă incorectă!";
    }
    $stmt->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Echipe de Fotbal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-container button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Utilizator" required>
            <input type="password" name="password" placeholder="Parolă" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
