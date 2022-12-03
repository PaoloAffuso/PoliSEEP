<?php
	include '../config.php';
				
	// Check connection
	if (mysqli_connect_errno())
		echo "Connessione al database non riuscita: " . mysqli_connect_error();
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
		<header id="header">

			<!-- Navigation Bar -->
			<nav>
				<a href="../index.html"><div class="logo"><img src="../images/logo.png" alt="logo"></div></a>
				<ul>
					<li><a class="active" href="#dashboard">Dashboard</a></li>
					<li><a href="#sezione_corsi_disponibili">Courses</a></li>
				</ul>
				<a class="logout" href="">Logout</a>
				<img src="../images/icon_/menu.png" class="menu" onclick="sideMenu(0)" alt="menu"> <!--menu a scomparsa-->
			</nav>

			<!-- Title -->
			<div class="title" id="dash">
				<span>Welcome back Username</span>
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
						$sql="SELECT descrizione, stato FROM task WHERE idUtente=1";
						$result = $link->query($sql);
						while($row = mysqli_fetch_array($result)) {
							echo "<li class='list pending' onclick='handleStatus(this)'>";
							if(strcmp($row['stato'], "1")===0)
								echo "<input type='checkbox' checked/>";
							else echo "<input type='checkbox'/>";
							echo "<span class='task'>".$row['descrizione']."</span>";
							echo "<i class='uil uil-trash' id='".$row['descrizione']."' onclick='deleteTask(this)'></i>";
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
						window.location.replace("courses_student.php?id_corso="+id_corso); 
					}
				</script>
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

	</body>
</html>