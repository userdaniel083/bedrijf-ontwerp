<?php
$availableDrivers = [];
$absentDrivers = [];

for ($driverNumber = 1; $driverNumber <= 12; $driverNumber++) {
	$availableDrivers[] = [
		'name' => 'Chauffeur ' . $driverNumber,
		'status' => 'Beschikbaar',
	];

	$absentDrivers[] = [
		'name' => 'Chauffeur ' . ($driverNumber + 12),
		'status' => 'Afwezig',
	];
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/chauffeur.css">
	<title>Chauffeurs - Veel Auto</title>
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
			<div class="section-heading">
				<h1>Aanwezige chauffeurs</h1>
				<span class="count"><?php echo count($availableDrivers); ?> beschikbaar</span>
			</div>

			<section class="driver-grid" aria-label="Aanwezige chauffeurs">
				<?php foreach ($availableDrivers as $driver): ?>
					<article class="driver-card">
						<img class="driver-image" src="../chauffeur.png" alt="Foto van <?php echo htmlspecialchars($driver['name']); ?>" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
						<div class="driver-fallback" hidden aria-hidden="true">👨‍✈️</div>
						<h2><?php echo htmlspecialchars($driver['name']); ?></h2>
						<p class="driver-status"><?php echo htmlspecialchars($driver['status']); ?></p>
						<button class="send-button" type="button" data-driver="<?php echo htmlspecialchars($driver['name']); ?>">Stuur rit naar chauffeur</button>
					</article>
				<?php endforeach; ?>
			</section>

			<div class="section-heading">
				<h1>Afwezige chauffeurs</h1>
				<span class="absent-count"><?php echo count($absentDrivers); ?> afwezig</span>
			</div>

			<section class="driver-grid" aria-label="Afwezige chauffeurs">
				<?php foreach ($absentDrivers as $driver): ?>
					<article class="driver-card absent">
						<img class="driver-image" src="../chauffeur.png" alt="Foto van <?php echo htmlspecialchars($driver['name']); ?>" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
						<div class="driver-fallback" hidden aria-hidden="true">👨‍✈️</div>
						<h2><?php echo htmlspecialchars($driver['name']); ?></h2>
						<p class="driver-status"><?php echo htmlspecialchars($driver['status']); ?></p>
					</article>
				<?php endforeach; ?>
			</section>
			<p class="message" id="message" role="status" aria-live="polite"></p>
		</main>
	</div>

	<script>
		document.querySelectorAll('.send-button').forEach(function (button) {
			button.addEventListener('click', function () {
				document.querySelectorAll('.send-button').forEach(function (item) {
					item.classList.remove('sent');
					item.textContent = 'Stuur rit naar chauffeur';
				});
				button.classList.add('sent');
				button.textContent = 'Rit verstuurd';
				document.getElementById('message').textContent = 'De rit is verstuurd naar ' + button.dataset.driver + '.';
			});
		});
	</script>
</body>
</html>
