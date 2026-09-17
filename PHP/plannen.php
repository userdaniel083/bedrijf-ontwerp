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
        $message = "<p style='color: green;'>Rit succesvol ingepland!</p>";
    } else {
      $message = "<p>Fout bij opslaan: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

   
?>
<?php echo $message; ?>
<form method="post">
  <h2>Rit plannen</h2>
  <p>Vul uw ritgegevens in. Uw chauffeur wordt automatisch geïnformeerd.</p>

  <div>
    <label for="ophaaladres">Ophaaladres</label><br>
    <input type="text" id="ophaaladres" name="ophaaladres">
  </div>

  <div>
    <label for="bestemming">Bestemming</label><br>
    <input type="text" id="bestemming" name="bestemming">
  </div>

  <div>
    <label for="datum">Datum</label><br>
    <input type="date" id="datum" name="datum" required>
  </div>

  <div>
    <label for="tijdstip">Tijdstip</label><br>
    <input type="time" id="tijdstip" name="tijdstip" required>
  </div>

  <div>
    <label for="aantal-passagiers">Aantal passagiers</label><br>
    <select id="aantal-passagiers" name="aantal_passagiers">
      <option value="1">1 passagier</option>
      <option value="2">2 passagiers</option>
      <option value="3">3 passagiers</option>
    </select>
  </div>

  <div>
    <button type="submit">Rit bevestigen</button>
  </div>
</form>
