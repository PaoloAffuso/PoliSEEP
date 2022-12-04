<?php
    
    include '../config.php';
    session_start();
                 
    // Check connection
    if (mysqli_connect_errno())
        echo "Connessione al database non riuscita: " . mysqli_connect_error();

    $id_corso = $_POST["id_corso"];
    $id_studente = $_SESSION['id_studente'];

    			// per il pulsante DECLINE usare questa query:
						/*
							DELETE FROM ISCRIZIONE WHERE idUtente = "$id_utente" AND tipoUtente = 'STU'; 
						*/

						// per il pulsante ACCEPT usare questa query:
						/*
							UPDATE ISCRIZIONE SET stato=1 WHERE idUtente = "$id_utente" AND tipoUtente = 'STU'; 
						*/
/*
    $sql = "INSERT INTO ISCRIZIONE (idUtente, tipoUtente, idCorso, stato) VALUES ('$id_studente', 'STU', '$id_corso', -1)";
    if($result = $link -> query($sql)) echo "OK";
    else echo "ERROR";*/

    $link -> close();  
?>