<?php
    session_start();
    // Vernietig sessie
    if(session_destroy()) 
	{
        //Terugsturen naar de thuispagina
        header("Location: login.php");
    }
?>