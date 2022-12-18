<?php
   
    require_once "../config.php";
	session_start();
   
    $email = $_POST["email"];
    
	$sql = "select count(*) as cntUser from UTENTE where email='".$email."'";
	$result = mysqli_query($link,$sql);
    $row = mysqli_fetch_array($result);
    $count = $row['cntUser'];
	
	$random_pin = rand(1000, 9999);
	$_SESSION['random_pin'] = $random_pin;
    $_SESSION['email'] = $email;
	
    if($count > 0) // ha trovato l'utente e posso inviargli la mail di ripristino password
	{
        $_SESSION['uname'] = $uname;
		$subject = "EMO - MUSIC Web App -- PASSWORD RECOVERY";
		$message = "Hello, use the PIN '".$random_pin."' to recover your password. ";
		$headers = "EMO - MUSIC Web App (Management System) says: ";
		mail($email, $subject, $message, $headers);
		echo "OK"; 
    }
	else
	{
        echo "ERRORE";
    }

mysqli_close($link);

?>
