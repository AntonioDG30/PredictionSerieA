
# Prediction Serie A

## Descrizione del Progetto
**Prediction Serie A** è un'applicazione web progettata per gestire e visualizzare previsioni e statistiche relative alla Serie A italiana. L'applicazione consente agli utenti di creare, visualizzare e analizzare previsioni sui risultati delle partite e sui marcatori.

## Requisiti di Sistema
- **PHP** 7.4 o superiore
- **MySQL** 5.7 o superiore
- **Composer** (facoltativo, per la gestione delle dipendenze PHP)
- **Node.js** (per la gestione dei pacchetti e la compilazione di asset front-end)
- **Web server** come Apache o Nginx

## Istruzioni per l'Installazione
1. **Clona il repository**:
   ```bash
   git clone https://github.com/AntonioDG30/PredictionSerieA.git
   cd PredictionSerieA
   ```

2. **Installa le dipendenze PHP**:
   Se usi Composer, puoi installare le dipendenze con:
   ```bash
   composer install
   ```

3. **Installa le dipendenze Node.js**:
   ```bash
   npm install
   ```

4. **Configura l'ambiente**:
   - Copia il file `config.php` di esempio e personalizzalo con le tue credenziali di accesso al database e le chiavi API.
   - Aggiungi `config.php` al file `.gitignore` per evitare che venga incluso nei commit.

## Configurazione del Progetto
- **Database**: Assicurati di avere un database MySQL configurato. Importa lo schema del database utilizzando il file SQL fornito (se disponibile).
- **Chiavi API**: Inserisci le tue chiavi API nel file `config.php` creato nella sezione di configurazione. Queste chiavi sono necessarie per il funzionamento corretto di alcune funzionalità dell'applicazione.

## Esecuzione del Progetto
1. **Compilazione degli asset front-end**:
   ```bash
   npm run build
   ```

2. **Avvia il server**:
   Configura il tuo web server (Apache, Nginx, ecc.) per servire i file del progetto. Se stai utilizzando un server di sviluppo PHP integrato, puoi avviarlo con:
   ```bash
   php -S localhost:8000
   ```

3. **Accedi all'applicazione**:
   Visita `http://localhost:8000` nel tuo browser per iniziare a utilizzare l'applicazione.

## Struttura delle Cartelle
- **/PredictionSerieA**: Contiene tutti i file principali del progetto.
- **/PredictionSerieA/config.php**: File di configurazione per le credenziali del database e le chiavi API.
- **/PredictionSerieA/functions.php**: File con funzioni PHP utilizzate in tutto il progetto.
- **/PredictionSerieA/**/*.php**: File PHP che gestiscono varie funzionalità dell'applicazione.
- **/PredictionSerieA/webpack.config*.js**: File di configurazione per Webpack, utilizzati per la gestione degli asset front-end.

## Sicurezza
- **Protezione delle chiavi API**: Le chiavi API sono memorizzate nel file `config.php`, che è escluso dal versionamento grazie al file `.gitignore`.
- **Sanitizzazione dell'input**: Assicurati che tutti i dati inseriti dagli utenti siano correttamente sanitizzati e validati per prevenire vulnerabilità come l'iniezione SQL.

## Contributi
Contributi, suggerimenti e segnalazioni di bug sono i benvenuti! Puoi fare un fork del progetto, creare una nuova branch per le tue modifiche e inviare una pull request.

## Licenza
Questo progetto è distribuito sotto la licenza MIT. Vedi il file `LICENSE.txt` per ulteriori dettagli.
