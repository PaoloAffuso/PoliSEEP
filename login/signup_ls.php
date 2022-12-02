<?php

    require_once "config.php";

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];

    $tipoUtente = $_POST["tipoUtente"]; // dato che arriva dal radio-button
    echo $tipoUtente;

    // verifico che il nickname e/o la mail non siano già in uso

    $sql = "SELECT count(*) as cntUser FROM UTENTE where email='".$email."' or nome='".$nome."'";
    $result = $link -> query($sql);
    $row = mysqli_fetch_array($result);
    $count = $row['cntUser'];

    if($count > 0) // controllo se l'utente esiste già
    {
        $_SESSION['uname'] = $uname;
        echo "ERRORE_CREDENZIALI"; //significa che l'utente esiste già
    }
    else // se l'utente non esiste
    {
        if($password1 != $password2) //password non combaciano
        {
            $_SESSION['uname'] = $uname;
            echo "ERRORE_PASSWORD";
        }
        else // entro in questo else se: utente NON esiste e se password combaciano
        {
            // se nickname/email NON sono in uso, allora l'utente può creare il suo account
            $querySelezione = "SELECT max(id) as massimo FROM UTENTE WHERE id LIKE '$tipoUtente%'";
            $result = $link -> query($querySelezione);
            $row = $result -> fetch_assoc();
            $id = $row['massimo'];

            // calcolo nuovo id
            $idArray = str_split($id, 1);
            echo count($idArray);
            $idParteLetterale = $idArray[0] . $idArray[1] . $idArray[2];
            $idParteNumerica = $idArray[3] . $idArray[4] . $idArray[5];
            $idParteNumerica = $idParteNumerica + 1;
            $id = $idParteLetterale . $idParteNumerica;

            // inserimento dati utente nel DB
            $sql = "INSERT INTO UTENTE (id, nome, email, pass) VALUES ('$id', '$nome', '$email', '$password1')";
            $result = $link -> query($sql);  
            
            echo "OK";
        }

    }

    mysqli_close($link);

?>