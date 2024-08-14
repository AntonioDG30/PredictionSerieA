<?php
include_once 'config.php';

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

include 'functions.php';  // Include la funzione per calcolare la classifica aggregata

$aggregatedRanking = calculateAggregatedRanking2($conn);

function fetchTopScorers() {
    $apiKey = API_KEY;
    $url = 'https://api.football-data.org/v4/competitions/SA/scorers';

    $options = [
        "http" => [
            "header" => "X-Auth-Token: $apiKey"
        ]
    ];
    $context = stream_context_create($options);

    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        die("Errore nel recupero dei dati dai capocannonieri.");
    }

    return json_decode($response, true);
}

$topScorers = fetchTopScorers();

// Funzione per calcolare la correttezza delle previsioni sui capocannonieri
function calculateScorerPredictionAccuracy($conn, $topScorers) {
    $scorersReport = [];

    // Verifica se ci sono capocannonieri
    if (empty($topScorers['scorers'])) {
        return $scorersReport; // Restituisce un array vuoto se non ci sono dati
    }

    // Calcolo dei capocannonieri reali
    $realScorers = [];
    foreach ($topScorers['scorers'] as $scorer) {
        $realScorers[$scorer['player']['name']] = $scorer['numberOfGoals'];
    }

    // Recupera tutte le previsioni degli utenti
    $sql = "SELECT * FROM predictionsmarcatori";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $predictionDetails = [
            'correct' => 0,
            'wrong' => 0,
            'total' => 0
        ];

        for ($i = 1; $i <= 5; $i++) {
            $player = $row["scorer$i"];
            if (!empty($player) && isset($realScorers[$player])) {
                $predictionDetails['total']++;
                // Verifica se il giocatore è nei primi 5 capocannonieri
                $rank = array_search($player, array_keys(array_slice($realScorers, 0, 5, true)));
                if ($rank !== false && $rank + 1 == $i) {
                    $predictionDetails['correct']++;
                } else {
                    $predictionDetails['wrong']++;
                }
            }
        }

        $scorersReport[] = $predictionDetails;
    }

    return $scorersReport;
}

$report = calculateScorerPredictionAccuracy($conn, $topScorers);
?>

    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tutte le Previsioni sui Capocannonieri</title>
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
        <h1>Previsioni sui Capocannonieri</h1>

        <h2>Classifica Aggregata dei Capocannonieri</h2>
        <table>
            <tr>
                <th>Posizione</th>
                <th>Giocatore</th>
                <th>Media Posizione</th>
            </tr>
            <?php
            $position = 1;
            foreach ($aggregatedRanking as $player => $average) {
                echo "<tr>
                    <td>$position</td>
                    <td>$player</td>
                    <td>" . number_format($average, 2) . "</td>
                  </tr>";
                $position++;
            }
            ?>
        </table>

        <h2>Classifica Reale dei Capocannonieri 2024-2025</h2>
        <table>
            <tr>
                <th>Posizione</th>
                <th>Giocatore</th>
                <th>Gol</th>
            </tr>
            <?php
            // Controlla se ci sono dati disponibili
            if (isset($topScorers['scorers']) && !empty($topScorers['scorers'])) {
                foreach ($topScorers['scorers'] as $index => $scorer) {
                    echo "<tr>
                        <td>" . ($index + 1) . "</td>
                        <td>{$scorer['player']['name']}</td>
                        <td>{$scorer['numberOfGoals']}</td>
                      </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Nessun dato disponibile per la stagione corrente</td></tr>";
            }
            ?>
        </table>

        <h2>Report delle Previsioni sui Capocannonieri</h2>
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
                    <td>Previsione $predictionNumber</td>
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