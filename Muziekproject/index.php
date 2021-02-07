<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="frontend/css/style.css">
		<title>Indexpagina MuziekProject</title>
		<div class="header">
			<h1>Indexpagina MuziekProject</h1>
		</div> 
	</head>
	
	<body>
		<table>
			<tr class="textbox_bottom">		
				<th><a class="button" href="index.php">Home</a></th>
		<?php
			require("backend/config.php");
			include("backend/auth_session.php");
		
			$connection = $db_connection; 
			//Controleer verbinding
			if ($connection->connect_error) 
			{
				die("Connection failed: " . $connection->connect_error);
			} 

			//Test om te kijken of er session login is
			if ($_SESSION != null)
			{	
				echo "<th><div class=\"user_text\">Welkom, ". $_SESSION['username'] ."</div></th>";			
			}				
			
		?>
				<!--Wanneer ingelogd-->
				<?php if ($_SESSION != null) : ?>		
					<th><a class="button" href="frontend/overzicht.php">Overzicht Pagina</a></th>
					<th><a class="button" href="backend/logout.php"> Klik hier om uit te loggen. </a></th>
				<?php endif; ?>
				
				<!--Wanneer niet ingelogd-->
				<?php if ($_SESSION == null) : ?>
					<th><a class="button" href="frontend/login.php"> Klik hier voor inloggen. </a></th>
				<?php endif; ?>
			</tr>
		</table>	
		
		<table style='width: auto;'><tr><th><a class='text-title'>Welkom op de startpagina.</a></th></tr></table>
		
	</body>
	
	<footer>
	</footer>
</html>