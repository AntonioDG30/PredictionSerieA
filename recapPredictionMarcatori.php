<?php
// Connessione al database
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

// Recupera i dati del record
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM predictionsMarcatori WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$prediction = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riepilogo Previsione Serie A 2024-2025</title>
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
            height: 100vh;
            overflow: hidden; /* Rimuove lo scroll globale della pagina */
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
            max-width: 90vw;
            max-height: 90vh; /* Limita l'altezza massima */
            width: 100%;
            box-sizing: border-box;
            overflow-y: auto; /* Abilita lo scroll verticale */
        }

        h1 {
            text-align: center;
            color: #1e88e5;
            margin-bottom: 15px;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        p {
            margin: 8px 0;
        }

        .prediction-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .prediction-table th, .prediction-table td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .prediction-table th {
            background-color: #f4f4f4;
            text-align: left;
        }

        button {
            background-color: #1e88e5;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s, transform 0.2s;
            box-sizing: border-box;
            margin-top: 10px;
        }

        button:hover {
            background-color: #1565c0;
            transform: translateY(-3px);
        }

        button:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
<div class="container" id="contentToCapture">
    <h1>Riepilogo Previsione</h1>
    <p><strong>Nome:</strong> <?php echo htmlspecialchars($prediction['first_name']); ?></p>
    <p><strong>Cognome:</strong> <?php echo htmlspecialchars($prediction['last_name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($prediction['email']); ?></p>

    <h2>Classifica Finale</h2>
    <table class="prediction-table">
        <thead>
        <tr>
            <th>Posizione</th>
            <th>Squadra</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <tr>
                <td><?php echo $i; ?>°</td>
                <td><?php echo htmlspecialchars($prediction["scorer$i"]); ?></td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>

    <button id="downloadPDF">Scarica PDF</button>
    <button id="downloadImage">Scarica Immagine</button>
    <a href="index.php"><button id="home">Torna alla Home</button></a>
</div>

<!-- Include i file JavaScript necessari -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.getElementById('downloadPDF').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Aggiungi titolo
        doc.setFontSize(18);
        doc.text('Riepilogo Previsione', 10, 10);

        // Aggiungi dettagli
        doc.setFontSize(12);
        doc.text(`Nome: ${document.querySelector('p strong').nextSibling.nodeValue.trim()}`, 10, 20);
        doc.text(`Cognome: ${document.querySelectorAll('p strong')[1].nextSibling.nodeValue.trim()}`, 10, 30);
        doc.text(`Email: ${document.querySelectorAll('p strong')[2].nextSibling.nodeValue.trim()}`, 10, 40);

        // Aggiungi tabella
        const table = document.querySelector('.prediction-table');
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr => [
            tr.cells[0].textContent,
            tr.cells[1].textContent
        ]);

        doc.autoTable({
            startY: 50,
            head: [['Posizione', 'Squadra']],
            body: rows
        });

        doc.save('previsione.pdf');
    });

    document.getElementById('downloadImage').addEventListener('click', function () {
        const container = document.getElementById('contentToCapture');

        // Salva lo stato originale del contenitore
        const originalStyle = {
            width: container.style.width,
            height: container.style.height,
            maxWidth: container.style.maxWidth,
            maxHeight: container.style.maxHeight,
            overflow: container.style.overflow,
            position: container.style.position,
        };

        // Disabilita lo scroll e forza il contenitore ad espandersi per mostrare tutto il contenuto
        container.style.overflow = 'visible';
        container.style.height = 'auto';
        container.style.maxHeight = 'none';
        container.style.position = 'relative';

        html2canvas(container, {
            useCORS: true,
            scale: 2, // Maggiore qualità dell'immagine
            backgroundColor: null,
            logging: true,
            ignoreElements: el => el.id === 'downloadPDF' || el.id === 'downloadImage' || el.id === 'home' // Ignora i pulsanti
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = imgData;
            link.download = 'previsione.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }).catch(err => {
            console.error('Errore nel rendering dell\'immagine:', err);
        }).finally(() => {
            // Ripristina lo stato originale del contenitore
            container.style.width = originalStyle.width;
            container.style.height = originalStyle.height;
            container.style.maxWidth = originalStyle.maxWidth;
            container.style.maxHeight = originalStyle.maxHeight;
            container.style.overflow = originalStyle.overflow;
            container.style.position = originalStyle.position;
        });
    });


</script>
</body>
</html>
