<?php
// Dati per la connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_predictionseriea";

// Creazione della connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo della connessione
if ($conn->connect_error) {
  die("Connessione fallita: " . $conn->connect_error);
}

$prediction_id = $_GET['id'];

// Query per ottenere i dettagli della previsione
$sql = "SELECT * FROM predictions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $prediction_id);
$stmt->execute();
$result = $stmt->get_result();
$prediction = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<?php if ($prediction): ?>
  <h2>Classifica Finale</h2>
  <?php for ($i = 1; $i <= 20; $i++): ?>
    <div class="position">
      <strong><?php echo $i; ?>° Posizione:</strong> <?php echo htmlspecialchars($prediction["position$i"]); ?>
    </div>
  <?php endfor; ?>
<?php else: ?>
  <p>Dettagli non disponibili.</p>
<?php endif; ?>
