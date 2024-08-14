<?php
// Dati per la connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_predictionseriea
";

// Creazione della connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Recupera tutte le previsioni dal database
$sql = "SELECT *
        FROM predictionsMarcatori ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutte le Previsioni</title>
    <style>
        /* Stili di base */
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
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            width: 100%;
            box-sizing: border-box;
            animation: fadeIn 1s ease-in-out;
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
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #1e88e5;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
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

        /* Stili per lo scroll */
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        /* Stili di risposta */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 20px;
            }

            th, td {
                font-size: 14px;
            }

            a.button {
                font-size: 14px;
                padding: 10px 15px;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Tutte le Previsioni</h1>

    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Email</th>
                <th>Data</th>
                <th>Stagione</th>
                <th>Azioni</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['first_name']) . "</td>
                            <td>" . htmlspecialchars($row['last_name']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars(date('d/m/Y H:i', strtotime($row['created_at']))) . "</td>
                            <td>" . htmlspecialchars($row['season']) . "</td>
                            <td><a href='recapPredictionMarcatori.php?id=" . $row['id'] . "' class='button'>Visualizza</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Nessuna previsione trovata.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <a href="index.php" class="button">Torna alla Home</a>
    <a href="rankingMarcatori.php" class="button">Classifica partecipanti</a>
</div>
</body>
</html>

<?php
$conn->close();
?>
