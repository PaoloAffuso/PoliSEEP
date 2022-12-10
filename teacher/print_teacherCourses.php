<?php

    include '../config.php';
    session_start();
                
    // Check connection
    if (mysqli_connect_errno())
        echo "Connessione al database non riuscita: " . mysqli_connect_error();

    $array_nomeCorso = array();
    $array_numQS = array();
    $array_numQNS = array();

    $id_docente = $_SESSION['id_utente'];

    $stringa = "";
    $str_nomeCorso = "";
    $str_numQS = "";
    $str_numQNS = "";

    $sql = "SELECT CORSO.nome as nome_corso
            FROM ISCRIZIONE INNER JOIN CORSO ON ISCRIZIONE.idCorso = CORSO.id
            WHERE ISCRIZIONE.idUtente = '$id_docente' AND ISCRIZIONE.stato = 0 AND ISCRIZIONE.tipoUtente = 'DOC'";

    $result = $link -> query($sql);

    while($row = $result->fetch_assoc()) 
        array_push($array_nomeCorso, $row['nome_corso']);

    for($i=0;$i<count($array_nomeCorso);$i++)
    {
        $sql = "SELECT count(TAKE_QUIZ.idUtente) as num_quiz_sostenuti
                FROM TAKE_QUIZ INNER join utente on take_quiz.idUtente = utente.id and take_quiz.tipoUtente = utente.tipo
                INNER JOIN iscrizione on utente.id = iscrizione.idUtente
                INNER JOIN CORSO ON iscrizione.idCorso = corso.id
                WHERE ISCRIZIONE.idUtente = '$id_docente' AND ISCRIZIONE.tipoUtente = 'DOC' and corso.nome='$array_nomeCorso[$i]' and take_quiz.stato=1";

        $result = $link -> query($sql);
        $row = mysqli_fetch_array($result);
        //echo $row['num_quiz_sostenuti'];
        echo $array_nomeCorso[$i];
        array_push($array_numQS, $row['num_quiz_sostenuti']);
    }

    for($i=0;$i<count($array_nomeCorso);$i++)
    {
        $sql = "SELECT count(TAKE_QUIZ.idUtente) as num_quiz_non_sostenuti
                FROM TAKE_QUIZ INNER join utente on take_quiz.idUtente = utente.id and take_quiz.tipoUtente = utente.tipo
                INNER JOIN iscrizione on utente.id = iscrizione.idUtente
                INNER JOIN CORSO ON iscrizione.idCorso = corso.id
                WHERE ISCRIZIONE.idUtente = '$id_docente' AND ISCRIZIONE.tipoUtente = 'DOC' and corso.nome='$array_nomeCorso[$i]' and take_quiz.stato=-1";

        $result = mysqli_query($link,$sql);
        $row = mysqli_fetch_array($result);
        array_push($array_numQNS, $row['num_quiz_non_sostenuti']);
    }
    
    for($i=0;$i<count($array_nomeCorso);$i++)
    {
        $str_nomeCorso = $str_nomeCorso.$array_nomeCorso[$i]."|||";
        $str_numQS = $str_numQS.$array_numQS[$i]."|||";
        $str_numQNS = $str_numQNS.$array_numQNS[$i]."|||";
    }

    $stringa = $str_nomeCorso.":::".$str_numQS.":::".$str_numQNS; 

    echo $stringa;

?>