<?php
    include './config.php';
    session_start();
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();

    if(isset($_POST['get_info']))
    {
        $id_studente_cliccato = $_POST['id_studente'];
        $id_docente = $_SESSION['id_utente'];
        
        $querySelezione = "SELECT nome, propic from utente where id = '$id_studente_cliccato' and tipo='STU'";
        $result = $link -> query($querySelezione);
        $row = $result -> fetch_assoc();

        $queryup = "UPDATE chat set stato=0 where idStudente='$id_studente_cliccato' and idDocente='$id_docente'";
        $resultup = $link -> query($queryup);

        echo "<img src='data:image/gif;base64,".base64_encode($row['propic'])."'id='photo'>
                <div class='details'>
                <span id='nomeUtenteAttivo'>
                    ".$row['nome']."
                </span>
                <p>Active now</p>
            </div>";
    }
    else
    {
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
    
    }
    
    $link -> close();
?>