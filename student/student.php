<?php
	include '../config.php';
	session_start();
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();
	
	if(!isset($_SESSION["loggedin"])){
		header("location: ../index.html");
	}

		$id_studente = $_SESSION['id_utente']; 
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Comaptible" content="IE=edge">
		<title>Student page</title>
		<meta name="desciption" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="student.css">
		<link rel="shortcut icon" type="png" href="../images/icon_/favicon.png">
		<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>

		<script type="text/javascript" src="../script.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
		<script type="text/javascript" src="../script/modale.js"></script>

		<script> // script per la gestione dell'ombra nera della navbar
			$(window).on('scroll', function(){
				if($(window).scrollTop()){
				$('nav').addClass('black');
				}else {
			$('nav').removeClass('black');
			}
			})
		</script>

		<script> 
			function course_redirect(id_corso)
			{
				$.ajax({
					url: "setSession.php",
					type: "post",
					data : {'id_corso':id_corso},
					success: function (response) {
						window.location.replace("courses_student.php?id_corso="+id_corso); 
					}
				});
			}
		</script>

	</head>

	<body>

		<!----------------------------------------------------HEADER (navbar, title, side-menu)--------------------------------------------->
		<header id="header">

			<!-- Navigation Bar -->
			<nav>
				<a href="../index.html"><div class="logo"><img src="../images/logo.png" alt="logo"></div></a>
				<ul>
					<li><a class="active" href="#dashboard">Dashboard</a></li>
					<li><a href="#sezione_corsi_disponibili">Courses</a></li>
				</ul>
				<a class="logout" href="../login/logout.php">Logout</a>
				<a class="logout" href="">Logout</a>
				<img src="../images/icon_/menu.png" class="menu" onclick="sideMenu(0)" alt="menu"> <!--menu a scomparsa-->
			</nav>

			<!-- Title -->
			<div class="title" id="dash">
				<span>Welcome back Username</span>
				<?php

					$sql = "SELECT nome as nome_utente FROM UTENTE WHERE id = '$id_studente' AND tipo = 'STU'";
					
					$result = $link -> query($sql);
					$row = $result -> fetch_assoc();
					$nome_utente = $row['nome_utente'];

					echo "<span>Welcome back ".$nome_utente."</span>";
				?>
				<div class="shortdesc">
					<p>Here you find everything you need</p>
				</div>
			</div>

			<!-- Immagine di profilo -->
			<main class="ccard_usrimg">
				<div class="profile-pic-div">
					<img src="../images/student_/usrimg.png" id="photo">
					<input type="file" id="file">
					<label for="file" id="uploadBtn">Change Photo</label>
				</div>
				<?php
				
				// Check connection
				if (mysqli_connect_errno())
					echo "Connessione al database non riuscita: " . mysqli_connect_error();

					$sql = "SELECT propic FROM UTENTE WHERE id='$id_studente' AND UTENTE.tipo = 'STU'";
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
					<li><a href="#dashboard">Dashboard</a></li>
					<li><a href="#sezione_corsi_disponibili">Courses</a></li>
				</ul>
			</div>

			<!-- Freccia per tornare su -->
			<a href="#" class="go-top"><i class="fas fa-arrow-up"></i></a>

		</header>


		<!-------------------------------------------------------------------DASHBOARD----------------------------------------------------->

		<!-- Dashboard -->
		<div class="inbt", id="dashboard">
			<span>Dashboard</span>
			<div class="shortdesc2">
				<p>Here is your Learning Activity</p>
			</div>
		</div>
		<main class="ccard_dash">

			<!-- Overview -->
			<section class="overview">
				<div class="overview_container">
				<div class="overview_sub">
					<p id="topper_overview">Enrolled Courses</p>
					<p id="number">0</p>
					<p id="bottom_overview">6 available courses</p>
				</div>
				<div class="overview_sub">
					<p id="topper_overview">Completed Courses</p>
					<p id="number">0</p>
					<p id="bottom_overview">6 courses not completed yet</p>
				</div>
				<div class="overview_sub">
					<p id="topper_overview">Completed quiz</p>
					<p id="number">0</p>
					<p id="bottom_overview">30 quizzes remaining</p>
					<?php

						// QUERY: conta i corsi a cui l'utente è iscritto
						$sql = "SELECT count(ISCRIZIONE.idCorso) AS conta_corsi FROM ISCRIZIONE WHERE ISCRIZIONE.idUtente = '$id_studente' AND ISCRIZIONE.stato = 1 AND ISCRIZIONE.tipoUtente = 'STU'";
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$conta_corsi_iscrizione = $row['conta_corsi'];

						// QUERY: conta i corsi a cui l'utente NON è iscritto
						$sql = "SELECT count(ISCRIZIONE.idCorso) AS conta_corsi FROM ISCRIZIONE WHERE ISCRIZIONE.idUtente <> '$id_studente' AND ISCRIZIONE.tipoUtente = 'STU' OR ISCRIZIONE.stato = -1";
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$conta_corsi_disponibili = $row['conta_corsi'];

						echo "
							<p id='topper_overview'>Enrolled Courses</p>
							<p id='number'>".$conta_corsi_iscrizione."</p>
							<p id='bottom_overview'>".$conta_corsi_disponibili." avaiable courses</p>";
					?>
					
				</div>
				<div class="overview_sub">
					<?php

						// QUERY: conta quanti corsi sono stati completati
						$sql = "SELECT count(stato) as conta_corsi FROM ISCRIZIONE WHERE ISCRIZIONE.idUtente = '$id_studente' AND ISCRIZIONE.tipoUtente = 'STU' AND ISCRIZIONE.stato = 1"; // 1 = corso completato
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$conta_corsi_completi = $row['conta_corsi'];

						// QUERY: conta quanti corsi non sono stati completati
						$sql = "SELECT count(stato) as conta_corsi FROM ISCRIZIONE WHERE ISCRIZIONE.idUtente = '$id_studente' AND ISCRIZIONE.tipoUtente = 'STU' AND ISCRIZIONE.stato <> 1"; // 1 = corso completato
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$conta_corsi_incompleti = $row['conta_corsi'];

						echo "			
							<p id='topper_overview'>Completed Courses</p>	
							<p id='number'>".$conta_corsi_completi."</p>
							<p id='bottom_overview'>".$conta_corsi_incompleti." courses not completed yet</p>";

					?>
				</div>
				<div class="overview_sub">
					<?php

						// QUERY: conta quanti quiz sono stati completati
						$sql = "SELECT count(stato) as conta_quiz FROM TAKE_QUIZ WHERE TAKE_QUIZ.idUtente = '$id_studente' AND TAKE_QUIZ.tipoUtente = 'STU' AND TAKE_QUIZ.stato = 1"; // 1 = quiz completato
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$conta_quiz_completi = $row['conta_quiz'];

						// QUERY: conta quanti quiz non sono stati completati
						$sql = "SELECT count(stato) as conta_quiz FROM TAKE_QUIZ WHERE TAKE_QUIZ.idUtente = '$id_studente' AND TAKE_QUIZ.tipoUtente = 'STU' AND TAKE_QUIZ.stato <> 1"; // 1 = quiz completato
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$conta_quiz_incompleti = $row['conta_quiz'];
						
						echo "
							<p id='topper_overview'>Completed quiz</p>
							<p id='number'>".$conta_quiz_completi."</p>
							<p id='bottom_overview'>".$conta_quiz_incompleti." quizzes remaining</p>";
					?>
					
				</div>
				</div>
			</section>

			<!-- To-Do List -->
			<div class="container">
				<div class="input-field">
				  <textarea placeholder="Enter your new task"></textarea>
				  <i class="uil uil-notes note-icon"></i>
				</div>

				<ul class="todoLists">
					<?php
						$count=0;
						$idUtente=$_SESSION['id_utente'];
						$sql="SELECT descrizione, stato, num FROM task WHERE idUtente='$idUtente'";
						$result = $link->query($sql);
						while($row = mysqli_fetch_array($result)) {
							echo "<li class='list pending' onclick='handleStatus(this, ".$row['num'].")'>";
							if(strcmp($row['stato'], "1")===0)
								echo "<input type='checkbox' checked/>";
							else echo "<input type='checkbox'/>";
							echo "<span class='task'>".$row['descrizione']."</span>";
							echo "<i class='uil uil-trash' id='".$row['descrizione']."' onclick='deleteTask(this, ".$row['num'].")'></i>";
							echo "</li>";
						}
					?>
				</ul>

				<div class="pending-tasks">
				  <span
					>You have <span class="pending-num"> no </span> tasks pending</span>
				  <button class="clear-button">Clear All</button>
				</div>
			</div>
			<script src="../script/script_Todo.js"></script> <!-- Script per la To-Do List -->



		</main>


		<!-----------------------------------------------------------SEZIONE CORSI----------------------------------------------------------->

		<!-- Sezione dei corsi disponibili -->
		<div class="inbt", id="sezione_corsi_disponibili">
			<span>Courses available</span>
			<div class="shortdesc2">
				<p>Choose your favorite course among those available</p>
			</div>

			<!-- Insieme dei corsi disponibili -->
			<div class="ccard">
				<center>
					<div class="ccardbox">
						<div class="dcard" onclick="toggleModal()" type="button">
							<div class="fpart"><img src="../images/student_/ADS.jpeg"></div>
							<a><div class="spart">Algorithms and Data Structures</div></a>
						</div>
						<div class="dcard" onclick="toggleModal()" type="button">
							<div class="fpart"><img src="../images/student_/DB.png"></div>
							<a><div class="spart">Databases</div></a>
						</div>
						<div class="dcard" onclick="toggleModal()" type="button">
							<div class="fpart"><img src="../images/student_/SWE.png"></div>
							<a><div class="spart">Software Engineering</div></a>
						</div>
					</div>
					<div class="ccardbox">
						<div class="dcard" onclick="toggleModal()" type="button">
							<div class="fpart"><img src="../images/student_/OS.jpg"></div>
							<a><div class="spart">Operating Systems</div></a>
						</div>
						<div class="dcard" onclick="toggleModal()" type="button">
							<div class="fpart"><img src="../images/student_/IoT.jpg"></div>
							<a><div class="spart">Internet of Things</div></a>
						</div>
						<div class="dcard" onclick="toggleModal()" type="button">
							<div class="fpart"><img src="../images/student_/AIML.jpeg"></div>
							<a><div class="spart">Artificial Intelligence and Machine Learning</div></a>
						</div>
					</div>
				</center>
			<?php

				// QUERY: estrae i codici dei corsi a cui l'utente NON è iscritto
				$sql = "SELECT CORSO.nome AS nome_corso, CORSO.copertina AS copertina_corso, CORSO.id AS id_corso FROM CORSO WHERE CORSO.id 
						NOT IN (SELECT idCorso FROM ISCRIZIONE WHERE idUtente = '$id_studente' AND tipoUtente = 'STU')
						OR CORSO.id IN (SELECT idCorso FROM ISCRIZIONE WHERE idUtente = '$id_studente' AND tipoUtente = 'STU' AND stato = -1)";
				$result = $link -> query($sql);

				while($row = $result->fetch_assoc())
				{
							echo "				
							<center>
								<div class='ccardbox'>
									<div class='dcard' onclick='toggleModal()' type='button'>
										<div class='fpart'><img src='data:image/gif;base64," .base64_encode($row['copertina_corso']). "'></div>
											<a><div class='spart'>".$row['nome_corso']."</div></a>
										</div>
									</div>
							</center>";
						
				}
					/*if (!$row) 
				{
					echo "No courses available.";
				}*/
			?>

			</div>


			<!-- The Modal -->
			<div class="modal-background" onclick="toggleModal()">
			</div>
			<!-- Modal content -->
			<div class="modal">
				<h3>Select one of the available teachers teaching this course:</h3>
				<form class="listprof">
					<input class = "cb" type="checkbox" id="prof1" name="prof1" value="Nicola Giaquinto" onchange="cbChange(this)">
					<label for="prof1"> Nicola Giaquinto</label>
					<input class = "cb" type="checkbox" id="prof2" name="prof2" value="Gennaro Boggia" onchange="cbChange(this)">
					<label for="prof2"> Gennaro Boggia</label>
					<input class = "cb" type="checkbox" id="prof3" name="prof3" value="Luigi Alfredo Grieco" onchange="cbChange(this)">
					<label for="prof3"> Luigi Alfredo Grieco</label>
					<input class = "cb" type="checkbox" id="prof4" name="prof4" value="Marina Mongiello" onchange="cbChange(this)">
					<label for="prof4"> Marina Mongiello</label>
				</form>
				<button class="send-button">Send request</button>
			</div>
			<script type="text/javascript" src="../script/modale.js"></script>
					<?php

						echo "<form class='listprof' id='formSendRequest' action='send_request_toCourse.php' method='POST'>";

						//$nome_corso = $_SESSION['nome_corso'];

						$nome_corso = "IoT"; // dummy

						// QUERY: estrae nome e id docente sulla base del nome corso
						$sql = "SELECT UTENTE.nome AS nome_utente, UTENTE.id AS id_docente
								FROM UTENTE INNER JOIN ISCRIZIONE ON UTENTE.id = ISCRIZIONE.idUtente AND UTENTE.tipo = ISCRIZIONE.tipoUtente INNER JOIN CORSO ON ISCRIZIONE.idCorso = CORSO.id 
								WHERE CORSO.nome = '$nome_corso' AND UTENTE.tipo = 'DOC' AND ISCRIZIONE.stato = 0";

						$result = $link -> query($sql);

						while($row = $result->fetch_assoc())
						{

							$id_docente = $row['id_docente'];

							// QUERY: estrae l'id del corso sulla base dell'id docente
							$sql2 = "SELECT CORSO.id AS id_corso
									FROM UTENTE INNER JOIN ISCRIZIONE ON UTENTE.id = ISCRIZIONE.idUtente AND UTENTE.tipo = ISCRIZIONE.tipoUtente INNER JOIN CORSO ON ISCRIZIONE.idCorso = CORSO.id 
									WHERE CORSO.nome = '$nome_corso' AND UTENTE.tipo = 'DOC' AND UTENTE.id = '$id_docente' ";
						
							$result2 = $link -> query($sql2);
							$row2 = $result2 -> fetch_assoc();
							$id_corso = $row2['id_corso'];

							$nome_utente_doc = $row['nome_utente'];

							// invio l'id_corso mediante il campo value del radio
							echo "
								<input class = 'cb' type='radio' id='radioSendRequest' name='id_corso' value='".$id_corso."' onchange='cbChange(this)'>
								<label for='".$nome_utente_doc." '> ".$nome_utente_doc." </label></br>
							";

						}
						
						echo "
							<button id='sendRequestBtn' class='send-button'>Send request</button>
							</form>
							";

						$_SESSION['id_studente'] = $id_studente;
					?>
			</div>
			

		</div>


		<!-- Sezione dei corsi a cui l'utente è già iscritto -->
		<div class="inbt", id="sezione_corsi_registrati">
			<span>Your Courses</span>
			<div class="shortdesc2">
				<p>Here are the courses you are already enrolled in</p>
				<?php
					// dummy 
					$id_utente = 2;
					// dummy 

					// QUERY: estrae nome e copertina dei corsi a cui l'utente è iscritto
					
					$sql = "SELECT CORSO.nome AS nome_corso, CORSO.copertina AS copertina_corso, CORSO.id AS id_corso FROM CORSO WHERE CORSO.id IN (SELECT idCorso FROM ISCRIZIONE WHERE idUtente = '$id_utente' AND tipoUtente = 'STU')";

					// QUERY: estrae nome e copertina dei corsi a cui l'utente è iscritto
					$sql = "SELECT CORSO.nome AS nome_corso, CORSO.copertina AS copertina_corso, CORSO.id AS id_corso FROM CORSO WHERE CORSO.id IN (SELECT idCorso FROM ISCRIZIONE WHERE idUtente = '$id_studente' AND tipoUtente = 'STU' AND stato=1)";
					$result = $link -> query($sql);
					
					while($row = $result->fetch_assoc())
					{
						echo "				
						<center>
							<div class='ccardbox'>
									<div class='dcard' onclick='course_redirect(".$row['id_corso'].")' type='button'>
										<div class='fpart'><img src='data:image/gif;base64," .base64_encode($row['copertina_corso']). "'></div>
										<a><div class='spart'>".$row['nome_corso']."</div></a>
									</div>
							</div>
						</center>";
					}
				?>

			</div>
		</div>


		<!-----------------------------------------------------------FOOTER----------------------------------------------------------->
		<footer>
			<div class="footer-container">
				<div class="foot">
					<p>Copyright © 2022<br>Created By PoliSEEP Team<br>All Rights Reserved.</p>
					<p><img src="../images/icon_/location.png"> Politecnico di Bari, via Edoardo Orabona, 4, 70126 Bari BA</p>
					<p><img src="../images/icon_/phone.png"> 345 1122333<br><img src="../images/icon_/mail.png">&nbsp;
						poliseepteam@gmail.com</p>
				</div>
			</div>
		</footer>
		
		<script type="text/javascript" src="../script/send_request_toCourse.js"></script>

	</body>
</html>