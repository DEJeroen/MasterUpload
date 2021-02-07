<?php

	require("config.php");
	include("auth_session.php");
	
	//Test om te kijken of er session login is
	if ($_SESSION != null)
	{	
		echo "<th><div class=\"user_text\">Welkom, ". $_SESSION['username'] ."</div></th>";			
	}	
	
	$connection = $db_connection; 
	
	//Check connection
	if ($connection->connect_error) 
	{
		die("Connection failed: " . $connection->connect_error);
	} 
	
	$target_dir = "uploaded files/" . $_SESSION['username'] . "/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$_SESSION['message'] = '';
	
/*
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) 
	{
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) 
		{
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} 
		else 
		{
			echo "File is not an image.";
			$uploadOk = 0;
		}
	}
*/ 
	
	//echo "Uploaded File: ".basename($_FILES["fileToUpload"]["name"])."";
	// Een check om te kijken of het bestand al bestaat en of er niets is geupload
	if(basename($_FILES["fileToUpload"]["name"]) == null)
	{
			//echo "<br>Empty";
	}
	else
	{
		if (file_exists($target_file)) 
		{
			$uploadOk = 0;
			$_SESSION['message'] = 'failed exists';
			//echo "<br>Not Empty";
		}
	}

/*
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) 
	{
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
*/
/*
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) 
	{
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
*/
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) 
	{
		//$_SESSION['message'] = 'failed unknown';
	} 
	//Als alles goed dan het bestand uploaden
	else 
	{
		if (isset($_REQUEST['comment']) && $_SESSION != null) 
		{
			
			$comment = ($_REQUEST['comment']);
			$user_ID = $_SESSION['user_ID'];
			$conversation_ID = $_SESSION['conversation_ID'];
			$uploaded_file = htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
						
			if (empty($comment))
			{
				$_SESSION['message'] = 'failed empty';
			}
			else 
			{
				$comment_query    = "INSERT into `comment` (comment, user_ID, conversation_ID, uploaded_file)
				VALUES ('$comment', '$user_ID', '$conversation_ID', '$uploaded_file')";
				$comment_result   = mysqli_query($connection, $comment_query);
				//print_r($comment_query);

				if ($comment_result) 
				{					
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
					{		
						//echo "Het bestand ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " is geupload.";					
						//$_SESSION['message'] = 'success';
					}
					else 
					{
						//$_SESSION['message'] = 'failed upload';
					}
					$_SESSION['message'] = 'success';					
				} 
				else 
				{
					$_SESSION['message'] = 'failed unknown';
				}
			}
				
			$connection->close();	
		}
	}
	
/////////////////Tijdelijke quality of life/////////////////////////////////
	if($_SESSION['username'] == "admin")
	{
		header("Location: ../frontend/overzicht.php?gesprek=".$_SESSION['conversation_ID']."");
		exit();
	}
/////////////////Tijdelijke quality of life/////////////////////////////////
	
	header("Location: ../frontend/overzicht.php");
	exit();
?> 