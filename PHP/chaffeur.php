<?php
session_start();
include '../PDO/database.php';

if (!isset($_SESSION['email'])) {
    header("Location: inlog.php");
    exit();
}

$rit_id = isset($_GET['rit_id']) ? (int)$_GET['rit_id'] : null;
$message = "";

// Toewijzen van de rit verwerken
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_driver'])) {
    $post_rit_id = (int)$_POST['rit_id'];
    $chauffeur_id = (int)$_POST['chauffeur_id'];

    if ($post_rit_id > 0 && $chauffeur_id > 0) {
        $updateSql = "UPDATE ritten SET chauffeur_id = ?, status = 'Gepland' WHERE rit_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ii", $chauffeur_id, $post_rit_id);

        if ($stmt->execute()) {
            header("Location: administratie.php");
            exit();
        } else {
            $message = "Fout bij opslaan: " . $conn->error;
        }
    } else {
        $message = "Kies eerst een rit via de administratie pagina!";
    }
}

// Alleen accounts ophalen met rol = 'Chauffeur'
$sql = "SELECT g.ID, g.gebruikersnaam, g.email, cs.is_aanwezig 
        FROM gebruiker g
        LEFT JOIN chauffeurs_status cs ON g.ID = cs.chauffeur_id
        WHERE g.rol = 'Chauffeur'";

$result = $conn->query($sql);

$availableDrivers = [];
$absentDrivers = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $isAanwezig = ($row['is_aanwezig'] === null) ? 1 : (int)$row['is_aanwezig'];

        $driverData = [
            'id'     => $row['ID'],
            'name'   => $row['gebruikersnaam'],
            'email'  => $row['email'],
            'status' => $isAanwezig ? 'Beschikbaar' : 'Afwezig'
        ];

        if ($isAanwezig === 1) {
            $availableDrivers[] = $driverData;
        } else {
            $absentDrivers[] = $driverData;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/chauffeur.css">
    <title>Chauffeurs - Veel Auto</title>
    <style>
        .notice-bar {
            background-color: #e3f2fd;
            border: 1px solid #90caf9;
            color: #0d47a1;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .warning-bar {
            background-color: #ffebee;
            border: 1px solid #ef9a9a;
            color: #c62828;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .send-button {
            width: 100%;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="brand"><span class="brand-mark">🚕</span> Veel Auto <span class="role">Admin</span></div>
        <a class="back-link" href="../index.php">← Terug naar site</a>
    </header>

    <div class="layout">
        <aside class="sidebar">
            <p class="sidebar-title">Navigatie</p>
            <a class="nav-link active" href="chaffeur.php">👥 Chauffeur toewijzen</a>
            <a class="nav-link" href="administratie.php">📋 Administratie</a>
        </aside>

        <main>
            <?php if ($rit_id): ?>
                <div class="notice-bar">
                    🚕 Je bent momenteel een chauffeur aan het kiezen voor <strong>Rit #<?= htmlspecialchars($rit_id) ?></strong>
                </div>
            <?php else: ?>
                <div class="warning-bar">
                    ⚠️ Er is geen specifieke rit geselecteerd. Ga naar de <a href="administratie.php">Administratie</a> en klik op "Toewijzen" bij een rit.
                </div>
            <?php endif; ?>

            <?php if (!empty($message)): ?>
                <div class="warning-bar"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <div class="section-heading">
                <h1>Aanwezige chauffeurs</h1>
                <span class="count"><?= count($availableDrivers); ?> beschikbaar</span>
            </div>

            <section class="driver-grid" aria-label="Aanwezige chauffeurs">
                <?php if (count($availableDrivers) > 0): ?>
                    <?php foreach ($availableDrivers as $driver): ?>
                        <article class="driver-card">
                            <img class="driver-image" src="../chauffeur.png" alt="Foto van <?= htmlspecialchars($driver['name']); ?>" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                            <div class="driver-fallback" hidden aria-hidden="true">👨‍✈️</div>
                            <h2><?= htmlspecialchars($driver['name']); ?></h2>
                            <p class="driver-status"><?= htmlspecialchars($driver['status']); ?></p>

                            <?php if ($rit_id): ?>
                                <form method="POST" action="chaffeur.php?rit_id=<?= $rit_id ?>">
                                    <input type="hidden" name="rit_id" value="<?= htmlspecialchars($rit_id) ?>">
                                    <input type="hidden" name="chauffeur_id" value="<?= $driver['id'] ?>">
                                    <button class="send-button" type="submit" name="assign_driver">
                                        Wijs toe aan Rit #<?= htmlspecialchars($rit_id) ?>
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="send-button" type="button" disabled style="opacity: 0.5; cursor: not-allowed;">
                                    Selecteer eerst een rit
                                </button>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Er zijn op dit moment geen beschikbare chauffeurs.</p>
                <?php endif; ?>
            </section>

            <div class="section-heading">
                <h1>Afwezige chauffeurs</h1>
                <span class="absent-count"><?= count($absentDrivers); ?> afwezig</span>
            </div>

            <section class="driver-grid" aria-label="Afwezige chauffeurs">
                <?php if (count($absentDrivers) > 0): ?>
                    <?php foreach ($absentDrivers as $driver): ?>
                        <article class="driver-card absent">
                            <img class="driver-image" src="../chauffeur.png" alt="Foto van <?= htmlspecialchars($driver['name']); ?>" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                            <div class="driver-fallback" hidden aria-hidden="true">👨‍✈️</div>
                            <h2><?= htmlspecialchars($driver['name']); ?></h2>
                            <p class="driver-status"><?= htmlspecialchars($driver['status']); ?></p>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Er zijn geen afwezige chauffeurs.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>