<?php
	include '../config.php';
	session_start();
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();
	
	if(!isset($_SESSION["loggedin"])){
		header("location: ../index.html");
	}
	$id_docente = $_SESSION['id_utente']; 
?>


<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Comaptible" content="IE=edge">
		<title>Teacher page</title>
		<meta name="desciption" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="teacher.css">
		<link rel="shortcut icon" type="png" href="../images/icon_/favicon.png">
		<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>


		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
        </script>

		<script type="text/javascript" src="../script.js"></script>

		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>

		<script> // script per la gestione dell'ombra nera della navbar
			$(window).on('scroll', function(){
				if($(window).scrollTop()){
				$('nav').addClass('black');
				}else {
			$('nav').removeClass('black');
			}
			})
		</script>
	</head>

	<body>

		<!----------------------------------------------------HEADER (navbar, title, side-menu)--------------------------------------------->
		<header id="header"></header>

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
				<?php

					//$nome_utente = $_SESSION['nome_utente']; // dal login si ricava la variabile di sessione

					// dummy
					
					$nome_utente = "PROVA";

					// dummy
					$sql = "SELECT nome as nome_utente FROM UTENTE WHERE id = '$id_docente' AND tipo = 'DOC'";
										
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
				<?php
					//$id_utente = $_SESSION['id_utente'];

					$id_utente = 2; // dummy

					$sql = "SELECT propic FROM UTENTE WHERE id='$id_utente' AND UTENTE.tipo = 'DOC'";

					$sql = "SELECT propic FROM UTENTE WHERE id='$id_docente' AND UTENTE.tipo = 'DOC'";
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
					<li><a class="active" href="#dashboard">Dashboard</a></li>
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
				<p>Here there are some statistics on your courses</p>
			</div>
		</div>
		<main class="ccard_dash">

			<!-- Overview -->
			<section class="overview">
				<div class="overview_container">
					<div class="overview_sub">
						<?php

						//$id_utente = $_SESSION['id_utente'];
						
						$id_utente = 2; // dummy
						
						// QUERY: conta i corsi che appartengono al docente
						$sql = "SELECT count(ISCRIZIONE.idCorso) AS conta_corsi FROM ISCRIZIONE WHERE ISCRIZIONE.idUtente = '$id_utente' AND ISCRIZIONE.stato = 0 AND ISCRIZIONE.tipoUtente = 'DOC'";
						
						// QUERY: conta i corsi che appartengono al docente
						$sql = "SELECT count(ISCRIZIONE.idCorso) AS conta_corsi FROM ISCRIZIONE WHERE ISCRIZIONE.idUtente = '$id_docente' AND ISCRIZIONE.stato = 0 AND ISCRIZIONE.tipoUtente = 'DOC'";
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$conta_corsi = $row['conta_corsi'];

						echo "
							<p id='topper_overview'>Your courses</p>
							<p id='number'>".$conta_corsi."</p>";
						?>
						
					</div>
					<div class="overview_sub">
						<?php

							//$id_utente = $_SESSION['id_utente'];
							//$id_corso = $_GET['id_corso'];

							$id_utente = 2; // dummy
							$id_corso = 1; // dummy

							// QUERY: conta i corsi che appartengono al docente
							$sql = "SELECT count(ISCRIZIONE.idUtente) AS conta_iscritti FROM ISCRIZIONE WHERE ISCRIZIONE.stato = 1 AND ISCRIZIONE.tipoUtente = 'STU' AND ISCRIZIONE.idCorso='$id_corso'";
							// QUERY: conta il numero di iscritti al corso del docente
							$sql = "SELECT count(ISCRIZIONE.idUtente) AS conta_iscritti FROM ISCRIZIONE WHERE ISCRIZIONE.stato = 1 AND ISCRIZIONE.tipoUtente = 'STU'";
							$result = $link -> query($sql);
							$row = $result -> fetch_assoc();
							$conta_iscritti = $row['conta_iscritti'];

							echo "
							<p id='topper_overview'>Enrolled students</p>
							<p id='number'>".$conta_iscritti."</p>";
						?>
						
					</div>
					<div class="overview_sub">
					<?php

						//$id_utente = $_SESSION['id_utente'];
						//$id_corso = $_GET['id_corso'];

						$id_utente = 2; // dummy
						$id_corso = 1; // dummy

						// QUERY: conta i corsi che appartengono al docente
						// QUERY: conta i quiz creati dal docente
						$sql = "SELECT count(TAKE_QUIZ.idUtente) AS conta_quiz_creati FROM TAKE_QUIZ WHERE TAKE_QUIZ.stato = 0 AND TAKE_QUIZ.tipoUtente = 'DOC'";
						$result = $link -> query($sql);
						$row = $result -> fetch_assoc();
						$conta_quiz_creati = $row['conta_quiz_creati'];

						echo "
						<p id='topper_overview'>Loaded quiz</p>
						<p id='number'>".$conta_quiz_creati."</p>";
					?>
					</div>
				</div>
			</section>
			<div class="chart">
				<canvas id="myChart" style="width:100%;max-width:700px"></canvas>
			</div>
		</main>

		<?php

		?>


		<script>

			function(quiz_comp, quiz_ncomp, corsi)
			{
				var xValues = ["Databases", "OS", "IoT","ML"]; //dummy
				var quiz_comp = [65,59,80,81,56,55,40]; // dummy

				new Chart("myChart", 
				{
					type: "bar",
					data: 
					{
						labels: xValues,
						datasets: [{
										label: "NUMBER OF STUDENT THAT HAVE COMPLETED THE QUIZ",
										backgroundColor: "#4BB377",
										data: [65,59,80,81,56,55,40]
									}, 
									{
										label: "NUMBER OF STUDENT THAT HAVEN'T COMPLETED THE QUIZ",
										backgroundColor: "#004A86",
										data: [28, 48, 40, 19, 86, 27, 90]
									}]
					},
					options: 
					{
						legend: {display: false},
						title: 
						{
							display: true,
							text: "TOTAL COMPLETED QUIZ"
						}
					}
				});
			}
			
		</script>


		<!-----------------------------------------------------------SEZIONE CORSI----------------------------------------------------------->

		<!-- Sezione dei corsi disponibili -->
		<div class="inbt", id="sezione_corsi_disponibili">
			<span>Your Courses</span>
			<div class="shortdesc2">
				<p>Here you can manage your courses</p>
			</div>
			<div class="nuovo_corso">
				<!-- Trigger/Open The Modal -->
				<button onclick="toggleModal()" type="button">Insert a new course</button>
			</div>
			<!-- Insieme dei corsi disponibili -->
			<div class="ccard">
				<?php
					// dummy 
					$id_utente = 2;
					// dummy 

					// QUERY: estrae i codici dei corsi a cui l'utente NON è iscritto
					$sql = "SELECT CORSO.nome AS nome_corso, CORSO.copertina AS copertina_corso, CORSO.id AS id_corso FROM CORSO WHERE CORSO.id NOT IN (SELECT idCorso FROM ISCRIZIONE WHERE idUtente = '$id_utente' AND tipoUtente = 'STU')";

					// QUERY: estrae i codici dei corsi a cui l'utente è iscritto
					$sql = "SELECT CORSO.nome AS nome_corso, CORSO.copertina AS copertina_corso, CORSO.id AS id_corso FROM CORSO WHERE CORSO.id IN (SELECT idCorso FROM ISCRIZIONE WHERE idUtente = '$id_docente' AND tipoUtente = 'DOC' AND stato = 0)";
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
				
				<script> 
					function course_redirect(id_corso)
					{
						window.location.replace("courses_teacher.php?id_corso="+id_corso); 
					}
				</script>
			</div>


			<!-- The Modal -->
			<div class="modal-background" onclick="toggleModal()">
			</div>
			<!-- Modal content -->
			<div class="modal">


					<!-- FORM CREATE NEW COURSE -->

					<form action="#" method="post" enctype="text/plain" >
						<div class="form-inner">
							<h3>Create a new course</h3>
							<br>
							<input type="text" placeholder="Course name">
							<input type="number" placeholder="CFU">
							<input type="text" placeholder="Professor">
							<textarea placeholder="Course Goals"></textarea>
							<input type="number" placeholder="Number of chapters">
							<textarea placeholder="Brief description of the course"></textarea>
							<input type="text" placeholder="Learning Verification">
							<input type="file" id="upload_img_btn">
							<label for="upload_img_btn" id="upload_img_lbl"><i class = "fa-solid fa-upload"></i> Choose course image</label>
							<button type="submit" id="form_course">Create</button>
						</div>
					</form>

			</div>
			<script type="text/javascript" src="../script/modale.js"></script>
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

	</body>
</html>