<?php
include '../PDO/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ophaaladres = trim($_POST['ophaaladres']);
    $bestemming = trim($_POST['bestemming']);
    $datum = $_POST['datum'];
    $tijdstip = $_POST['tijdstip'];
    $passagiers = (int)$_POST['aantal_passagiers']; 
    
    try {
        $stmt = $pdo->prepare("INSERT INTO ritten (ophaaladres, bestemming, datum, tijdstip, passagiers) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$ophaaladres, $bestemming, $datum, $tijdstip, $passagiers]);
        $message = "<p style='color: green;'>Rit succesvol ingepland!</p>";
    } catch (PDOException $e) {
        $message = "<p>Fout bij opslaan: " . $e->getMessage() . "</p>";
    }
}

   
?>
<form>
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
    <input type="text" id="datum" name="datum">
  </div>

  <div>
    <label for="tijdstip">Tijdstip</label><br>
    <input type="text" id="tijdstip" name="tijdstip">
  </div>

  <div>
    <label for="aantal-passagiers">Aantal passagiers</label><br>
    <select id="aantal-passagiers" name="aantal_passagiers">
      <option value="1">1 passagier</option>
      <option value="1">2 passagiers</option>
      <option value="1">3 passagiers</option>
    </select>
  </div>

  <div>
    <button type="submit">Rit bevestigen</button>
  </div>
</form>
