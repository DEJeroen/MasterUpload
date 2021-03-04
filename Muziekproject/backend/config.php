<?php
	$db_Host = '127.0.0.1';
	$db_Name = 'muziek_project';
	$db_Username = 'newuser';
	$db_Password = 'password';
	$db_connection= mysqli_connect($db_Host, $db_Username, $db_Password, $db_Name);

	// Check connection
	if ($db_connection->connect_error) 
	{
		die("Connection failed: " . $db_connection->connect_error);
	}
	//echo "Connected successfully";
?>
