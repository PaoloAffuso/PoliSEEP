<?php
   
    session_start();

    $err=0;
    require_once "../config.php";
   
    $email = $_POST["email"];
    $password = $_POST["password"];

    $email_array = str_split($email);
    $trovato = true;

    if(isset($_POST["logCheck"]))
        $chk=$_POST["logCheck"];
    
    // verifico che la mail sia nel formato PoliBa e se ha loggato uno STUDENTE o un DOCENTE
    $domain=explode("@", $email);
    if($domain[1]==="studenti.poliba.it") $tipoUtente = "STU";
    else if($domain[1]==="poliba.it") $tipoUtente = "DOC";
    else // formato non accettabile
    {
        echo "FORMATO"; 
        $trovato = false;
    } 

    if($trovato)
    {
        // QUERY: verifica che l'utente esista nel db
        $sql = "select count(*) as cntUser, id, email, pass, tipo from UTENTE where email='".$email."' and pass='".$password."' and tipo='$tipoUtente'";
        $result = mysqli_query($link,$sql);
        $row = mysqli_fetch_array($result);
        $count = $row['cntUser'];

        if($count > 0) // entro nell'if se l'utente esiste
        {
            $_SESSION['uname'] = $email;
            $_SESSION["loggedin"]="True";
            $_SESSION['id_utente']=$row['id'];
            if(isset($chk)) 
            {
                setcookie ("email",$email,time()+ 3600, '/');
                setcookie ("password",$password,time()+ 3600, '/');
                //echo "ATTIVO";
            }
            if($tipoUtente == "DOC") {
                $_SESSION['tipoUtente']="DOC";
                echo "OK-DOC";
            }
            else if($tipoUtente == "STU") {
                $_SESSION['tipoUtente']="STU";
                echo "OK-STU";  
            }
        }
        else echo "ERRORE"; // se entro nell'else l'utente non esiste
    }
    

mysqli_close($link);

?>
