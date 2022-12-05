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
				<a href="../index.html"><div class="logo"><img src="../images/logo.png" alt="logo"></div></a>
				<ul>
					<li><a class="active" href="#info">Info</a></li>
					<li><a href="files_teacher.php">Files</a></li>
					<li><a href="quiz_teacher.html">Quiz</a></li>
				</ul>
				<a class="logout" href="../login/logout.php">Logout</a>
				<img src="../images/icon_/menu.png" class="menu" onclick="sideMenu(0)" alt="menu">
				<!--menu a scomparsa-->
			</nav>

			<!-- Title -->
			<?php
				//$id_corso = $_GET['id_corso'];

				$id_corso = $_SESSION['idCorso'];

				// QUERY: estrae nome doc, nome corso, num cfu corso
				
				$sql = "SELECT UTENTE.nome AS nome_doc, CORSO.nome AS nome_corso, CORSO.cfu AS cfu_corso 
						FROM CORSO INNER JOIN ISCRIZIONE ON CORSO.id = ISCRIZIONE.idCorso INNER JOIN UTENTE ON ISCRIZIONE.idUtente = UTENTE.id 
						WHERE ISCRIZIONE.idCorso='$id_corso' AND ISCRIZIONE.tipoUtente = 'DOC'";

				$result = $link -> query($sql);
				$row = $result -> fetch_assoc();
				
				$nome_doc = $row['nome_doc'];
				$nome_corso = $row['nome_corso'];
				$cfu_corso = $row['cfu_corso'];
				
				$_SESSION['id_corso'] = $id_corso;

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

			<!--<div class="modal">
				<form class="liststud">
					<h3>Manage pending requests</h3>
					<br>
					<div class="form-group select-all">
						<input type="checkbox" id="select-all">
						<label for="select-all"><i>Select all</i></label>
					</div>
					<div class="form-group">
						<input class = "cb" type="checkbox" id="stud1" name="stud1" value="Paolo Affuso">
						<label for="stud1"> Paolo Affuso</label>
					</div>
					<div class="form-group">
						<input class = "cb" type="checkbox" id="stud2" name="stud2" value="Natale Rossiello">
						<label for="stud2"> Natale Rossiello</label>
					</div>
					<div class="form-group">
						<input class = "cb" type="checkbox" id="stud3" name="stud3" value="Francesca Albano">
						<label for="stud3"> Francesca Albano</label>
					</div>
					<div class="form-group">
						<input class = "cb" type="checkbox" id="stud4" name="stud4" value="Alessandro Mezzina">
						<label for="stud4"> Alessandro Mezzina</label>
					</div>
				</form>
				<button class="accept-button">Accept</button>
				<button class="decline-button">Decline</button>
			</div>-->
			<script type="text/javascript" src="../script/modale.js"></script>

			<!-- The Modal: edit course info -->
			<div class="modal-background1" onclick="toggleModal1()">
			</div>
			<!-- Modal content -->
			<div class="modal1">

					<!-- FORM MANAGE COURSE INFO-->

					<form action="#" method="post" enctype="text/plain" >
						<div class="form-inner">
							<h3>Edit course info</h3>
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
							<button type="submit" id="form_course">Save</button>
						</div>
					</form>

			</div>
			<script type="text/javascript" src="../script/modale.js"></script>



			<!-- Immagine di profilo -->
			<main class="ccard_usrimg">
				<div class="profile-pic-div">
					<img src="../images/student_/usrimg.png" id="photo">
					<input type="file" id="file">
					<label for="file" id="uploadBtn">Change Photo</label>
				</div>
				<script src="../script/user_img.js"></script>
			</main>

			<!-- side-menu (appare quando si rimpicciolisce la finestra) -->
			<div class="side-menu" id="side-menu">
				<div class="close" onclick="sideMenu(1)"><img src="../images/icon_/close.png" alt=""></div>
				<ul>
					<li><a class="active" href="#info">Info</a></li>
					<li><a href="files_teacher.php">Files</a></li>
					<li><a href="quiz_teacher.html">Quiz</a></li>
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
					<p>The purpose of the course is to describe the architectures, enabling technologies and design principles
						of telecommunications networks in the Internet of Things. The topics covered in the theoretical lessons
						will be in the laboratory to obtain tangible demonstrations in the field of the effectiveness of the architectural solutions presented in the course. Knowledge and skills attested:
					</p><br>
					<h4>
						- Knowledge of the main IoT architectures<br>
						- Knowledge of IPv6 and 6LoWPAN protocols<br>
						- Ability to analyze the elements of an IoT network (WPAN and WAN)<br>
						- Ability to use correct scientific technical language.<br>
						- Ability to design complex IoTs
					</h4>
				</div>
			</div>


			<!-- Description -->
			<div class="head-container">
				<div class="inbt">
					<span>Brief description of the course</span>
					<div class="shortdesc2">
						<p> Is an idea from computer science:
							connecting ordinary things
							like lights and doors to a computer network to make them "intelligent".
							An embedded system or a computer connects each thing together in a network
							and to the internet. Some technologies used for the internet of things are
							RFID and mesh nets. The connections allow each thing to collect and exchange
							data, and we can control them remotely or by setting rules or chains of actions.
							IoT improves the ease of life of humans and their daily activities. Experts
							estimate that the IoT will consist of almost 50 billion objects by 2020.
							The course is divided into the following chapters:
						</p><br>
						<h4>
							- Chapter 1<br>
							- Chapter 2<br>
							- Chapter 3<br>
							- Chapter 4<br>
							- Chapter 5
						</h4>
					</div>
				</div>
				<!--immagine-->
				<div class="container">
					<img src="../images/courses_/IoT.jpg" alt="svg">
				</div>
			</div>


			<!-- Verification -->
			<div class="inbt_end">
				<span>Learning Verification</span>
				<div class="shortdesc2">
					<p>Verification of learning is established through an oral test aimed at ascertaining the level of
						knowledge and understanding reached by the student on the theoretical and methodological contents indicated in the program.
					</p><br>
					<p>
						The oral exam also allows to verify the student's communication skills with language properties and
						autonomous organization of the exhibition.
						Minimum contents: short long range protocol architectures for IoT systems. IPv6
					</p>
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
	</body>

</html>