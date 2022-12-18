<?php
   
    require_once "../config.php";
	session_start();

	$password = $_POST["password"];
    
	$email = $_SESSION['email'];
	
	$sql = "update UTENTE set password = '".$password."' where email='".$email."'";
	$result = mysqli_query($link,$sql);
    $row = mysqli_fetch_array($result);
    
	echo "OK";

mysqli_close($link);

?>
