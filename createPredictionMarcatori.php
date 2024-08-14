<?php
include_once 'config.php';
// Dati per la connessione al database
$servername = DB_SERVER;
$username = DB_USERNAME;
$password = DB_PASSWORD;
$dbname = DB_NAME;

// Creazione della connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Funzione per calcolare la stagione
function getSeason() {
    $currentYear = date("Y");
    $currentMonth = date("m");

    if ($currentMonth >= 8) { // Agosto o successivo, la stagione è l'anno corrente e il successivo
        return $currentYear . '-' . ($currentYear + 1);
    } else { // Gennaio a Luglio, la stagione è l'anno corrente e il precedente
        return ($currentYear - 1) . '-' . $currentYear;
    }
}

// Se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);

    // Calcola la stagione attuale
    $season = getSeason();

    // Assicurati che tutte le variabili siano definite e non nulle
    $scorers = [];
    for ($i = 1; $i <= 5; $i++) {
        $scorers[] = isset($_POST["scorer$i"]) ? $conn->real_escape_string($_POST["scorer$i"]) : '';
    }

    // Prepara la query SQL per inserire i dati
    $sql = "INSERT INTO predictionsMarcatori (
        first_name, last_name, email, 
        scorer1, scorer2, scorer3, scorer4, scorer5,
        season, created_at
    ) VALUES (
        '$firstName', '$lastName', '$email',
        '{$scorers[0]}', '{$scorers[1]}', '{$scorers[2]}', '{$scorers[3]}', '{$scorers[4]}',
        '$season', NOW()
    )";

    // Esegui la query
    if ($conn->query($sql) === TRUE) {
        // Ottiene l'ID dell'ultimo inserimento
        $last_id = $conn->insert_id;
        // Reindirizza alla pagina di riepilogo
        header("Location: recapPredictionMarcatori.php?id=$last_id");
        exit;
    } else {
        echo "Errore: " . $conn->error;
    }

    // Chiude la connessione
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Previsione Serie A 2024-2025</title>
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
            height: 100vh;
            overflow: hidden; /* Nasconde eventuali barre di scorrimento del body */
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            width: 100%;
            box-sizing: border-box;
            max-height: 90vh; /* Limita l'altezza massima del contenitore */
            overflow-y: auto; /* Aggiunge lo scroll verticale se necessario */
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            text-align: center;
            color: #1e88e5;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }

        input[type="text"]:focus, input[type="email"]:focus, select:focus {
            border-color: #1e88e5;
            outline: none;
            box-shadow: 0 0 8px rgba(30, 136, 229, 0.2);
        }

        button {
            background-color: #1e88e5;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s, transform 0.2s;
            box-sizing: border-box;
        }

        button:hover {
            background-color: #1565c0;
            transform: translateY(-3px);
        }

        button:active {
            transform: translateY(0);
        }

        /* Stili per migliorare l'aspetto delle dropdown */
        select {
            background: #f9f9f9;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath d='M7 10l5 5 5-5H7z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 15px;
        }

        /* Responsività */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                margin: 10px;
            }

            input[type="text"], input[type="email"], select, button {
                font-size: 14px;
                padding: 10px;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Crea la tua Previsione</h1>
    <form id="predictionForm" method="POST">
        <div class="form-group">
            <label for="firstName">Nome</label>
            <input type="text" id="firstName" name="firstName" placeholder="Inserisci il tuo nome" required>
        </div>
        <div class="form-group">
            <label for="lastName">Cognome</label>
            <input type="text" id="lastName" name="lastName" placeholder="Inserisci il tuo cognome" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Inserisci la tua email" required>
        </div>

        <div class="form-group">
            <label for="season">Stagione</label>
            <!-- Visualizza la stagione calcolata come testo -->
            <p id="seasonDisplay"><?php
include_once 'config.php'; echo getSeason(); ?></p>
            <!-- Il campo di input è rimosso perché non è modificabile -->
        </div>

        <h2>Top 5 Marcatori</h2>
        <div id="scorerSelections"></div>

        <button type="submit">Invia Previsione</button>
    </form>
</div>

<script>
    // Lista dei marcatori
    const scorers = [
        "Albert Guðmundsson", "Álvaro Morata", "Ademola Lookman", "Artem Dovbyk", "Christian Pulisic",
        "Dušan Vlahović", "Duván Zapata", "Lautaro Martínez", "Mateo Retegui", "Mattia Zaccagni",
        "Moise Kean", "Rafael Leão", "Romelu Lukaku / Victor Osimhen", "Thijs Dallinga", "Valentín Castellanos"
    ];


    // Funzione per generare le selezioni dei marcatori
    function generateScorerSelections() {
        const scorerSelections = document.getElementById('scorerSelections');

        for (let i = 1; i <= 5; i++) {
            const div = document.createElement('div');
            div.className = 'form-group';

            const label = document.createElement('label');
            label.setAttribute('for', `scorer${i}`);
            label.textContent = `Marcatore ${i}`;

            const select = document.createElement('select');
            select.id = `scorer${i}`;
            select.name = `scorer${i}`;
            select.required = true;

            const optionDefault = document.createElement('option');
            optionDefault.value = '';
            optionDefault.textContent = 'Seleziona un marcatore';
            optionDefault.disabled = true;
            optionDefault.selected = true;
            select.appendChild(optionDefault);

            scorers.forEach(scorer => {
                const option = document.createElement('option');
                option.value = scorer;
                option.textContent = scorer;
                select.appendChild(option);
            });

            select.addEventListener('change', function() {
                updateScorerOptions();
            });

            div.appendChild(label);
            div.appendChild(select);
            scorerSelections.appendChild(div);
        }
    }

    // Funzione per aggiornare le opzioni dei marcatori in base alle selezioni precedenti
    function updateScorerOptions() {
        const selectedScorers = [];

        for (let i = 1; i <= 5; i++) {
            const select = document.getElementById(`scorer${i}`);
            if (select.value) {
                selectedScorers.push(select.value);
            }
        }

        for (let i = 1; i <= 5; i++) {
            const select = document.getElementById(`scorer${i}`);
            const options = select.querySelectorAll('option');

            options.forEach(option => {
                if (option.value && selectedScorers.includes(option.value) && option.value !== select.value) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        }
    }

    // Esecuzione della funzione al caricamento della pagina
    document.addEventListener('DOMContentLoaded', function() {
        generateScorerSelections();
    });
</script>
</body>
</html>
