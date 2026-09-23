<?php
include '../PDO/database.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username =$_POST['username'];
    $email =$_POST['email'];
    $password =$_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkEmailStmt =$conn->prepare("SELECT email FROM gebruiker WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();$checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {$message = "E-mailadres bestaat al";
        $toastClass = "toast-warning"; 
    } else {
        // Prepare and bind
        $stmt =$conn->prepare("INSERT INTO gebruiker (gebruikersnaam, email, wachtwoord) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email,$hashedPassword);

        if ($stmt->execute()) {$message = "Account succesvol aangemaakt";
            $toastClass = "toast-success"; 
        } else {
            $message = "Fout: " . $stmt->error;
            $toastClass = "toast-danger"; 
        }

        $stmt->close();
    }

    $checkEmailStmt->close();$conn->close();
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <title>Registreren - Veel Auto</title>
    <link rel="stylesheet" href="../css/register.css">
</head>

<body>
    <!-- Navigatie balk -->
    <nav class="navbar">
        <div class="nav-container">
            <a class="navbar-brand" href="#">
                <span class="brand-icon">🚗</span> 
                <span class="brand-text">Veel Auto</span>
            </a>
            <a href="./inlog.php" class="back-link">← Terug</a>
        </div>
    </nav>

    <div class="main-container">
        <?php if ($message): ?>
            <div class="custom-toast <?php echo $toastClass; ?>" id="toast-message">
                <div class="toast-body">
                    <?php echo $message; ?>
                </div>
                <button type="button" class="toast-close" onclick="this.parentElement.style.display='none';">&times;</button>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h2>Account aanmaken</h2>
            <p>Maak een nieuw Veel Auto account aan</p>
        </div>

        <!-- Registratie formulier -->
        <form method="post" class="login-form">
            <div class="form-group">
                <label for="email" class="form-label">E-mailadres</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="u@voorbeeld.nl" required>
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Gebruikersnaam</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Uw naam" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Wachtwoord</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Wachtwoord bevestigen</label>
                <input type="password" id="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">Account aanmaken</button>
            </div>
            
            <div class="register-link-container">
                <p>
                    Al een account? <a href="./inlog.php">Inloggen</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        // Optioneel: laat de melding na 3 seconden automatisch langzaam wegzakken/verdwijnen
        setTimeout(function() {
            let toast = document.getElementById('toast-message');
            if (toast) {
                toast.style.transition = 'opacity 0.5s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        }, 3000);
    </script>
</body>

</html>