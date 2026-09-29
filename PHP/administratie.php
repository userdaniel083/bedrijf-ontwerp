<?php
session_start();
include '../PDO/database.php';

if (!isset($_SESSION['email'])) {
    header("Location: inlog.php");
    exit();
}

// Query haalt nu de 'gebruikersnaam' op voor klant en chauffeur
$sql = "SELECT r.rit_id, r.ophaaladres, r.bestemming, r.datum, r.tijdstip, r.chauffeur_id,
               klant.gebruikersnaam AS klant_naam, 
               chauffeur.gebruikersnaam AS chauffeur_naam
        FROM ritten r
        LEFT JOIN gebruiker klant ON r.klant_id = klant.ID
        LEFT JOIN gebruiker chauffeur ON r.chauffeur_id = chauffeur.ID
        ORDER BY r.datum ASC, r.tijdstip ASC";

$result = $conn->query($sql);

$ritten = [];
if ($result && $result->num_rows > 0) {
    $ritten = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <link rel="stylesheet" href="../css/administratie.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace kantoor</title>
    <style>
        .btn-toewijzen {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-toewijzen:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <!-- Top Header -->
    <header class="header">
        <div class="header-left">
            <div class="logo-box">🚗</div>
            <span class="brand-title">Veel Auto</span>
            <span class="badge-admin">Admin</span>
        </div>
        <div class="header-right">
            <span class="workspace-label">Workspace kantoor</span>
            <a href="site.php" class="back-btn">← Terug naar site</a>
        </div>
    </header>

    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="nav-heading">NAVIGATIE</div>
            <nav class="nav-links">
                <a href="chaffeur.php" class="nav-link">
                    <span class="icon">👥</span> Chauffeur toewijzen
                </a>
                <a href="administratie.php" class="nav-link active">
                    <span class="icon">📄</span> Administratie
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <div class="page-title-area">
                <h1>Geplande rit</h1>
                <p>Overzicht van alle ingeplande ritten — automatisch bijgewerkt</p>
            </div>

            <!-- Tabel Container -->
            <div class="table-container">
                <table class="rit-tabel">
                    <thead>
                        <tr>
                            <th>KLANT</th>
                            <th>CHAUFFEUR</th>
                            <th>TIJD</th>
                            <th>OPHAAL PLAATS</th>
                            <th>BESTEMMING</th>
                            <th>DATUM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($ritten) > 0): ?>
                            <?php foreach ($ritten as $rit): ?>
                                <tr>
                                    <td><?= htmlspecialchars($rit['klant_naam'] ?? 'Onbekend') ?></td>
                                    
                                    <td>
                                        <?php if (empty($rit['chauffeur_id'])): ?>
                                            <a href="chaffeur.php?rit_id=<?= urlencode($rit['rit_id']) ?>" class="btn-toewijzen">Toewijzen</a>
                                        <?php else: ?>
                                            <?= htmlspecialchars($rit['chauffeur_naam']) ?>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td><?= htmlspecialchars(substr($rit['tijdstip'], 0, 5)) ?></td>
                                    <td><?= htmlspecialchars($rit['ophaaladres']) ?></td>
                                    <td><?= htmlspecialchars($rit['bestemming']) ?></td>
                                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($rit['datum']))) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Er zijn momenteel geen ritten gevonden.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>