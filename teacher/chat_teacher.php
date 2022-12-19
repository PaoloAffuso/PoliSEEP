<?php
	include '../config.php';
	session_start();
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();
	
	if(!isset($_SESSION["loggedin"]))
		header("location: ../index.html");

	if(isset($_SESSION['tipoUtente']) && $_SESSION['tipoUtente']=="STU")
		header("location: ../student/student.php");

	$id_docente=$_SESSION['id_utente'];
?>


<!DOCTYPE html>
<html>

<head>
	<link rel="shortcut icon" type="png" href="../images/icon_/favicon.png">
	<title>Chat page</title>
	<link rel="stylesheet" type="text/css" href="chat_teacher.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
	<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	<script type="text/javascript" src="../script.js"></script>
</head>

<body>

	<!-- NAVIGATION -->

	<!----------------------------------------------------HEADER (navbar, title, side-menu)--------------------------------------------->
	<header id="header">

		<!-- Navigation Bar -->
		<nav>
            <a href="teacher.php"><div class="logo"><img src="../images/logo.png" alt="logo"></div></a>
            <ul>
                <li><a href="teacher.php">Dashboard</a></li>
                <li><a href="teacher.php#sezione_corsi_disponibili">Courses</a></li>
                <li><a class="active" href="#chat">Chat</a></li>
            </ul>
            <a class="logout" href="../login/logout.php">Logout</a>
            <img src="../images/icon_/menu.png" class="menu" onclick="sideMenu(0)" alt="menu"> <!--menu a scomparsa-->
        </nav>

		<!-- side-menu (appare quando si rimpicciolisce la finestra) -->
		<div class="side-menu" id="side-menu">
			<div class="close" onclick="sideMenu(1)"><img src="../images/icon_/close.png" alt=""></div>
			<ul>
				<li><a href="teacher.php">Dashboard</a></li>
                <li><a href="teacher.php">Courses</a></li>
                <li><a class="active" href="#chat">Chat</a></li>
			</ul>
		</div>

	</header>


	<!-- Sezione chat -->

	<div class="panel" id="panel">
		<div class="left-side" id="left">
			<!--QUI CI VA LA LISTA DELLE VARIE CHAT SULLA SX-->
			<section class="users">
				<div class="search">
					<span class="text"></span>
					<!--Gestione ricerca chat js all'interno di script.js-->
					<input type="text" id="searchInput" placeholder="Enter name to search..." onkeydown="search(this)">
					<button id="searchBTN"><i class="fas fa-search"></i></button>
				</div>
				<div class="users-list" id="users-list">
					
					<?php

						$sql = "SELECT distinct chat.idStudente as id_studente from chat where idDocente='$id_docente'";
						$result = $link -> query($sql);

						while($row = $result->fetch_assoc())
						{
							$id_studente = $row['id_studente'];

							$sql0 = "SELECT utente.nome as nome_studente, propic from utente where utente.tipo='STU' and utente.id='$id_studente'";
							$result0 = $link -> query($sql0);
							$row0 = $result0->fetch_assoc();

							$sql1 = "SELECT max(num) as max_num from chat where chat.idDocente='$id_docente' and chat.idStudente='$id_studente'";
							$result1 = $link -> query($sql1);
							$row1 = $result1->fetch_assoc();
							$num = $row1['max_num'];
							
							$sql1 = "SELECT messaggio from chat where chat.idDocente='$id_docente' and chat.idStudente='$id_studente' and num = '$num'";
							$result1 = $link -> query($sql1);
							$row1 = $result1->fetch_assoc();
							$messaggio = $row1['messaggio'];

							$sql1 = "SELECT count(messaggio) as conta_non_letti from chat where chat.idDocente='$id_docente' and chat.idStudente='$id_studente' and stato = 1 and tipo<>'DOC'";
							$result1 = $link -> query($sql1);
							$row1 = $result1->fetch_assoc();
							$conta_non_letti = $row1['conta_non_letti'];

							echo "
							<a href='#' onclick='set_idStudente(".$id_studente.")'>
								<div class='content'>
								<img src='data:image/gif;base64,".base64_encode($row0['propic'])."'id='photo'>
									<div class='details'>
										<span>
											".$row0['nome_studente']." </br>
										</span>
										<p>".$messaggio."</p>
									</div>
								</div>
								<div class='new-messages' id='div_contaNonLetti'>
									<p>".$conta_non_letti."</p>
								</div>
							</a>";
						}

						
					?>
				</div>
			</section>
	    </div>

		<div class="right-side" id="right">
            <!--QUI CI VA LA CHAT SULLA DX-->
            <section class="chat-area">

				<!--Intestazione IMMAGINE, NOME-COGNOME, ACTIVENOW -->	
			    <div id="firstpart">
                    <img src="../images/student_/usrimg.png" alt="">
                    <div class="details">
                        <span id='nomeUtenteAttivo'>
                            
                        </span>
                        <p>Active now</p>
                    </div>
			    </div>

               <!--Chat--> 
               <div class="chat-box">
				
					<!--Messaggi inviati-->	
					<div class="chat outgoing">
						<div class="details" id="chat-outgoing">
						</div>
					</div>

					<!--Messaggi ricevuti-->
					<div class="chat incoming">
						<img src="../images/student_/usrimg.png" alt="">
						<div class="details" id="chat-incoming">
							<p>Ciaoooooo anche a te</p>
						</div>
					</div>

               </div>

			   <!--Form per scrivere il messaggio-->
			   <form action="#" class="typing-area" id="FrmSendMsg">
					<input type="text" class="input-field" placeholder="Type a message here..." autocomplete="off" name="messaggio">
					<input type="hidden" id="idStudente" name="idStudenteHidden">
					<button class="attachments"><i class="fa-solid fa-paperclip"></i></button>	
					<!--<button type="submit" class="send"><i class="fa-solid fa-paper-plane"></i></button>	-->
					<button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
			   </form>

            </section>
		</div>
	</div>

	<script>
		function set_idStudente(id_studente)
		{
			document.getElementById('idStudente').value=id_studente;
			$('#firstpart').empty();
			$.ajax({
				url: "../send_message.php",
				type: "post",
				data : {'id_studente' : id_studente, 'get_info': 'S'},
				success: function (response) {
				$('#firstpart').append(response);
				$("#div_contaNonLetti").innerHTML("");
				$("#div_contaNonLetti").load("chat_teacher.php #div_contaNonLetti"); //Refresh solo sul div modale
				}
			});
		}
	</script>

</body>
</html>