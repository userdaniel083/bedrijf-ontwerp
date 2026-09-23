<?php
include '../PDO/database.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute
    $stmt = $conn->prepare("SELECT wachtwoord, email FROM gebruiker WHERE email = ? OR gebruikersnaam = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password, $accountEmail);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $message = "Login successful";
            $toastClass = "bg-success";
            // Start the session and redirect to the dashboard or home page
            session_start();
            $_SESSION['email'] = $accountEmail ?? $email;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Incorrect password";
            $toastClass = "bg-danger";
        }
    } else {
        $message = "Email not found";
        $toastClass = "bg-warning";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Inlogpagina - Veel Auto</title>
    <style>
        .custom-navbar {
            background-color: #1e293b;
        }
        .btn-custom-blue {
            background-color: #1e293b;
            color: white;
        }
        .btn-custom-blue:hover {
            background-color: #0f172a;
            color: white;
        }
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-bottom: 50px;
        }
    </style>
</head>

<body>
    <!-- Nivigatie balk -->
    <nav class="navbar navbar-dark custom-navbar px-4 py-3 shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <span class="bg-warning p-1 rounded me-2 text-dark font-weight-bold">🚗</span> 
                <span style="font-weight: 600; font-size: 1.2rem;">Veel Auto</span>
            </a>
            <a href="../index.php" class="text-white text-decoration-none" style="font-size: 0.9rem;">← Terug</a>
        </div>
    </nav>

    <div class="main-container container">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white <?php echo $toastClass; ?> border-0 mb-4" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center mb-4">
            <h2 style="font-weight: 700; color: #1e293b;">Inlogpagina</h2>
            <p class="text-muted" style="font-size: 0.95rem;">Log in op uw Veel Auto account</p>
        </div>

        <!-- Formulier voor het inloggen -->
        <form action="" method="post" class="bg-white p-5 rounded-3 border"
            style="width: 100%; max-width: 450px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
            
            <div class="mb-3">
                <label for="email" class="form-label" style="font-weight: 500; color: #334155; font-size: 0.9rem;">E-mailadres of gebruikersnaam</label>
                <input type="text" name="email" id="email" class="form-control py-2" placeholder="admin@taxi.nl of taxi_admin" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label" style="font-weight: 500; color: #334155; font-size: 0.9rem;">Wachtwoord</label>
                <input type="password" name="password" id="password" class="form-control py-2" placeholder="••••••••" required>
            </div>
            
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-custom-blue py-2" style="font-weight: 600;">Inloggen</button>
            </div>
            
            <div class="text-center mt-3">
                <p class="mb-0" style="font-size: 0.9rem; color: #64748b;">
                    Nog geen account? <a href="./registeer.php" style="text-decoration: underline; color: #1e293b; font-weight: 600;">Registreren</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script>
</body>

</html>