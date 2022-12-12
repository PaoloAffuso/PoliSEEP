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

		$id_corso = $_SESSION['idCorso'];
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Comaptible" content="IE=edge">
	<title>Files page</title>
	<meta name="desciption" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="files_teacher.css">
	<link rel="shortcut icon" type="png" href="../images/icon_/favicon.png">
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">


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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>

<body>

	<!----------------------------------------------------HEADER (navbar, title, side-menu)--------------------------------------------->
	<header id="header">

		<!-- Navigation Bar -->
		<nav class="mynav">
			<a href="teacher.php"><div class="logo"><img src="../images/logo.png" alt="logo"></div></a>
			<ul>
				<li><a href="courses_teacher.php">Info</a></li>
				<li><a href="#dash" class ="active" >Files</a></li>
				<li><a href="quiz_teacher.php">Quiz</a></li>
			</ul>
			<a class="logout" href="">Logout</a>
			<img src="../images/icon_/menu.png" class="menu" onclick="sideMenu(0)" alt="menu">
			<!--menu a scomparsa-->
		</nav>

		<!-- Title -->
		<div class="title" id="dash">
			<span>Files Section</span>
			<div class="shortdesc">
				<p>
				<h4>Here you can see files you uploaded for your students</h4>
				</p>
			</div>
		</div>

		<!-- Immagine download -->
		<main class="ccard_usrimg">
			<div class="profile-pic-div" style="height: 165px; width: 165px;">
				<img src="../images/files_/upload_button.png" id="photo">
			</div>
		</main>

		<!-- side-menu (appare quando si rimpicciolisce la finestra) -->
		<div class="side-menu" id="side-menu">
			<div class="close" onclick="sideMenu(1)"><img src="../images/icon_/close.png" alt=""></div>
			<ul>
				<li><a href="courses_teacher.php">Info</a></li>
				<li><a href="#dash" class ="active">Files</a></li>
				<li><a href="quiz_teacher.php">Quiz</a></li>
			</ul>
		</div>

		<!-- Freccia per tornare su -->
		<a href="#" style="text-decoration:none" class="go-top"><i class="fas fa-arrow-up"></i></a>

	</header>


	<main class="ccard_text">
		<!-- SAMPLE PAPERS -->
		<div class="inbt">
			<span>Documents</span>
			<div class="shortdesc2">
				<p>Upload or Delete Files for your students
				</p><br>
			</div>
		</div>
	</main>


 	<div class="container" style="padding-top:50px;">

		<input type="file" id="uploadFile" style="display:none"/>
 		<div class="btn btn-primary btn_delete_val">Delete Files</div> | <div class="btn btn-primary" input id='actual-btn' input type= 'file'>Upload</div>
 		<div style="padding:21px;"></div>

		 <table class="table">
			<tbody>
				<?php
					$sql = "SELECT nome, dataOraCaricamento, dimensione, documento FROM FILES WHERE idCorso='$id_corso'";
					$result = $link -> query($sql);

					echo "
						<tr>
							<td style='font-family:Montserrat, sans-serif;'><b>Select<br> <input type='checkbox' class='select_all_items'></td>
							<td style='font-family:Montserrat, sans-serif;'><b>Pdf Name </td>
							<td style='font-family:Montserrat, sans-serif;'><b>Upload Date</td>
							<td style='font-family:Montserrat, sans-serif;'><b>Weight</td>
						</tr>";

					while($row = $result->fetch_assoc())
					{
						echo "
						<tr>
							<td><input type='checkbox' name='checkbox' class='item_id' option_id='1' value='".$id_corso."-".$row['nome']."'> </td>
							<td>".$row['nome']."</td>
							<td>".$row['dataOraCaricamento']."</td>
							<td>".$row['dimensione']." kB </td>
						</tr>
						";
					}

				?>
			</tbody>
		</table>
		<script src="../script/upload_delete.js"></script>
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

</body>

</html>