<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css">
		<title>Registratiepagina MuziekProject</title>
		<div class="header">
			<h1>Loginpagina MuziekProject</h1>
		</div> 		
	</head>
	
	<body>
		<table>
			<tr class="textbox_bottom">
				<th><a class="button" href="../index.php">Home</a></th>

<?php
	require("../backend/config.php");
	include("../backend/auth_session.php");
	
	//Test om te kijken of er session login is
	if ($_SESSION != null)
	{	
		echo "<th><div class=\"user_text\">Welkom, ". $_SESSION['username'] ."</div></th>";			
	}	
		
?>

	<!--Wanneer ingelogd-->
	<?php if ($_SESSION != null) : ?>		
		<th><a class="button" href="overzicht.php">Overzicht Pagina</a></th>				
		<th><a class="button" href="../backend/logout.php"> Klik hier om uit te loggen. </a></th>
		</tr></table>
	<?php endif; ?>		

<?php
	
	$connection = $db_connection; 
	//Check connection
	if ($connection->connect_error) 
	{
		die("Connection failed: " . $connection->connect_error);
	} 

    if (isset($_REQUEST['username'])) 
	{
		echo "<table style='width: auto;'><tr>";
		
        // removes backslashes
        $username = stripslashes($_REQUEST['username']);
        //escapes special characters in a string
        $username = mysqli_real_escape_string($connection, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($connection, $password);

		$sql="SELECT user_name FROM user WHERE user_name='$username'";
		$result = $connection->query($sql);
		
		if ($result->num_rows > 0)
		{
			echo"<th class='negative'><div class='text-title'>Deze naam bestaat al, probeer een andere naam in te voeren.</div></th>";
		}	
		else 
		{
			$query    = "INSERT into `user` (user_name, user_password)
			VALUES ('$username', '$password')";
			$result   = mysqli_query($connection, $query);	
			if ($result) 
			{
				echo "<th><a class='text-title'>Gebruiker succesvol geregistreerd.</a><th>";
				mkdir("../backend/uploaded files/$username");
			} 
			else 
			{
				echo "<th class='negative'><div class='text-title'>Benodige velden ontbreken.</a><th>";
			}
		}
		echo "</tr></table>";		
	}		
	else 
	{
		
	}

	$connection->close();
?>

		<table style="width: auto;">
			<tr>
				<form action="register.php" method="post">
					<tr><th><a class="text-title">Voer een gebruikersnaam in: </th></tr> 
					<tr><th><input type="text" name="username" required="required"/></th></tr>
					<tr><th><a class="text-title">Voer een wachtwoord in: </th></tr> 
					<tr><th><input type="password" name="password" required="required"/></th></tr>
					<tr><th><input class="button" type="submit" value="Register"/></th></tr>
				</form>
			</tr>
		</table>
		
	</body>
	
	<footer>
	</footer>
</html>