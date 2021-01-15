<?php
    //session_start();
	//Wanneer er nog niet is ingelogd gestuurd naar de inlogpagina.
    if(!isset($_SESSION["username"])) 
	{
        header("Location: ./login.php");
        exit();
    }
?>