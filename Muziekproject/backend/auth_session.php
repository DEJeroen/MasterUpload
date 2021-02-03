<?php

	$uri = $_SERVER['REQUEST_URI'];
    session_start();
	
	if($uri != "/Muziekproject/frontend/login.php" && $uri != "/Muziekproject/index.php" && $uri != "/Muziekproject/")
	{
		//Wanneer er nog niet is ingelogd gestuurd naar de inlogpagina.
		if(!isset($_SESSION["username"])) 
		{
			header("Location: login.php");
			exit();
		}
	}
?>