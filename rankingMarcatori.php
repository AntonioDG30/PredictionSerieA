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

// Funzione per ottenere i capocannonieri dalla API
function fetchTopScorers() {
    $apiKey = API_KEY;
    $url = 'https://api.football-data.org/v4/competitions/SA/scorers';

    // Utilizzo di cURL per l'API
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "X-Auth-Token: $apiKey",
        "Content-Type: application/json"
    ));

    // Disabilita la verifica SSL per test (non raccomandato in produzione)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    if(curl_errno($ch)) {
        echo 'Errore cURL: ' . curl_error($ch);
        return null;
    }

    curl_close($ch);

    return json_decode($response, true);
}

// Ottieni i capocannonieri attuali
$topScorers = fetchTopScorers();

if ($topScorers === null) {
    die('Errore durante il recupero dei dati dell\'API.');
}

// Funzione per calcolare la precisione delle previsioni degli utenti
function calculateUserRankings($conn, $topScorers) {
    $realScorers = [];
    foreach ($topScorers['scorers'] as $scorer) {
        $realScorers[$scorer['player']['name']] = $scorer['numberOfGoals'];
    }

    $userRankings = [];

    $sql = "SELECT * FROM predictionsmarcatori";
    $result = $conn->query($sql);

    if (!$result) {
        die("Errore nella query: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $username = $row['first_name']." ".$row['last_name'];
        $season = $row['season'];

        $correctPredictions = 0;
        $totalPredictions = 0;

        for ($i = 1; $i <= 5; $i++) {
            $player = $row["scorer$i"];
            if (!empty($player) && isset($realScorers[$player])) {
                // Verifica se il giocatore è nei primi 5 in base ai gol
                $rank = array_search($player, array_keys(array_slice($realScorers, 0, 5, true)));
                if ($rank !== false && $rank + 1 == $i) {
                    $correctPredictions++;
                }
                $totalPredictions++;
            }
        }

        $incorrectPredictions = $totalPredictions - $correctPredictions;

        if (!isset($userRankings[$username])) {
            $userRankings[$username] = [
                'correct' => 0,
                'incorrect' => 0,
                'season' => $season
            ];
        }
        $userRankings[$username]['correct'] += $correctPredictions;
        $userRankings[$username]['incorrect'] += $incorrectPredictions;
    }

    arsort($userRankings);  // Ordina gli utenti in base al numero di previsioni corrette in ordine decrescente

    return $userRankings;
}

$rankings = calculateUserRankings($conn, $topScorers);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica dei Migliori Previsori</title>
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
            max-width: 800px;
            width: 100%;
            box-sizing: border-box;
            max-height: 90vh;
            overflow-y: auto;
        }

        h1 {
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
    <h1>Classifica dei Migliori Previsori</h1>

    <table>
        <tr>
            <th>Posizione</th>
            <th>Stagione</th>
            <th>Utente</th>
            <th>Previsioni Corrette</th>
            <th>Previsioni Sbagliate</th>
        </tr>
        <?php
        $position = 1;
        foreach ($rankings as $username => $details) {
            echo "<tr>
                    <td>$position</td>
                    <td>{$details['season']}</td>
                    <td>$username</td>
                    <td>{$details['correct']}</td>
                    <td>{$details['incorrect']}</td>
                  </tr>";
            $position++;
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
