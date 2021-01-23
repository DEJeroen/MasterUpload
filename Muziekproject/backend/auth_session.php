<?php

	$uri = $_SERVER['REQUEST_URI'];
    session_start();
	
	if($uri != "/muziekproject/frontend/login.php" && $uri != "/muziekproject/index.php" && $uri != "/muziekproject/")
	{
		//Wanneer er nog niet is ingelogd gestuurd naar de inlogpagina.
		if(!isset($_SESSION["username"])) 
		{
			header("Location: login.php");
			exit();
		}
	}
?>