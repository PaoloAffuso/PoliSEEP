<?php
   
    require_once "../config.php";
	session_start();
    
	$random_pin = $_SESSION['random_pin'];
	
	$form_pin = $_POST['form_pin'];
	
	if($random_pin == $form_pin) // l'utente ha inserito il PIN (corretto) che gli è stato inviato via mail
	{
        $_SESSION['uname'] = $uname;
        echo "OK";
    }
	else
	{
        echo "ERRORE";
    }
    

mysqli_close($link);

?>