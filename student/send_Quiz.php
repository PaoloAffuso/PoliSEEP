<?php
    include '../config.php';
    session_start();
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();

    //$idCorso=$_SESSION['idCorso'];

    $id_studente=$_SESSION['id_utente'];

    $id_corso=$_POST['id_corso'];
    $numQuiz = $_POST['num'];

    $sql="UPDATE TAKE_QUIZ set stato=1 where idCorsoQuiz='$id_corso' and idUtente='$id_studente' and numQuiz='$numQuiz'";
    
    if($result = $link -> query($sql))
        echo "OK"; 
    else 
        echo $result;

    $link -> close();
?>