<?php
// si definiscono le variabili per la connessione al database in locale
// quando tale file è caricato su Altervista, possiede i parametri relativi al database in remoto 
define('DB_SERVER', 'localhost:3308'); // indirizzo server
define('DB_USERNAME', 'root'); // username che si utilizza per accedere al database
define('DB_PASSWORD', ''); // password che si utilizza per accedere al database
define('DB_NAME', 'poliseep'); // nome del database

$link=mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME); // connessione al database con i parametri precedentemente definiti

// se la connessione non va a buon fine mostra l'errore
if($link===false){
    die("ERRORE: Impossibile connettersi. ".mysqli_connect_error());
}

?>