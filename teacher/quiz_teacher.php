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
	<link rel="shortcut icon" type="png" href="../images/icon_/favicon.png">
	<title>Quiz page</title>
	<link rel="stylesheet" type="text/css" href="quiz_teacher.css">
	<script type="text/javascript" src="../script.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	

</head>

<body>

	<!-- NAVIGATION -->

	<!----------------------------------------------------HEADER (navbar, title, side-menu)--------------------------------------------->
	<header id="header">

		<!-- Navigation Bar -->
		<nav>
			<a href="../index.html">
				<div class="logo"><img src="../images/logo.png" alt="logo"></div>
			</a>
			<ul>
				<li><a href="courses_teacher.php">Info</a></li>
				<li><a href="files_teacher.php">Files</a></li>
				<li><a class="active" href="#quiz">Quiz</a></li>
				<li><a href="chat_teacher.php">Chat</a></li>
			</ul>
			<a class="logout" href="../login/logout.php">Logout</a>
			<img src="../images/icon_/menu.png" class="menu" onclick="sideMenu(0)" alt="menu">
			<!--menu a scomparsa-->
		</nav>

		<!-- side-menu (appare quando si rimpicciolisce la finestra) -->
		<div class="side-menu" id="side-menu">
			<div class="close" onclick="sideMenu(1)"><img src="../images/icon_/close.png" alt=""></div>
			<ul>
				<li><a href="courses_teacher.php">Info</a></li>
				<li><a href="files_teacher.php">Files</a></li>
				<li><a class="active" href="#quiz">Quiz</a></li>
				<li><a href="chat_teacher.php">Chat</a></li>
			</ul>
		</div>

	</header>


	<!-- Sezione quiz -->

	<div class="panel" id="panel">

		<div class="left-side" id="left">
			<ul>
			<?php

				$querySelezione = "SELECT num, titolo as nome_capitolo, numCap as num_capitolo from quiz where  idCorso='$id_corso'";
				$result = $link -> query($querySelezione);

				//<!--<button class="decline-button" value="" id="deleteButton">Remove Quiz</button>-->

				while($row = $result -> fetch_assoc())
					echo "<li onclick='quiz_teach(".$row['num'].")'>"."Chapter".$row['num_capitolo']." - ".$row['nome_capitolo']."</li>";
					
				echo "<li style='display: flex;	justify-content: center; align-items: center;'><a href='https://www.google.it/intl/it/forms/about/' target='_blank'>
							<button onclick='toggleModal()' class='accept-button' id='addButton'>Add Quiz</button></a>
							<button onclick='' class='decline-button' id='declineButton' style='display:inline-block;'>Remove Quiz</button>
						</li>";
			?>
			</ul>
		</div>

		<!-- The Modal -->
		<div class="modal-background" onclick="toggleModal()">
		</div>
		<!-- Modal content -->
		<div class="modal">

			<!-- FORM CREATE NEW QUIZ -->

			<form action="insert_newQuiz.php" method="post" id="frmInsertNewQuiz">
				<!--<form id="frmInsertNewQuiz">-->
				<div class="form-inner">
					<h3>Create a new quiz</h3>
					<br>
					<input type="text" name="nomeQuiz" id="nomeQuiz" placeholder="Quiz name" required>
					<input type="number" name="numeroCap" id="numeroCap" placeholder="Chapter Number" required>
					<input type="text" name="linkQuiz" id="linkQuiz" placeholder="Quiz link" required>
					<button type="submit" id="form_quiz">Create</button>
				</div>
			</form>
		</div>
		<script type="text/javascript" src="../script/modale.js"></script>


		<div class="right-side" id="right">
			<div id="quiz-container">

				<!--<button class="decline-button" value="" id="deleteButton">Remove Quiz</button>-->

				<?php

						$querySelezione = "SELECT linkGF, num, titolo as nome_capitolo, numCap as num_capitolo from quiz where  idCorso='$id_corso'";
						$result = $link -> query($querySelezione);

						while($row = $result -> fetch_assoc())
						{
							//dummy
							echo "
								<div id='f".$row['num']."'>
									<iframe id='frame".$row['num']."' class='quiz-frame'
										src='https://www.google.it/intl/it/forms/about/'
										frameborder='0' marginheight='0' marginwidth='0'>Loading…
									</iframe>
								</div>";
						}
				?>

				

			</div>
		</div>
	</div>

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

	<script type="text/javascript" src="../script.js"></script>
	<script type="text/javascript" src="../script/insert_newQuiz.js"></script>
	

</body>



</html>