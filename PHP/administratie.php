<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: inlog.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <link rel="stylesheet" href="../css/administratie.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace kantoor</title>
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
                            <th>TIJD / DUUR</th>
                            <th>OPHAAL PLAATS</th>
                            <th>BESTEMMING</th>
                            <th>DATUM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Hier kun straks je while-loop van de database neerzetten, bijv: while($row = ...) -->
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>