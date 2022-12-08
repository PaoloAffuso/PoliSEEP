<?php
    include '../config.php';
    session_start();
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();

    $idCorso=$_SESSION['idCorso'];

    $id_docente = $_SESSION['id_utente'];

    $sql = "SELECT 
                UTENTE.nome as docenteCorso, 
                CORSO.nome as nome, 
                CORSO.copertina as immagineCorso, 
                CORSO.obiettivi as obiettivoCorso, 
                CORSO.descrizione as descrizioneCorso, 
                CORSO.verifica as verificaCorso, 
                CORSO.cfu as cfuCorso
            FROM CORSO INNER JOIN ISCRIZIONE ON CORSO.id = ISCRIZIONE.idCorso
                       INNER JOIN UTENTE ON ISCRIZIONE.idUtente = UTENTE.id AND ISCRIZIONE.tipoUtente = UTENTE.tipo
            WHERE ISCRIZIONE.idCorso='$idCorso' AND ISCRIZIONE.stato=0";

	$result = $link -> query($sql);
	$row = $result->fetch_assoc();

    if(isset($_POST['nomeCorso'])) $nomeCorso=$_POST['nomeCorso'];
    else $nomeCorso=$row['nome'];

    if(isset($_POST['docenteCorso'])) $docenteCorso=$_POST['docenteCorso'];
    else $docenteCorso=$row['docenteCorso'];

    if(isset($_POST['obiettivoCorso'])) $obiettivoCorso=$_POST['obiettivoCorso'];
    else $obiettivoCorso=$row['obiettivoCorso'];

    if(isset($_POST['descrizioneCorso'])) $descrizioneCorso=$_POST['descrizioneCorso'];
    else $descrizioneCorso=$row['descrizioneCorso'];

    if(isset($_POST['verificaCorso'])) $verificaCorso=$_POST['verificaCorso'];
    else $verificaCorso=$row['verificaCorso'];

    if(isset($_POST['cfuCorso'])) $cfuCorso=$_POST['cfuCorso'];
    else $cfuCorso=$row['cfuCorso'];

    echo $cfuCorso;

    echo $row['cfuCorso'];

    if(isset($_FILES['immagineCorso']))
    {
        if(!empty($_FILES['immagineCorso']['tmp_name']) && file_exists($_FILES['immagineCorso']['tmp_name'])) {
        
            $img=addslashes(file_get_contents($_FILES['immagineCorso']['tmp_name']));
    
            $sql="UPDATE CORSO SET nome='$nomeCorso', copertina='$img', obiettivi='$obiettivoCorso', descrizione='$descrizioneCorso', verifica='$verificaCorso', cfu='$cfuCorso' WHERE id ='$idCorso'";
    
            if($result = $link -> query($sql)) 
            {
                echo "OK";
            }
            else echo "ERROR";
        }
    } 
    else 
    {
        $sql="UPDATE CORSO SET nome='$nomeCorso', obiettivi='$obiettivoCorso', descrizione='$descrizioneCorso', verifica='$verificaCorso', cfu='$cfuCorso' WHERE id ='$idCorso'";
        if($result = $link -> query($sql)) 
        {
            echo "OK";
        }
        else echo "ERROR2";
    }

    

    
   // else echo "ERROR";

    $link -> close();
?>