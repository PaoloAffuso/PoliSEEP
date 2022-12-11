<?php
    include '../config.php';
    session_start();
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();

    $itsOk = true;

    $idCorso=$_SESSION['idCorso'];

    $linkGF = $_POST["linkQuiz"]; 
    $numCap = $_POST['numeroCap'];  
    $titolo = $_POST['nomeQuiz']; 

    $querySelezione = "SELECT max(num) as massimo FROM QUIZ where idCorso='$idCorso'";
    $result = $link -> query($querySelezione);
    $row = $result -> fetch_assoc();
    $num = $row['massimo'] + 1;

    $sql="INSERT INTO QUIZ(idCorso, num, linkGF, numCap, titolo) VALUES ('$idCorso', '$num', '$linkGF', '$numCap', '$titolo')";

    if($result = $link -> query($sql)) $itsOk = true; 
    else $itsOk = false; 

    $sql1="SELECT idUtente as id_studente FROM ISCRIZIONE where tipoUtente='STU' and idCorso='$idCorso'"; // estrae un vettore di idStudenti
    if($result1 = $link -> query($sql1)) $itsOk = true; 
    else $itsOk = false; 

    while($row = $result1->fetch_assoc())
    {
        $sql2="INSERT INTO TAKE_QUIZ(idUtente, tipoUtente, idCorsoQuiz, numQuiz, stato) VALUES ('".$row['id_studente']."', 'STU', '$idCorso', '$num', -1)";
        
        if($result2 = $link -> query($sql2)) $itsOk = true; 
        else {$itsOk = false; break;}
    }
    
    if($itsOk) echo "OK";
    else echo "ERROR";

    $link -> close();
?>