<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css">
		<title>Overzichtpagina MuziekProject</title>
		<div class="header">
			<h1>Overzichtpagina MuziekProject</h1>
		</div> 
	</head>
	
	<body>
		<table>
			<tr class="textbox_bottom">		
				<th><a class="button" href="../index.php">Home</a></th>
		<?php
			require("../Backend/config.php");
			include("../Backend/auth_session.php");
		
			$connection = $db_connection; 
			
			//Controleer verbinding
			if ($connection->connect_error)
			{
				die("Connection failed: " . $connection->connect_error);
			}
						
			$SessionUser = $_SESSION['username'];
			$admin_status ="";
			$user_ID ="";
			$conversation_ID ="";
			$comment_conversation_ID ="";
			$username ="";
			$selectedConversation ="";
			$selectedCheck ="";	
			$chosenFile ="";

			//Test om te kijken of er session login is
			if ($_SESSION != null)
			{	
				echo "<th><div class=\"user_text\">Welkom, ". $_SESSION['username'] ."</div></th>";			
			}

		?>
				
				<!--Wanneer ingelogd en admin-->
				<?php if ($_SESSION != null && $admin_status == 1) : ?>		
					<th><a class="button" href="register.php"> Klik hier voor te registeren. </a></th>
				<?php endif; ?>
				
				<!--Wanneer ingelogd-->
				<?php if ($_SESSION != null) : ?>
					<th><a class="button" href="overzicht.php">Overzicht Pagina</a></th>				
					<th><a class="button" href="../backend/logout.php"> Klik hier om uit te loggen. </a></th>
				<?php endif; ?>
				
				<!--Wanneer niet ingelogd-->
				<?php if ($_SESSION['username'] == null) : ?>
					<th><a class="button" href="login.php"> Klik hier voor inloggen. </a></th>
				<?php endif; ?>
			</tr>
		</table>	
				
		<?php	
		
			//Controle na het plaatsen van een bericht of bestand om te weergeven of het successvol was of niet.
			switch ($_SESSION['message']) 
			{
				case "failed exists":
					echo "<table class='textbox_bottom_error'><tr><th>Sorry, Dit bestand bestaat al.</th></tr></table>"; 
					$_SESSION['message'] = '';
					break;
				case "failed unknown":
					echo "<table class='textbox_bottom_error'><tr><th>Sorry, Er iets fout gegaan probeer het opnieuw.</th></tr></table>"; 
					$_SESSION['message'] = '';
					break;
				case "failed empty":
					echo "<table class='textbox_bottom_error'><tr><th>Er is niets ingevuld, probeer het opnieuw.</th></tr></table>"; 
					$_SESSION['message'] = '';
					break;
				case "failed upload":
					echo "<table class='textbox_bottom_error'><tr><th>Sorry, er was een fout met het uploaden.</th></tr></table>"; 
					$_SESSION['message'] = '';
					break;		
				case "success":
					echo "<table class='textbox_bottom_positive'><tr><th>Het bericht is geplaatst.</th></tr></table>"; 
					$_SESSION['message'] = '';
					break;						
				default:
			}
				
			$query_user_ID = "SELECT user_ID FROM user WHERE user_name='$SessionUser'";
			$query_user_ID_result = $connection->query($query_user_ID);
			if ($query_user_ID_result->num_rows > 0) 
			{
				// output data of each row
				while($row1 = $query_user_ID_result->fetch_assoc()) 
				{
					$user_ID = $row1["user_ID"];
					$_SESSION['user_ID'] = $row1["user_ID"];
					//$loggedAs = $row1["user_ID"];
				}
						
				$query_admin_status = "SELECT admin FROM user WHERE user_ID='$user_ID'";
				$query_admin_status_result = $connection->query($query_admin_status);
				if ($query_admin_status_result->num_rows > 0) 
				{
					// output data of each row
					while($row1 = $query_admin_status_result->fetch_assoc()) 
					{
						$admin_status = $row1["admin"];
					}
				}		
				
////////////////////ADMIN AREA/////////////////////////ADMIN AREA///////////////////////////				
				if ($admin_status == 1)
				{
					echo "<table class='textbox'>";
				
					$query_conversation_ID = "SELECT * FROM conversation";
					$query_conversation_ID_result = $connection->query($query_conversation_ID);
					if ($query_conversation_ID_result->num_rows > 0) 
					{
						// output data of each row
						while($row1 = $query_conversation_ID_result->fetch_assoc()) 
						{
							$conversation_ID = $row1["conversation_ID"];
							$user_ID = $row1["user_ID"];	
							
							$query_user_ID = "SELECT user_name FROM user WHERE user_ID='$user_ID'";
							$query_user_ID_result = $connection->query($query_user_ID);
							if ($query_user_ID_result->num_rows > 0) 
							{	
								// output data of each row
								while($row1 = $query_user_ID_result->fetch_assoc()) 
								{
									echo "<tr><th><div class=\"user_text\"> Gebruiker - <user>" . $row1["user_name"] . "</user></div></th>
									<th><a class='button' href=\"overzicht.php?gesprek=$conversation_ID\">Selecteer</a></th></tr>";
								}
							}
						}
					}
					
					echo "<tr class='textbox_bottom'><th><a class='button' href=\"register.php\">Register nieuwe gebruiker</a></th>	
					<class='textbox_bottom'><th><a class='button' href=\"overzicht.php?nieuw_gesprek\">Begin nieuw gesprek</a></th></div></tr></table>";	

					if (isset($_GET['gesprek']))
					{
						echo "<table style='width: auto;'><tr><th><a class='text-title'>Huidig, <user>Gesprek " . $_GET['gesprek'] . "</a></user></th></tr></table>";
					}
				}
////////////////////ADMIN AREA/////////////////////////ADMIN AREA///////////////////////////				
				
////////////////////USER AREA/////////////////////////USER AREA///////////////////////////				
				else if ($admin_status == 0)
				{
					$query_conversation_ID = "SELECT conversation_ID FROM conversation WHERE user_ID='$user_ID'";
					$query_conversation_ID_result = $connection->query($query_conversation_ID);
					if ($query_conversation_ID_result->num_rows > 0) 
					{
						// output data of each row
						while($row1 = $query_conversation_ID_result->fetch_assoc()) 
						{
							$conversation_ID = $row1["conversation_ID"];
							chooseConversation($conversation_ID);
						}
					}
					else
					{
						echo "<tr><th>Nog geen gesprek gestart </th></tr>";
					}					
				}
////////////////////USER AREA/////////////////////////USER AREA///////////////////////////								
			}		
			
			//Bepaald welk gesprek wordt getoont
			function chooseConversation($selectedConversation) 
			{
				//global $selectedChat;
				global $conversation_ID;
				global $selectedCheck;
				//$selectedChat = $selectedConversation;
				$conversation_ID = $selectedConversation;
				$selectedCheck = TRUE;
				$_SESSION['conversation_ID'] = $selectedConversation;
				
				selectedConversation();				
			}
			
			//Functie getter wanneer een gesprek gekozen is
			if (isset($_GET['gesprek']) && $admin_status == 1)
			{
				chooseConversation($_GET['gesprek']);
			}
			
			//Functie getter wanneer een nieuw gesprek gekozen is
			if (isset($_GET['nieuw_gesprek']) && $admin_status == 1)
			{
				echo "<table style='width: auto;'><tr><th><a class='text-title'>Kies een gebruiker om een nieuw gesprek mee te starten.</a></th></tr></table>";
				
				$userCheck ="";
				$userArray = array();
				$i = 0;
				
				$query_conversation_user_ID = "SELECT user_ID FROM conversation";
				$query_conversation_user_ID_result = $connection->query($query_conversation_user_ID);
				if ($query_conversation_user_ID_result->num_rows > 0) 
				{
					// output data of each row
					while($row1 = $query_conversation_user_ID_result->fetch_assoc()) 
					{
						$i++;				
						$userCheck = $row1["user_ID"];	
						$userArray[$i] = $userCheck;
					}
				}
				$userArray = implode ( ', ', $userArray);
				$query_user_ID = "SELECT * FROM user WHERE user_ID NOT IN ($userArray)";
				$query_user_ID_result = $connection->query($query_user_ID);
				if ($query_user_ID_result->num_rows > 0) 
				{
					// output data of each row
					while($row1 = $query_user_ID_result->fetch_assoc()) 
					{
						$user_ID = $row1["user_ID"];
						$username = $row1["user_name"];
						$admin_status = $row1["admin"];
								
						if ($admin_status == 1)
						{
							$admin_status = "Admin";
						}
						else
						{
							$admin_status = "Gebruiker";
						}			
		
						echo "<table class='newuser_gesprek'>
						<tr><th class='textbox'><a class='user'>Gebruiker</a></th><th class='newuser_gesprek'> $user_ID </th></tr>
						<tr><th class='textbox'><a class='user'>Naam</a></th><th class='newuser_gesprek'> $username </th></tr>
						<tr><th class='textbox'><a class='user'>Rechten</a></th><th class='newuser_gesprek'> $admin_status </th></tr>
						<tr class='textbox_bottom'><th></th><th><a class='button' href=\"overzicht.php?conversation_user=$user_ID\">Selecteer</a></th></tr></table>";	
					}
				}				
			}

			//Functie getter wanneer een gebruiker voor een nieuw gesprek gekozen is
			if (isset($_GET['conversation_user']) && $admin_status == 1)
			{
				$user_ID = $_GET['conversation_user'];
				
				$query_conversation_user_ID = "SELECT user_ID FROM conversation WHERE user_ID='$user_ID'";
				$query_conversation_user_ID_result = $connection->query($query_conversation_user_ID);
				$sql="SELECT user_name FROM user WHERE user_name='$username'";
				$result = $connection->query($sql);
				
				if ($result->num_rows > 0)
				{
					echo"<br> Deze naam bestaat al, probeer een andere naam in te voeren.</br>";
				}
				
				$query_new_conversation    = "INSERT into `conversation` (user_ID)
				VALUES ('$user_ID')";
				$query_new_conversation_result   = mysqli_query($connection, $query_new_conversation);
				if ($query_new_conversation_result) 
				{
					echo "<div class='form'>
					<h3>Het gesprek is succesvol aangemaakt.</h3><br/>
					</div>";
				} 
				else 
				{
					echo "<div class='form'>
					<h3>Er is iets fout gegaan, probeer het opnieuw.</h3><br/>
					</div>";
				}
				$connection->close();	
				header("Location: overzicht.php");
				exit();				
			}
			
			//Functie wanneer een gebruiker een bestand gekozen heeft om te downloaden.
			if (isset($_REQUEST['chosenFile'])) 
			{		
				$chosenFile = ($_REQUEST['chosenFile']);
				$chosenOwner = ($_REQUEST['fileOwner']);
				
				retrieveFile($chosenOwner, $chosenFile);
			}
			
			function selectedConversation()
			{	
				global $connection;
				global $selectedCheck;
				global $conversation_ID;
				
				if ($selectedCheck === TRUE)
				{
					$query_comment_conversation_ID = "SELECT * FROM comment WHERE conversation_ID='$conversation_ID'";
					$query_comment_conversation_ID_result = $connection->query($query_comment_conversation_ID);
					if ($query_comment_conversation_ID_result->num_rows > 0) 
					{
						// output data of each row
						while($row1 = $query_comment_conversation_ID_result->fetch_assoc()) 
						{
							$comment_conversation_ID = $row1["conversation_ID"];
							$username = $row1["user_ID"];			
							$query_username = "SELECT user_name FROM user WHERE user_ID='$username'";
							$query_username_result = $connection->query($query_username);
							$message_owner ="";
												
							// output data of each row
							while($row2 = $query_username_result->fetch_assoc()) 
							{
								$message_owner = $row2["user_name"];
							}	
							
							echo "<table class='user_gesprek'><tr class='textbox_bottom'><th></th><th>
							<a class='user'>" . $row1["date"]. "</a></th></tr>
							<tr><th class='textbox'><a class='user'>" . $message_owner. "</a></th>
							<th>" . $row1["comment"]. "</th></tr>";
							
							if($row1['uploaded_file'] != NULL)
							{
								//echo '<tr class="textbox"><th></th><th><a href="../backend/uploaded files/'.$message_owner.'/'.$row1['uploaded_file'].'">'.$row1['uploaded_file'].'</a></th></tr>';
								
								echo '<tr class="textbox"><th><form action="" method="post" enctype="multipart/form-data">
									<input type=hidden value="' .$message_owner. '" name=fileOwner />
									<th><input class=button type=submit value="' .$row1['uploaded_file']. '" name=chosenFile /></th>
									</form></th></tr>';	
							}
							echo "</table>";
						}
					}	
					else 
					{
						echo "<table class='textbox_bottom'><tr><th><a class='user'>Nog geen berichten geplaatst.</a></th></tr></table>"; 
					}
					//$connection->close();							
				}
				else
				{
					//echo "<BR>FALSE<BR>ID - " . $conversation_ID . "<BR>CHECK - " . $selectedCheck . "<BR>";
					//echo "<script>alert('FALSE');</script>"; 
				}
			}
			
			function retrieveFile($owner, $filename)
			{
				//echo "<table class='user_gesprek'><tr><th>". $owner ."</th></tr>";
				//echo "<tr><th>". $filename ."</th></tr>";
				//echo '<tr class="textbox"><th><a href="../backend/uploaded files/'.$owner.'/'.$filename.'">'.$filename.'</a></th></tr></table>';
				//$filename ="stuck_at_home.zip";
				$file_location = $_SERVER["DOCUMENT_ROOT"] . "/Muziekproject/backend/uploaded files/".$owner."/".$filename."";
				
				echo "<tr><th>". $file_location ."</th></tr></table>";
				
				ob_clean();
				
				if (file_exists($file_location)) 
				{
					header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
					header("Cache-Control: public"); // needed for internet explorer
					header("Content-Type: application/mp3/zip/rar/flac/mp4/wav");
					header("Content-Transfer-Encoding: Binary");
					header("Content-Length:".filesize($file_location));
					header("Content-Disposition: attachment; filename=$filename");
					readfile($file_location);
					
					//echo "<script>alert('File: ".$file_location."');</script>"; 
					exit();    				
				} 
				else 
				{
					echo "<tr><th>". $file_location ."</th></tr></table>";
					exit("Error: File not found.");
				} 				
			}	
			
		?>
		
		<table class="textbox_bottom" style="text-align: left; width: auto;">
			<tr>
				<!--Wanneer ingelogd en geplaatst in een gesprek-->
				<?php if ($_SESSION != null && $conversation_ID != null && $selectedCheck == TRUE) : ?>		
					<form action="../backend/upload.php" method="post" enctype="multipart/form-data">
						<th><input class="input" type="text" placeholder="voorbeeld tekst" name="comment" required="required"/></th>
						<th><input class="input" type="file" name="fileToUpload" value="" id="fileToUpload"></th>
						<th><input class="button" type="submit" value="Versturen" name="submit"></th>
					</form>
					
				<?php endif; ?>
			</tr>
		</table>
		
	</body>
	
	<footer>
	</footer>
</html>