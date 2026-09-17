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
        $message = "<div class='alert alert-success' role='alert'>Rit succesvol ingepland!</div>";
    } else {
        $message = "<div class='alert alert-danger' role='alert'>Fout bij opslaan: " . $stmt->error . "</div>";
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
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        /* Sidebar styling */
        .sidebar {
            background-color: #172038;
            min-height: calc(100vh - 65px); /* minus de hoogte van de topbalk */
            color: #fff;
        }
        .sidebar .nav-link {
            color: #b0bec5;
            border-radius: 8px;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #f39c12;
            color: #fff;
        }
        .btn-veilig {
            background-color: #f39c12;
            border-color: #f39c12;
            color: #fff;
        }
        .btn-veilig:hover {
            background-color: #d68910;
            border-color: #d68910;
            color: #fff;
        }
        /* Bovenste navigatiebalk */
        .top-navbar {
            background-color: #172038;
            border-bottom: 1px solid #2c3e50;
            height: 65px;
        }
        /* Onderste balk */
        .bottom-footer {
            background-color: #ffffff;
            border-top: 1px solid #dee2e6;
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<!-- 1. BOVENSTE NAVIGATIEBALK (zoals op de screenshot) -->
<nav class="navbar top-navbar px-4 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <!-- Logo bovenin -->
        <div class="bg-warning p-2 rounded me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">🚗</div>
        <span class="fs-5 fw-bold text-white">Veel Auto</span>
    </div>
    <div>
        <a href="inlog.php" class="btn btn-warning fw-bold px-4 btn-sm">Inloggen</a>
    </div>
</nav>

<!-- HOOFD CONTAINER VOOR SIDEBAR EN CONTENT -->
<div class="container-fluid flex-grow-1 p-0">
    <div class="row g-0 h-100">
        
        <!-- 2. LINKER SIDEBAR (NAVIGATIEBAR) -->
        <nav class="col-md-3 col-lg-2 sidebar p-3 d-flex flex-column justify-content-between">
            <div>
                <!-- Extra Logo Box in de sidebar (optioneel of herhaling van boven) -->
                <div class="d-flex align-items-center mb-4 p-2 bg-dark rounded border border-secondary">
                    <span class="bg-warning p-1 rounded me-2">🚖</span>
                    <span class="fs-6 fw-bold text-white">Veel Auto</span>
                </div>

                <!-- Menu items -->
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link py-2 px-3" href="index.php">🏠 Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active py-2 px-3" href="rit_plannen.php">📍 Rit plannen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-2 px-3" href="informatie.php">ℹ️ Informatie</a>
                    </li>
                </ul>
            </div>

            <!-- Copyright onderaan de sidebar -->
            <div class="text-muted small pb-3">
                &copy; 2026 Veel Auto B.V.
            </div>
        </nav>

        <!-- HOOFDPAGINA INHOUD -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <?php echo $message; ?>

                    <div class="card shadow-sm border-0 p-4">
                        <form method="post">
                            <h2 class="fw-bold mb-1">Rit plannen</h2>
                            <p class="text-muted mb-4">Vul uw ritgegevens in. Uw chauffeur wordt automatisch geïnformeerd.</p>

                            <div class="mb-3">
                                <label for="ophaaladres" class="form-label text-uppercase fw-bold small text-secondary">Ophaaladres</label>
                                <input type="text" class="form-control" id="ophaaladres" name="ophaaladres" placeholder="bijv. Damstraat 1, Amsterdam">
                            </div>

                            <div class="mb-3">
                                <label for="bestemming" class="form-label text-uppercase fw-bold small text-secondary">Bestemming</label>
                                <input type="text" class="form-control" id="bestemming" name="bestemming" placeholder="bijv. Schiphol, Haarlemmermeer">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="datum" class="form-label text-uppercase fw-bold small text-secondary">Datum</label>
                                    <input type="date" class="form-control" id="datum" name="datum" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tijdstip" class="form-label text-uppercase fw-bold small text-secondary">Tijdstip</label>
                                    <input type="time" class="form-control" id="tijdstip" name="tijdstip" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="aantal-passagiers" class="form-label text-uppercase fw-bold small text-secondary">Aantal passagiers</label>
                                <select class="form-select" id="aantal-passagiers" name="aantal_passagiers">
                                    <option value="1">1 persoon</option>
                                    <option value="2">2 personen</option>
                                    <option value="3">3 personen</option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-veilig btn-lg">Rit bevestigen &rarr;</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </main>

    </div>
</div>

<!-- 3. ONDERSTE BALK / FOOTER (met contactgegevens en vraagteken icoon) -->
<footer class="bottom-footer py-2 px-4 d-flex justify-content-between align-items-center">
    <div>
        Contactgegevens: 
        <span class="text-danger">📞 XXX - XXX XX XX</span> &bull; 
        <span class="text-muted">✉️ XXXX@XXXXXXX.nl</span> &bull; 
        <span class="text-danger">📍 XXXXXXXXXX X, XXXXXXXXXX</span>
    </div>
    <div>
        <span class="badge rounded-circle bg-dark p-2" style="cursor: pointer;">?</span>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>