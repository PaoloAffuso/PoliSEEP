<?php
   
    session_start();

    $err=0;
    require_once "../config.php";
   
    $email = $_POST["email"];
    $password = $_POST["password"];
    $tipoUtente = $_POST["tipoUtente"];
    //$mat = $_POST["mat"]; // matricola utente = id

    $chk=$_POST["logCheck"];

    //$_SESSION['id_utente'] = $mat;
    
    // QUERY: verifica che l'utente esista nel db
	$sql = "select count(*) as cntUser, id, email, password, tipo from UTENTE where email='".$email."' and password='".$password."' and tipo='$tipoUtente'";
	$result = mysqli_query($link,$sql);
    $row = mysqli_fetch_array($result);
    $count = $row['cntUser'];
    
    if($count > 0) // entro nell'if se l'utente esiste
    {
        $_SESSION['uname'] = $uname;
        $_SESSION["loggedin"]="True";
        if(isset($chk)) 
        {
        	setcookie ("email",$email,time()+ 3600, '/');
			setcookie ("password",$password,time()+ 3600, '/');
            echo "ATTIVO";
        }
        else
        {
            if($tipoUtente == "DOC") echo "OK-DOC";
            else if($tipoUtente == "STU") echo "OK-STU";
        }      
    }
    else echo "ERRORE"; // se entro nell'else l'utente non esiste

mysqli_close($link);

?>
