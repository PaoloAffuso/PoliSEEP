<?php
    include '../config.php';
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();

    $fileName=$_FILES['filePdf']['name'];
    $fileSize=$_FILES['filePdf']['size'];

    if(!empty($_FILES['filePdf']['tmp_name']) && file_exists($_FILES['filePdf']['tmp_name'])) {
        $pdf=addslashes(file_get_contents($_FILES['filePdf']['tmp_name']));
        echo $pdf;
        $sql="INSERT INTO files(idCorso, nome, dataOraCaricamento, dimensione, documento) VALUES (1, '$fileName', NOW(), '$fileSize', '$pdf')";

        if($result = $link -> query($sql)) {
            echo "OK";
        }
        else echo "ERROR2";
    }
    else echo "ERROR";

    $link -> close();
?>