<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css">
		<title>Loginpagina MuziekProject</title>
	</head>
	<body>
		<div class="header">
			<h1>Loginpagina MuziekProject</h1>
		</div> 

<?php
    require("Backend/config.php");
	$conn = $db_connection;
    //session_start();
	
	//Test om te kijken of er session login is
	foreach ($_SESSION as $key=>$val)
	{
		echo "Welkom ".$val."<br/>";
		header("Location: index.php");
	}
	
    // When form submitted, check and create user session.
    if (isset($_POST['username'])) 
	{
        $username = stripslashes($_REQUEST['username']);    // removes backslashes
        $username = mysqli_real_escape_string($conn, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($conn, $password);
        // Check user exist in the database
        $query    = "SELECT * FROM `user` WHERE user_name='$username'
                     AND user_password='$password'";
        $result = mysqli_query($conn, $query) or die(mysql_error());
        $rows = mysqli_num_rows($result);
        
		if ($rows == 1) 
		{
            $_SESSION['username'] = $username;
            // Redirect to user index page
            header("Location: index.php");
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
    <form class="form" method="post" name="loginUser">
		<a class="login-title">Login Gebruikers en Admins</a>
        <br><input type="text" class="login-input" name="username" placeholder="Username" autofocus="true"/>
        <input type="password" class="login-input" name="password" placeholder="Password"/>
        <input type="submit" value="Login" name="submit" class="login-button"/><hr>
	</form>
<?php
    }
?>
	</body>
	<footer>
	
	</footer>
</html>