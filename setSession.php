<?php
    include '../config.php';
    session_start();
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();
    
    if(isset($_POST['id_corso']))
        $_SESSION['idCorso']=$_POST['id_corso'];

    if(isset($_POST['nome_corso']))
        $_SESSION['nome_corso']=$_POST['nome_corso'];

    $link -> close();
?>