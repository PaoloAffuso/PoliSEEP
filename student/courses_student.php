<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Comaptible" content="IE=edge">
		<title>Course page</title>
		<meta name="desciption" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="courses_student.css">
		<link rel="shortcut icon" type="png" href="../images/icon_/favicon.png">
		<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

		<script type="text/javascript" src="../script.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>

		<script> // script per la gestione dell'ombra nera della navbar
			$(window).on('scroll', function () {
				if ($(window).scrollTop()) {
					$('nav').addClass('black');
				} else {
					$('nav').removeClass('black');
				}
			})
		</script>
	</head>

	<body>

		<!----------------------------------------------------HEADER (navbar, title, side-menu)--------------------------------------------->
		<header id="header">

			<!-- Navigation Bar -->
			<nav>
				<a href="../index.html"><div class="logo"><img src="../images/logo.png" alt="logo"></div></a>
				<ul>
					<li><a class="active" href="#info">Info</a></li>
					<li><a href="files_student.html">Files</a></li>
					<li><a href="quiz_student.html">Quiz</a></li>
				</ul>
				<a class="logout" href="">Logout</a>
				<img src="../images/icon_/menu.png" class="menu" onclick="sideMenu(0)" alt="menu">
				<!--menu a scomparsa-->
			</nav>

			<!-- Title -->
			<?php
				include '../config.php';
				// Check connection
				if (mysqli_connect_errno())
					echo "Connessione al database non riuscita: " . mysqli_connect_error();

				$id_corso = $_GET['id_corso'];

				$id_corso = 1; //dummy

				// QUERY: estrae nome doc, nome corso, num cfu corso
				
				$sql = "SELECT UTENTE.nome AS nome_doc, CORSO.nome AS nome_corso, CORSO.cfu AS cfu_corso 
						FROM CORSO INNER JOIN ISCRIZIONE ON CORSO.id = ISCRIZIONE.idCorso INNER JOIN UTENTE ON ISCRIZIONE.idUtente = UTENTE.id 
						WHERE ISCRIZIONE.idCorso='$id_corso' AND iscrizione.tipoUtente = 'DOC'";

				$result = $link -> query($sql);
				$row = $result -> fetch_assoc();
				$nome_doc = $row['nome_doc'];
				$nome_corso = $row['nome_corso'];
				$cfu_corso = $row['cfu_corso'];
				
				$_SESSION['id_corso'] = $id_corso;

				echo"
					<div class='title' id='info'>
						<span>".$nome_corso."</span>
						<div class='shortdesc' id='sub-info'>
							<h4>CFU: </h4>
							<p>".$cfu_corso."</p><br><br>
							<h4>Professor: </h4>
							<p>".$nome_doc."</p>
						</div>
					</div>";
			?>
			
			<!-- Immagine di profilo -->
			<main class="ccard_usrimg">
				
				<?php

					$id_corso = $_SESSION['id_corso'];

					$sql = "SELECT UTENTE.propic AS propic_utente FROM UTENTE INNER JOIN ISCRIZIONE ON UTENTE.id = ISCRIZIONE.idUtente INNER JOIN CORSO ON ISCRIZIONE.idCorso = CORSO.id WHERE CORSO.id='$id_corso' AND UTENTE.tipo = 'DOC'";
					$result = $link -> query($sql);
					$row = $result -> fetch_assoc();
					$propic = $row['propic_utente'];

					echo"
						<div class='profile-pic-div'>
							<img src='data:image/gif;base64," .base64_encode($propic). "'>
						</div>";
				?>
			</main>

			<!-- side-menu (appare quando si rimpicciolisce la finestra) -->
			<div class="side-menu" id="side-menu">
				<div class="close" onclick="sideMenu(1)"><img src="../images/icon_/close.png" alt=""></div>
				<ul>
					<li><a class="active" href="#info">Info</a></li>
					<li><a href="files_student.html">Files</a></li>
					<li><a href="quiz_student.html">Quiz</a></li>
				</ul>
			</div>

			<!-- Freccia per tornare su -->
			<a href="#" class="go-top"><i class="fas fa-arrow-up"></i></a>

		</header>

		<!-- Container principale contenente le tre sezioni di Info -->
		<main class="ccard_text">

			<!-- Goals -->
			<div class="inbt">
				<span>Course Goals</span>
				<?php
					$id_corso = $_SESSION['id_corso'];
					$sql = "SELECT CORSO.obiettivi FROM CORSO WHERE CORSO.id='$id_corso'";
					$result = $link -> query($sql);
					$row = $result -> fetch_assoc();
					$obiettivi_corso = $row['obiettivi'];

					echo"
						<div class='shortdesc2'>
							<p>".$obiettivi_corso."</p><br>
						</div>";
				?>
			</div>

			<!-- Description -->
			<div class="head-container">
				<div class="inbt">
					<span>Brief description of the course</span>
					<?php
						
						$id_corso = $_SESSION['id_corso'];

						// QUERY: recupera descrizione del corso
						$sql = "SELECT CORSO.descrizione FROM CORSO WHERE CORSO.id='$id_corso'";
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$descrizione_corso = $row['descrizione'];

						echo"
							<div class='shortdesc2'>
							<p>".$descrizione_corso."</p><br>
							</div>";

						// QUERY: recupera lista capitoli del corso
						$sql = "SELECT max(CAPITOLO.num) AS max_num FROM CAPITOLO WHERE CAPITOLO.idCorso='$id_corso'";
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$max_num = $row['max_num'];

						for($i=0; $i<$max_num; $i++)
						{
							echo "Chapter ".$i."<br>";
						}
					?>
				</div>
				
				<!--immagine-->
				<?php
				
					$id_corso = $_SESSION['id_corso'];

					$sql = "SELECT CORSO.copertina FROM CORSO WHERE CORSO.id='$id_corso'";
					$result = $link -> query($sql);
					$row = $result -> fetch_assoc();
					$copertina = $row['copertina'];

					echo"
					<div class='container'>
						<img src='data:image/gif;base64," .base64_encode($copertina). "' alt='svg'>
					</div>";

				?>
			</div>


			<!-- Verification -->
			<div class="inbt_end">
				<span>Learning Verification</span>
				<?php

					$id_corso = $_SESSION['id_corso'];

					$sql = "SELECT CORSO.verifica FROM CORSO WHERE CORSO.id='$id_corso'";
					$result = $link -> query($sql);
					$row = $result -> fetch_assoc();
					$verifica = $row['verifica'];

					echo "
					<div class='shortdesc2'>
						<p>".$verifica."</p>
					</div>
					";
				?>
				
			</div>

		</main>

		<!-----------------------------------------------------------FOOTER----------------------------------------------------------->
		<footer>
			<div class="footer-container">
				<div class="foot">
					<p>Copyright © 2022<br>Created By PoliSEEP Team<br>All Rights Reserved.</p>
					<p><img src="../images/icon_/location.png"> Politecnico di Bari, via Edoardo Orabona, 4, 70126 Bari BA
					</p>
					<p><img src="../images/icon_/phone.png"> 345 1122333<br><img src="../images/icon_/mail.png">&nbsp;
						poliseepteam@gmail.com</p>
				</div>
			</div>
		</footer>

	</body>

</html>