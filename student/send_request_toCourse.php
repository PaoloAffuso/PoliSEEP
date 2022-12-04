<?php
    
    include '../config.php';
    session_start();
                 
    // Check connection
    if (mysqli_connect_errno())
        echo "Connessione al database non riuscita: " . mysqli_connect_error();

    $id_corso = $_POST["id_corso"];
    $id_studente = $_SESSION['id_studente'];

    $sql = "INSERT INTO ISCRIZIONE (idUtente, tipoUtente, idCorso, stato) VALUES ('$id_studente', 'STU', '$id_corso', -1)";
    if($result = $link -> query($sql)) echo "OK";
    else echo "ERROR";

    $link -> close();  
?>