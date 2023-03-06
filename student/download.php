<?php
    include '../config.php';
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();
    
    $idCorso=$_POST['idCorso'];
    $nome=$_POST['nome'];

    $sql="SELECT documento FROM files WHERE idCorso=$idCorso AND nome='$nome'";

    if($result = $link -> query($sql)) {
        $row = mysqli_fetch_array($result);
        echo base64_encode($row['documento']);
    }
    else echo "ERROR";

    $link -> close();
?>