<?php
    include '../config.php';
    session_start();
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();

    $call=$_POST['call'];
    $idUtente=$_SESSION['id_utente'];
    
    $date = date('Y-m-d');
    $res = $link -> query("SELECT max(num) from task where idUtente='$idUtente';"); //idUtente va inserito in sessione a partire dal login. Inserire anche email per controllo tipo utente
    $res = $res->fetch_array();
    $num=intval($res[0])+1;
    if($call == "insertTask") {
        $descrizione = $_POST['descrizione'];
        if($result = $link -> query("INSERT INTO task(idUtente, tipoUtente, num, descrizione, dataIns, stato) VALUES ('$idUtente','STU',$num, '$descrizione', '$date', 0)"))
            echo "OK-".$num;
        else echo "ERROR";
    }

    if($call == "changeStatus") {
        $num = $_POST['num'];
        $stato=$_POST['stato'];
        if ($stato == "true")
            $sql="UPDATE task SET stato=1 WHERE idUtente='$idUtente' AND num='$num'";
        else $sql="UPDATE task SET stato=0 WHERE idUtente='$idUtente' AND num='$num'";
        if($result = $link -> query($sql))
            echo "OK";
        else echo $result;//"ERROR";
    }

    if($call == "delSingleTask") {
        $num = $_POST['num'];
        if($result = $link -> query("DELETE FROM task WHERE idUtente='$idUtente' AND num='$num'"))
            echo "OK";
        else echo "ERROR";
    }

    if($call == "delAllTask") {
        if($result = $link -> query("DELETE FROM task WHERE idUtente='$idUtente'"))
            echo "OK";
        else echo "ERROR";
    }

    if($call == "getPendingTask") {
        $sql="select count(*) as cnt from task where idUtente='$idUtente' AND stato=0;";
        if($result = $link -> query($sql)) {
            $row = mysqli_fetch_array($result);
            echo $row['cnt'];
        }
        else echo "ERROR";
    }
    $link -> close();
?>