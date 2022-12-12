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

	$id_corso=$_SESSION['idCorso'];
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Comaptible" content="IE=edge">
		<title>Course page</title>
		<meta name="desciption" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="courses_teacher.css">
		<link rel="shortcut icon" type="png" href="../images/icon_/favicon.png">
		<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>

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
				<a href="teacher.php"><div class="logo"><img src="../images/logo.png" alt="logo"></div></a>
				<ul>
					<li><a class="active" href="#info">Info</a></li>
					<li><a href="files_teacher.php">Files</a></li>
					<li><a href="quiz_teacher.php">Quiz</a></li>
				</ul>
				<a class="logout" href="../login/logout.php">Logout</a>
				<img src="../images/icon_/menu.png" class="menu" onclick="sideMenu(0)" alt="menu">
				<!--menu a scomparsa-->
			</nav>

			<!-- Title -->
			<?php

				// QUERY: estrae nome doc, nome corso, num cfu corso
				$sql = "SELECT UTENTE.nome AS nome_doc, CORSO.nome AS nome_corso, CORSO.cfu AS cfu_corso 
						FROM CORSO INNER JOIN ISCRIZIONE ON CORSO.id = ISCRIZIONE.idCorso INNER JOIN UTENTE ON ISCRIZIONE.idUtente = UTENTE.id 
						WHERE ISCRIZIONE.idCorso='$id_corso' AND ISCRIZIONE.tipoUtente = 'DOC'";

				$result = $link -> query($sql);
				$row = $result -> fetch_assoc();
				
				$nome_doc = $row['nome_doc'];
				$nome_corso = $row['nome_corso'];
				$cfu_corso = $row['cfu_corso'];

				// QUERY: estrae il numero di studenti iscritti a quel corso
				$sql = "SELECT count(ISCRIZIONE.idUtente) AS conta_stu
						FROM ISCRIZIONE
						WHERE ISCRIZIONE.idCorso='$id_corso' AND iscrizione.tipoUtente = 'STU'";

				$result = $link -> query($sql);
				$row = $result -> fetch_assoc();

				$conta_stu = $row['conta_stu'];

				echo "
					<div class='title' id='info'>
						<span>$nome_corso</span>
						<div class='shortdesc' id='sub-info'>
							<h4>CFU</h4>
							<p>$cfu_corso</p><br><br>
							<h4>Professor: </h4>
							<p>$nome_doc</p><br><br>
							<h4>Number of enrolled students: </h4>
							<p>$conta_stu</p>
						</div>
						<!-- Trigger/Open The Modal -->
						<button id='btn' class='mng-isc' onclick='toggleModal()' type='button'>Manage pending requests</button>
						<button id='btn1' class='mng-txt' onclick='toggleModal1()' type='button'>Edit course info</button>
					</div>";
			?>

			<!-- The Modal: manage pending requests -->
			<div class="modal-background" onclick="toggleModal()">
			</div>
			<!-- Modal content -->

			<!--Prova-->
			<div class="modal" id="pendingCourses">
					<h3>Manage pending requests</h3>
					<br>
					<div class="form-group select-all">
						<input type="checkbox" id="select-all">
						<label for="select-all"><i>Select all</i></label>
					</div>

					<?php

						$sql = "SELECT UTENTE.nome AS nome_utente, UTENTE.id as id_studente FROM ISCRIZIONE INNER JOIN UTENTE ON ISCRIZIONE.idUtente = UTENTE.id WHERE ISCRIZIONE.stato=-1 AND ISCRIZIONE.idCorso = '$id_corso' AND UTENTE.tipo='STU'";
						$result = $link -> query($sql);
						while($row = $result->fetch_assoc())
						{
							$nome_utente = $row['nome_utente'];
							$id_studente=$row['id_studente'];
							echo "
								<div class='form-group'>
									<input class = 'cb' type='checkbox' id='stud1' name='stud1' value='".$id_studente."' prova=''>
									<label for='stud1'>".$nome_utente."</label>
								</div>
								";
						}
					?>
				<button class="accept-button" id="acceptBTN">Accept</button>
				<button class="decline-button" id="declineBTN">Decline</button>
			</div>
			<script type="text/javascript" src="../script/modale.js"></script>

			<!-- The Modal: edit course info -->
			<div class="modal-background1" onclick="toggleModal1()">
			</div>
			<!-- Modal content -->
			<div class="modal1">

					<!-- FORM MANAGE COURSE INFO-->

					<!--<form action="#" method="post" enctype="text/plain" >-->
					<form id="frmUpdateCourse">
						<div class="form-inner">
							<h3>Edit course info</h3>
							<br>
							<input type="text" name="nomeCorso" placeholder="Course name">
							<input type="number" name="cfuCorso" placeholder="CFU">

							<?php

								$sql = "SELECT nome FROM UTENTE WHERE id = '$id_docente' AND tipo='DOC'";

								$result = $link -> query($sql);
								$row = $result->fetch_assoc();

								echo "<input type='text' value='".$row['nome']."' name='nomeDoc' placeholder='Professor' disabled>";
							?>
							
							<textarea  name="obiettivoCorso" placeholder="Course Goals"></textarea>
							<textarea name="descrizioneCorso" placeholder="Brief description of the course"></textarea>
							<input type="text" name="verificaCorso" placeholder="Learning Verification">
							<input name="immagineCorso" type="file" id="upload_img_btn">
							<label for="upload_img_btn" id="upload_img_lbl"><i class = "fa-solid fa-upload"></i> Choose course image</label>
							<button type="submit" id="form_course">Save</button>
						</div>
					</form>

			</div>
			<script type="text/javascript" src="../script/modale.js"></script>



			<!-- Immagine di profilo -->
			<main class="ccard_usrimg">
				<?php

					$sql = "SELECT UTENTE.propic 
							FROM UTENTE INNER JOIN ISCRIZIONE ON UTENTE.id = ISCRIZIONE.idUtente INNER JOIN CORSO ON ISCRIZIONE.idCorso = CORSO.id
							WHERE ISCRIZIONE.tipoUtente = 'DOC' AND ISCRIZIONE.idCorso = '$id_corso'";

					$result = $link -> query($sql);
					$row = $result -> fetch_assoc();
					$propic = $row['propic'];

					echo"
					<div class='profile-pic-div'>
						<img src='data:image/gif;base64,".base64_encode($propic)."'id='photo'>
						<input type='file' id='file'>
						<label for='file' id='uploadBtn'>Change Photo</label>
				</div>";
				?>
				<script src="../script/user_img.js"></script>
			</main>

			<!-- side-menu (appare quando si rimpicciolisce la finestra) -->
			<div class="side-menu" id="side-menu">
				<div class="close" onclick="sideMenu(1)"><img src="../images/icon_/close.png" alt=""></div>
				<ul>
					<li><a class="active" href="#info">Info</a></li>
					<li><a href="files_teacher.php">Files</a></li>
					<li><a href="quiz_teacher.php">Quiz</a></li>
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
				<div class="shortdesc2">
				<?php

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
			</div>


			<!-- Description -->
			<div class="head-container">
				<div class="inbt">
					<span>Brief description of the course</span>
					<div class="shortdesc2">
					<?php

						// QUERY: recupera descrizione del corso
						$sql = "SELECT CORSO.descrizione FROM CORSO WHERE CORSO.id='$id_corso'";
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$descrizione_corso = $row['descrizione'];

						echo"
							<div class='shortdesc2'>
							<p>".$descrizione_corso."</p><br>
							</div>";
					?>
					</div>
				</div>
				<!--immagine-->
				<?php

					$sql = "SELECT CORSO.copertina FROM CORSO WHERE CORSO.id='$id_corso' LIMIT 1";
					$result = $link -> query($sql);
					$row = $result -> fetch_assoc();
					$copertina = $row['copertina'];

					echo"
					<div class='container'>
						<img src='data:image/gif;base64," .base64_encode($copertina). "' alt='svg'>
					</div>";

				?>
				</div>
			</div>


			<!-- Verification -->
			<div class="inbt_end">
				<span>Learning Verification</span>
				<div class="shortdesc2">
				<?php

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
		
		<script type="text/javascript" src="../script/course_request.js"></script>
		<script type="text/javascript" src="../script/update_Course.js"></script>

	</body>

</html>