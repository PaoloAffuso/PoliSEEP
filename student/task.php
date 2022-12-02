<?php
    include '../config.php';
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();

    $call=$_POST['call'];
    
    $date = date('Y-m-d');
    $res = $link -> query("SELECT max(num) from task where idUtente=1;"); //idUtente va inserito in sessione a partire dal login. Inserire anche email per controllo tipo utente
    $res = $res->fetch_array();
    $num=intval($res[0])+1;
    if($call == "insertTask") {
        $descrizione = $_POST['descrizione'];
        if($result = $link -> query("INSERT INTO task(idUtente, tipoUtente, num, descrizione, dataIns, stato) VALUES (1,'STU',$num, '$descrizione', '$date', 0)"))
            echo "OK";
        else echo "ERROR";
    }

    if($call == "changeStatus") {
        $descrizione = $_POST['descrizione'];
        $stato=$_POST['stato'];
        if ($stato == "true")
            $sql="UPDATE task SET stato=1 WHERE idUtente=1 AND descrizione='$descrizione'";
        else $sql="UPDATE task SET stato=0 WHERE idUtente=1 AND descrizione='$descrizione'";
        if($result = $link -> query($sql))
            echo "OK";
        else echo "ERROR";
    }

    if($call == "delSingleTask") {
        $descrizione = $_POST['descrizione'];
        if($result = $link -> query("DELETE FROM task WHERE descrizione='$descrizione'"))
            echo "OK";
        else echo "ERROR";
    }

    if($call == "delAllTask") {
        if($result = $link -> query("DELETE FROM task WHERE idUtente=1"))
            echo "OK";
        else echo "ERROR";
    }
    $link -> close();
?>