<?php
session_start();
include '../PDO/database.php';

$message = "";

if (!isset($_SESSION['email'])) {
    header("Location: inlog.php");
    exit();
}

$userStmt = $conn->prepare("SELECT ID FROM gebruiker WHERE email = ?");
$userStmt->bind_param("s", $_SESSION['email']);
$userStmt->execute();
$userStmt->bind_result($klant_id);
$userStmt->fetch();
$userStmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ophaaladres = trim($_POST['ophaaladres']);
    $bestemming = trim($_POST['bestemming']);
    $datum = $_POST['datum'];
    $tijdstip = $_POST['tijdstip'];
    $passagiers = (int)$_POST['aantal_passagiers']; 
    
    $stmt = $conn->prepare("INSERT INTO ritten (klant_id, ophaaladres, bestemming, datum, tijdstip, aantal_passagiers) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $klant_id, $ophaaladres, $bestemming, $datum, $tijdstip, $passagiers);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Rit succesvol ingepland!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Fout bij opslaan: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rit plannen - Veel Auto</title>
    <link rel="stylesheet" href="../css/plannen.css">
</head>
<body>

<div class="site-shell">

    <header class="topbar">
        <div class="brand">
            <span class="brand-mark">🚕</span>
            <span>Veel Auto</span>
        </div>
        <div>
            <a href="inlog.php" class="login-button">Inloggen</a>
        </div>
    </header>

    <div class="page-layout">
        
        <aside class="sidebar">
            <div class="sidebar-brand">
                <span class="sidebar-logo-icon">🚕</span>
                <span>Veel Auto</span>
            </div>

            <ul class="side-navigation">
                <li><a class="nav-link" href="../index.php"><span>⌂</span> Menu</a></li>
                <li><a class="nav-link active" href="plannen.php"><span>•</span> Rit plannen</a></li>
                <li><a class="nav-link" href="info.php"><span>ⓘ</span> Informatie</a></li>
            </ul>

            <div class="copyright">
                &copy; 2026 Veel Auto B.V.
            </div>
        </aside>

        <main class="main-content">
            <div class="content-wrapper">
                
                <?php echo $message; ?>

                <div class="card">
                    <form method="post">
                        <h2 class="form-title">Rit plannen</h2>
                        <p class="form-subtitle">Vul uw ritgegevens in. Uw chauffeur wordt automatisch geïnformeerd.</p>

                        <div class="form-group">
                            <label for="ophaaladres" class="form-label">Ophaaladres</label>
                            <input type="text" class="form-control" id="ophaaladres" name="ophaaladres" placeholder="bijv. Damstraat 1, Amsterdam">
                        </div>

                        <div class="form-group">
                            <label for="bestemming" class="form-label">Bestemming</label>
                            <input type="text" class="form-control" id="bestemming" name="bestemming" placeholder="bijv. Schiphol, Haarlemmermeer">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="datum" class="form-label">Datum</label>
                                <input type="date" class="form-control" id="datum" name="datum" required>
                            </div>
                            <div class="form-group">
                                <label for="tijdstip" class="form-label">Tijdstip</label>
                                <input type="time" class="form-control" id="tijdstip" name="tijdstip" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="aantal-passagiers" class="form-label">Aantal passagiers</label>
                            <select class="form-select" id="aantal-passagiers" name="aantal_passagiers">
                                <option value="1">1 persoon</option>
                                <option value="2">2 personen</option>
                                <option value="3">3 personen</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-top: 24px;">
                            <button type="submit" class="btn-veilig">Rit bevestigen &rarr;</button>
                        </div>
                    </form>
                </div>

            </div>
        </main>

    </div>

    <footer class="footer">
        <div class="footer-item">
            Contactgegevens: 
            <span>📞 XXX - XXX XX XX</span> &bull; 
            <span class="text-muted">✉️ XXXX@XXXXXXX.nl</span> &bull; 
            <span>📍 XXXXXXXXXX X, XXXXXXXXXX</span>
        </div>
    </footer>

</div>

</body>
</html>