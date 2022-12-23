<?php
    include '../config.php';
    session_start();
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();

    //$idCorso=$_SESSION['idCorso'];

    $querySelezione = "SELECT max(id) as massimo FROM CORSO";
    $result = $link -> query($querySelezione);
    $row = $result -> fetch_assoc();
    $idCorso = $row['massimo'] + 1;

    $id_docente = $_SESSION['id_utente'];

    $fileName=$_FILES['immagineCorso']['name'];
    $nomeCorso=$_POST['nomeCorso'];
    $cfuCorso=$_POST['cfuCorso'];
    $docenteCorso=$_POST['docenteCorso'];
    $obiettivoCorso=$_POST['obiettivoCorso'];
    $descrizioneCorso=$_POST['descrizioneCorso'];
    $verificaCorso=$_POST['verificaCorso'];

    if(!empty($_FILES['immagineCorso']['tmp_name']) && file_exists($_FILES['immagineCorso']['tmp_name'])) {
        
        $img=addslashes(file_get_contents($_FILES['immagineCorso']['tmp_name']));

        $sql="INSERT INTO CORSO(id, nome, copertina, obiettivi, descrizione, verifica, cfu) VALUES ('$idCorso','$nomeCorso','$img','$obiettivoCorso','$descrizioneCorso','$verificaCorso','$cfuCorso')";

        if($result = $link -> query($sql)) {
            echo "OK";
        }
        else echo "ERROR2";

        $sql="INSERT INTO ISCRIZIONE(idUtente, tipoUtente, idCorso, stato) VALUES ('$id_docente','DOC', '$idCorso', 0)";

        if($result = $link -> query($sql)) {
            echo "OK";
        }
        else echo "ERROR2";
    }
    else echo "ERROR";

    $link -> close();
?>