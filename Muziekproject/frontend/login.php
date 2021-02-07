<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css">
		<title>Loginpagina MuziekProject</title>
		<div class="header">
			<h1>Loginpagina MuziekProject</h1>
		</div> 
	</head>
	
	<body>
		<table>
			<tr class="textbox_bottom">
				<th><a class="button" href="../index.php">Home</a></th>
			</tr>
		</table>	
<?php
	require("../backend/config.php");
	include("../backend/auth_session.php");

	$connection = $db_connection; 
			
	//Controleer verbinding
	if ($connection->connect_error)
	{
		die("Connection failed: " . $connection->connect_error);
	}

    //session_start();

	//Test om te kijken of er session login is
	if ($_SESSION != null)
	{	
		echo "<th><div class=\"user_text\">Welkom, ". $_SESSION['username'] ."</div></th>";			
		header("Location: overzicht.php");
	}	
	
    // When form submitted, check and create user session.
    if (isset($_POST['username'])) 
	{
        $username = stripslashes($_REQUEST['username']);    // removes backslashes
        $username = mysqli_real_escape_string($connection, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($connection, $password);
        // Check user exist in the database
        $query    = "SELECT * FROM `user` WHERE user_name='$username'
                     AND user_password='$password'";
        $result = mysqli_query($connection, $query) or die(mysql_error());
        $rows = mysqli_num_rows($result);
        
		if ($rows == 1) 
		{
            $_SESSION['username'] = $username;
			$_SESSION['message'] = "";
            // Redirect to user index page
            header("Location: overzicht.php");
        } 
		else 
		{
            echo "<div class='form'>
                  <h3>Verkeerde gebruikersnaam/wachtwoord.</h3><br/>
                  <p class='link'>Klik hier voor nogmaals te  <a href='login.php'>inloggen,</a></p>
                  </div>";
        }
    }
	else 
	{
?>
	<table style="width: auto;">
		<tr>
			<form method="post" name="loginUser">
				<th><a class="text-title">Login Gebruikers en Admins</a></th>
				<th><input class="input" type="text" class="login-input" name="username" placeholder="Gebruikersnaam" autofocus="true"/></th>
				<th><input class="input" type="password" class="login-input" name="password" placeholder="Wachtwoord"/></th>
				<th><input class="button" type="submit" value="Login" name="submit" class="login-button"/></th>
			</form>
		</tr>
	</table>	
<?php
    }
?>

	</body>
	
	<footer>
	</footer>
</html>