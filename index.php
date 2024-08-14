<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serie A 2024-2025 - Previsioni</title>
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
            overflow: hidden;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            box-sizing: border-box;
            animation: fadeIn 1s ease-in-out;
            text-align: center;
        }

        h1 {
            color: #1e88e5;
            margin-bottom: 20px;
            font-size: 24px;
        }

        p {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
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
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #1565c0;
            transform: translateY(-3px);
        }

        button:active {
            transform: translateY(0);
        }

        /* Stili di risposta */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            button {
                font-size: 16px;
                padding: 10px;
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
    <h1>Serie A 2024-2025 - Previsioni</h1>
    <p>
        Benvenuto nel sistema di previsioni per la Serie A 2024-2025 ideato da
        <a href="https://www.instagram.com/antoniodg30/">AntonioDG30</a>.Inizia a creare la tua previsione.
    </p>
    <a href="createPredictionSerieA.php">
        <button>Crea la tua Previsione Classifica Serie A</button>
    </a>
    <a href="allPredictionsSerieA.php">
        <button>Visualizza Tutte le Previsioni Classifica Serie A</button>
    </a>
    <a href="createPredictionMarcatori.php">
        <button>Crea la tua Previsione Marcatori</button>
    </a>
    <a href="allpredictionsmarcatori.php">
        <button>Visualizza Tutte le Previsioni Marcatori</button>
    </a>
    <a href="predictionSerieAStatistiche.php">
        <button>Visualizza Le Statistiche Delle Previsioni Classifica Serie A</button>
    </a>
    <a href="predictionMarcatoriStatistiche.php">
        <button>Visualizza Le Statistiche Delle Previsioni Marcatori</button>
    </a>
</div>
</body>
</html>
