<?php
    include './config.php';
    session_start();
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();

    $type=$_POST['type'];
    $search=$_POST['search'];
    $final="";

    if($type=="DOC")
        $querySelezione = "SELECT nome from utente where nome like '%$search%' or email like '%$search%' and tipo='DOC'";
    else
        $querySelezione = "SELECT nome from utente where nome like '%$search%' or email like '%$search%' and tipo='STU'";

    
    $result = $link -> query($querySelezione);
    while($row = $result -> fetch_assoc()) {
        $final=$final."<a href='#'>
        <div class='content'>
            <img src='../images/student_/usrimg.png' alt=''>
            <div class='details'>
                <span>
                ".$row['nome']."
                </span>
                <p>Messaggio ricerca</p>
            </div>
        </div>
        <div class='new-messages'>
            <p>1</p>
        </div>
    </a>";
    }

    echo $final;

    $link -> close();
?>