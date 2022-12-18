<?php
    include './config.php';
    session_start();
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();

    $messaggio = $_POST['messaggio'];
    $type = $_POST['type'];

    if($type=="DOC")
    {
        $id_docente = $_SESSION['id_utente'];
        $id_studente = $_POST['idStudenteHidden'];
        $querySelezione = "SELECT max(num) as max_num from chat where idDocente = '$id_docente' and idStudente = '$id_studente'";
        $result = $link -> query($querySelezione);

        if(is_null($result))
        {
            $num = 1;
        }
        else
        {
            $row = $result -> fetch_assoc();
            $num = $row['max_num'] + 1; 
        }

       
        $sql = "INSERT INTO chat (idDocente, tipoDoc, idStudente, tipoStu, messaggio, num, stato, tipo) values ('$id_docente', 'DOC', '$id_studente', 'STU', '$messaggio', '$num', 1, 'DOC')";
    }    
    else
        $sql = "SELECT nome from utente where nome like '%$search%' or email like '%$search%' and tipo='STU'";

    if($result = $link -> query($sql)) {
        echo "<p>".$messaggio."</p></br>";
    }
    else echo "ERROR";

    $link -> close();
?>