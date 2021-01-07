<!DOCTYPE html>
<html>
	<head>
		<title>Registratiepagina MuziekProject</title>
	</head>
	<body>
		<h2>Registratiepagina MuziekProject</h2>
		<a href="index.php">Klik hier om terug te gaan.</a><br/><br/>
		<form action="register.php" method="post">
			Voer een gebruikersnaam in: <br><input type="text" name="username" required="required"/> </br>
			Voer een wachtwoord in: <br><input type="password" name="password" required="required" /> </br/>
			<input type="submit" value="Register"/>
		</form>
	</body>
</html>

<?php
	require("Backend/config.php");
	
	//Test om te kijken of er session login is
	foreach ($_SESSION as $key=>$val)
		echo "Welkom ".$val."<br/>";
	
	$conn = $db_connection; 
	//Check connection
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	} 

    if (isset($_REQUEST['username'])) 
	{
        // removes backslashes
        $username = stripslashes($_REQUEST['username']);
        //escapes special characters in a string
        $username = mysqli_real_escape_string($conn, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($conn, $password);

		$sql="SELECT user_name FROM user WHERE user_name='$username'";
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0)
		{
			echo"<br><b> Deze naam bestaat al, probeer een andere naam in te voeren.</b></br>";
		}
		else 
		{
			$query    = "INSERT into `user` (user_name, user_password)
			VALUES ('$username', '$password')";
			$result   = mysqli_query($conn, $query);
			if ($result) 
			{
				echo "<div class='form'>
					  <h3>Je bent succesvol geregistreed.</h3><br/>
					  <p class='link'>Klik hier om de <a href='login.php'>Login</a></p>
					  </div>";
			} 
			else 
			{
				echo "<div class='form'>
					  <h3>Benodige velden ontbreken.</h3><br/>
					  <p class='link'>Klik hier om opnieuw te <a href='registration.php'>registeren</a> again.</p>
					  </div>";
			}
		}
	}		
	else 
	{
		
	}

	$conn->close();
?>