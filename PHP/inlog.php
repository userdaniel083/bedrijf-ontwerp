<?php
include '../PDO/database.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT wachtwoord, rol FROM gebruiker WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password, $db_rol);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $message = "Login successful";
            $toastClass = "success";
            
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['rol'] = $db_rol;

            if (strtolower($db_rol) === 'admin') {
                header("Location: administratie.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            $message = "Incorrect password";
            $toastClass = "danger";
        }
    } else {
        $message = "Email not found";
        $toastClass = "warning";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <title>Inlogpagina - Veel Auto</title>
    <link rel="stylesheet" href="../css/inlog.css">
</head>

<body>
    <!-- Navigatie balk -->
    <nav class="navbar">
        <div class="nav-container">
            <a class="navbar-brand" href="#">
                <span class="brand-icon">🚗</span> 
                <span class="brand-text">Veel Auto</span>
            </a>
            <a href="../index.php" class="back-link">← Terug</a>
        </div>
    </nav>

    <div class="main-container">
        <?php if ($message): ?>
            <div class="custom-toast toast-<?php echo $toastClass; ?>">
                <div class="toast-content">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="toast-close" onclick="this.parentElement.parentElement.style.display='none';">&times;</button>
                </div>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h2>Inlogpagina</h2>
            <p>Log in op uw Veel Auto account</p>
        </div>

        <!-- Formulier voor het inloggen -->
        <form action="" method="post" class="login-form">
            <div class="form-group">
                <label for="email" class="form-label">Gebruikersnaam</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="u@voorbeeld.nl" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Wachtwoord</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">Inloggen</button>
            </div>
            
            <div class="register-link-container">
                <p>
                    Nog geen account? <a href="./registeer.php">Registreren</a>
                </p>
            </div>
        </form>
    </div>
</body>

</html>