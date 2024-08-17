<?php
include_once 'config.php';

// Dati per la connessione al database (sostituisci con le credenziali fornite da Altervista)
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

include 'functions.php';  // Include la funzione per calcolare la classifica aggregata

$aggregatedRanking = calculateAggregatedRanking($conn);

function fetchRealTimeStandings() {
    $apiKey = API_KEY;
    $url = 'https://api.football-data.org/v4/competitions/SA/standings';

    // Utilizzo di cURL per l'API
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "X-Auth-Token: $apiKey",
        "Content-Type: application/json"
    ));

    // Specifica il percorso del file CA bundle
    curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cacert.pem');

    $response = curl_exec($ch);

    if(curl_errno($ch)) {
        echo 'Errore cURL: ' . curl_error($ch);
        return null;
    }

    curl_close($ch);

    return json_decode($response, true);
}



$standings = fetchRealTimeStandings();

if ($standings === null) {
    die('Errore durante il recupero della classifica.');
}

// Funzione per calcolare la correttezza delle previsioni
function calculatePredictionAccuracy($conn, $realStandings) {
    $predictionsReport = [];

    // Calcolo della posizione corretta di ciascuna squadra nella classifica reale
    $realRanking = [];
    foreach ($realStandings['standings'][0]['table'] as $team) {
        $realRanking[$team['team']['name']] = $team['position'];
    }

    // Recupera tutte le previsioni degli utenti
    $sql = "SELECT * FROM predictions";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $predictionDetails = [
            'correct' => 0,
            'wrong' => 0,
            'total' => 0,
            'name' => $row["first_name"] . " " . $row["last_name"]
        ];

        for ($i = 1; $i <= 20; $i++) {
            $team = $row["position$i"];
            if (!empty($team) && isset($realRanking[$team])) {
                $predictionDetails['total']++;
                if ($realRanking[$team] == $i) {
                    $predictionDetails['correct']++;
                } else {
                    $predictionDetails['wrong']++;
                }
            }
        }

        $predictionsReport[] = $predictionDetails;
    }

    return $predictionsReport;
}

$report = calculatePredictionAccuracy($conn, $standings);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutte le Previsioni</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow-y: auto;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
            max-width: 1200px;
            width: 100%;
            box-sizing: border-box;
            max-height: 90vh;
            overflow-y: auto;
        }

        h1, h2 {
            text-align: center;
            color: #1e88e5;
            margin-bottom: 20px;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #1e88e5;
            color: white;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        .container::-webkit-scrollbar {
            width: 12px;
        }

        .container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 6px;
        }

        .container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        a.button {
            display: inline-block;
            background-color: #1e88e5;
            color: white;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.2s;
            box-sizing: border-box;
        }

        a.button:hover {
            background-color: #1565c0;
            transform: translateY(-3px);
        }

        a.button:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Tutte le Previsioni</h1>

    <h2>Classifica Aggregata</h2>
    <table>
        <tr>
            <th>Posizione</th>
            <th>Squadra</th>
            <th>Media Posizione</th>
        </tr>
        <?php
        $position = 1;
        foreach ($aggregatedRanking as $team => $average) {
            echo "<tr>
                    <td>$position</td>
                    <td>$team</td>
                    <td>" . number_format($average, 2) . "</td>
                  </tr>";
            $position++;
        }
        ?>
    </table>

    <h2>Classifica Reale Serie A 2024-2025</h2>
    <table>
        <tr>
            <th>Posizione</th>
            <th>Squadra</th>
            <th>Punti</th>
        </tr>
        <?php
        foreach ($standings['standings'][0]['table'] as $team) {
            echo "<tr>
                    <td>{$team['position']}</td>
                    <td>{$team['team']['name']}</td>
                    <td>{$team['points']}</td>
                  </tr>";
        }
        ?>
    </table>

    <h2>Report delle Previsioni</h2>
    <table>
        <tr>
            <th>Previsione</th>
            <th>Totale Posizioni</th>
            <th>Corrette</th>
            <th>Sbagliate</th>
        </tr>
        <?php
        $predictionNumber = 1;
        foreach ($report as $details) {
            echo "<tr>
                    <td>Previsione n° $predictionNumber di {$details['name']}</td>
                    <td>{$details['total']}</td>
                    <td>{$details['correct']}</td>
                    <td>{$details['wrong']}</td>
                  </tr>";
            $predictionNumber++;
        }
        ?>
    </table>
    <a href="index.php" class="button">Torna alla Home</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
