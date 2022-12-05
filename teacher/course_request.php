<?php
    
    include '../config.php';
    session_start();
                 
    // Check connection
    if (mysqli_connect_errno())
        echo "Connessione al database non riuscita: " . mysqli_connect_error();

    //$id_corso = $_POST["id_corso"];

    $call=$_POST['call'];
    $id_corso = $_SESSION["idCorso"];
    $id_studente = $_POST['id_studente'];

    			// per il pulsante DECLINE usare questa query:
						/*
							DELETE FROM ISCRIZIONE WHERE idUtente = "$id_utente" AND tipoUtente = 'STU'; 
						*/

						// per il pulsante ACCEPT usare questa query:
						/*
							UPDATE ISCRIZIONE SET stato=1 WHERE idUtente = "$id_utente" AND tipoUtente = 'STU'; 
						*/
    if($call=="acceptRequest") {
        $sql="UPDATE ISCRIZIONE SET stato=1 WHERE idUtente='$id_studente' AND idCorso='$id_corso' AND tipoUtente='STU'";
        //$sql = "INSERT INTO ISCRIZIONE (idUtente, tipoUtente, idCorso, stato) VALUES ('$id_studente', 'STU', '$id_corso', -1)";
        if($result = $link -> query($sql)) echo "OK";
        else echo "ERROR";
    }

    if($call="declineRequest") {
        $sql="DELETE FROM ISCRIZIONE WHERE idUtente = '$id_studente' AND idCorso='$id_corso' AND tipoUtente = 'STU'";
        if($result = $link -> query($sql)) echo "OK";
        else echo "ERROR";
    }

    $link -> close();  
?>