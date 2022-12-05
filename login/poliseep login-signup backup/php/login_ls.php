<?php
   
    session_start();

    $err=0;
    require_once "config.php";

    $message_error = "Wrong Credentials. Try again.";
   
    $email = $_POST["email"];
    $password = $_POST["password"];
   // $chk=$_POST["logCheck"];
    $tipoUtente = $_POST["tipoUtente"];
    
	$sql = "select count(*) as cntUser, id from UTENTE where email='".$email."' and pass='".$password."'";
	$result = mysqli_query($link,$sql);
    $row = mysqli_fetch_array($result);
    $count = $row['cntUser'];

    $id = $row['id'];
    $idArray = str_split($id, 1);
    $idParteLetterale = $idArray[0] . $idArray[1] . $idArray[2];

    echo "tipo utente";
    echo $tipoUtente;
    echo "parte letterale id:";
    echo $idParteLetterale;
    
    if($count > 0)
    {
       /* $_SESSION['uname'] = $uname;
        $_SESSION["loggedin"]="True";*/
        /*if(isset($chk)) 
        {
        	setcookie ("email",$email,time()+ 3600, '/');
			setcookie ("password",$password,time()+ 3600, '/');
           // echo "ATTIVO";
        }
        else
        {*/
            if($tipoUtente == "DOC")
            {
                if($tipoUtente == $idParteLetterale) echo "OK-DOC";
                else echo "ERRORE";
            }
            else if($tipoUtente == "STU")
            {
                if($tipoUtente == $idParteLetterale) echo "OK-STU";
                else echo "ERRORE";
            }
        //}
        
    }
    else // se entro nell'else l'utente non esiste
    {
       echo "ERRORE";
    }

mysqli_close($link);

?>
