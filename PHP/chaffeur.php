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
	<title>Chauffeurs - Veel Auto</title>
	<style>
		:root {
			--navy: #1f326d;
			--orange: #f9a91b;
			--page: #f5f7fb;
			--text: #182747;
			--muted: #6b7690;
			--green: #15945b;
			--green-light: #e5f8ee;
		}

		* { box-sizing: border-box; }

		body {
			margin: 0;
			background: var(--page);
			color: var(--text);
			font-family: "Trebuchet MS", Verdana, sans-serif;
		}

		.topbar {
			display: flex;
			align-items: center;
			justify-content: space-between;
			min-height: 74px;
			padding: 0 32px;
			background: #fff;
			border-bottom: 1px solid #e2e6ef;
		}

		.brand { display: flex; align-items: center; gap: 10px; font-weight: 700; }
		.brand-mark {
			display: grid;
			width: 28px;
			height: 28px;
			place-items: center;
			border-radius: 7px;
			background: var(--orange);
		}
		.role { padding: 4px 10px; border-radius: 20px; background: #e5eaff; color: #5062c4; font-size: 11px; }
		.back-link { color: var(--muted); font-size: 12px; text-decoration: none; }
		.back-link:hover { color: var(--navy); }

		.layout { display: grid; grid-template-columns: 188px 1fr; min-height: calc(100vh - 74px); }
		.sidebar { padding: 24px 14px; background: #fff; border-right: 1px solid #e2e6ef; }
		.sidebar-title { margin: 0 0 18px 14px; color: #8a93a8; font-size: 10px; letter-spacing: .4px; text-transform: uppercase; }
		.nav-link { display: block; padding: 14px; border-radius: 10px; color: var(--text); font-size: 12px; text-decoration: none; }
		.nav-link.active { background: var(--navy); color: #fff; font-weight: 700; }

		main { padding: 34px 40px 54px; }
		.heading { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; }
		h1 { margin: 0; font-size: 18px; }
		.count { padding: 5px 10px; border-radius: 20px; background: var(--green-light); color: var(--green); font-size: 11px; font-weight: 700; }
		.section-heading { display: flex; align-items: center; gap: 10px; margin: 30px 0 18px; }
		.section-heading:first-child { margin-top: 0; }
		.absent-count { padding: 5px 10px; border-radius: 20px; background: #ffe9e9; color: #d73535; font-size: 11px; font-weight: 700; }
		.driver-grid { display: grid; grid-template-columns: repeat(3, minmax(170px, 1fr)); gap: 16px; }
		.driver-card { padding: 18px; background: #fff; border: 1px solid #dce4ef; border-radius: 12px; text-align: center; box-shadow: 0 5px 14px rgb(31 50 109 / 5%); }
		.driver-card.absent { background: #fff6f6; border-color: #ffd3d3; }
		.driver-image { width: 92px; height: 92px; margin: 0 auto 12px; object-fit: cover; border-radius: 50%; background: #eef2f8; }
		.driver-fallback { display: flex; align-items: center; justify-content: center; width: 92px; height: 92px; margin: 0 auto 12px; border-radius: 50%; background: #e9efff; color: var(--navy); font-size: 36px; }
		.driver-card.absent .driver-fallback { background: #ffe5e5; color: #d73535; }
		.driver-card h2 { margin: 0 0 5px; font-size: 14px; }
		.driver-status { margin: 0 0 14px; color: var(--green); font-size: 11px; }
		.driver-card.absent .driver-status { color: #d73535; }
		.send-button { width: 100%; padding: 10px; border: 0; border-radius: 7px; background: var(--navy); color: #fff; cursor: pointer; font: inherit; font-size: 11px; font-weight: 700; }
		.send-button:hover { background: #2b438c; }
		.send-button.sent { background: var(--green); }
		.message { min-height: 20px; margin: 24px 0 0; color: var(--green); font-size: 13px; font-weight: 700; }

		@media (max-width: 760px) {
			.topbar { padding: 0 16px; }
			.layout { grid-template-columns: 1fr; }
			.sidebar { display: flex; gap: 8px; padding: 12px 16px; border-right: 0; border-bottom: 1px solid #e2e6ef; }
			.sidebar-title { display: none; }
			.nav-link { padding: 10px 12px; }
			main { padding: 24px 16px 40px; }
			.driver-grid { grid-template-columns: repeat(2, minmax(130px, 1fr)); }
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
