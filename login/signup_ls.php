<?php

    require_once "../config.php";

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];

    $tipoUtente = $_POST["tipoUtente"]; // dato che arriva dal radio-button
    //echo $tipoUtente;

    // verifico che il nickname e/o la mail non siano già in uso

    $sql = "SELECT count(*) as cntUser FROM UTENTE where email='".$email."' or nome='".$nome."'";
    $result = $link -> query($sql);
    $row = mysqli_fetch_array($result);
    $count = $row['cntUser'];

    if($count > 0) // controllo se l'utente esiste già
    {
        $_SESSION['uname'] = $email;
        echo "ERRORE_CREDENZIALI"; //significa che l'utente esiste già
    }
    else // se l'utente non esiste
    {
        if($password1 != $password2) //password non combaciano
        {
            $_SESSION['uname'] = $email;
            echo "ERRORE_PASSWORD";
        }
        else // entro in questo else se: utente NON esiste e se password combaciano
        {
            // se nickname/email NON sono in uso, allora l'utente può creare il suo account
            $querySelezione = "SELECT max(id) as massimo FROM UTENTE WHERE tipo='$tipoUtente'";
            $result = $link -> query($querySelezione);
            $row = $result -> fetch_assoc();
            $id = $row['massimo'];
            $id=$id+1;

            // inserimento dati utente nel DB
            $sql = "INSERT INTO UTENTE (id, nome, email, pass, tipo) VALUES ($id,'$nome', '$email', '$password1', '$tipoUtente')";
            $result = $link -> query($sql);  
            
            echo "OK";
        }

    }

    mysqli_close($link);

?>