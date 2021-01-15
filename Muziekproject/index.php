<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css">
		<title>Indexpagina MuziekProject</title>
	</head>
	<body>
		<div class="header">
			<h1>Indexpagina MuziekProject</h1>
		</div> 
		<a href="index.php">HOME</a>
		
		<?php
			require("Backend/config.php");
			//include("Backend/auth_session.php");
		
			$connection = $db_connection; 
			//Controleer verbinding
			if ($connection->connect_error) 
			{
				die("Connection failed: " . $connection->connect_error);
			} 
			
			$Session = "";
			$admin_status ="";
			$user_ID ="";
			$loggedAs ="";
			$conversation_ID ="";
			$comment_conversation_ID ="";
			$username ="";
			$selectedConversation ="";
			$selectedCheck ="";
			$selectedChat = "";
			
			//Test om te kijken of er session login is
			foreach ($_SESSION as $key=>$Session)
			{	
				echo "<br><hr>Welkom, <user>".$Session."</user><hr>";
			}
				
			$query_user_ID = "SELECT user_ID FROM user WHERE user_name='$Session'";
			$query_user_ID_result = $connection->query($query_user_ID);
			if ($query_user_ID_result->num_rows > 0) 
			{
				// output data of each row
				while($row1 = $query_user_ID_result->fetch_assoc()) 
				{
					$user_ID = $row1["user_ID"];
					$loggedAs = $row1["user_ID"];
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
					$query_conversation_ID = "SELECT * FROM conversation";
					$query_conversation_ID_result = $connection->query($query_conversation_ID);
					if ($query_conversation_ID_result->num_rows > 0) 
					{
						// output data of each row
						while($row1 = $query_conversation_ID_result->fetch_assoc()) 
						{
							$conversation_ID = $row1["conversation_ID"];
							$user_ID = $row1["user_ID"];	
							
							//echo "<br>Conversation_ID - $conversation_ID  
							//<br> User_ID - $user_ID ";
							
							$query_user_ID = "SELECT user_name FROM user WHERE user_ID='$user_ID'";
							$query_user_ID_result = $connection->query($query_user_ID);
							if ($query_user_ID_result->num_rows > 0) 
							{
								// output data of each row
								while($row1 = $query_user_ID_result->fetch_assoc()) 
								{
									echo "<br> Gebruiker - <user>" . $row1["user_name"] . "</user>
									<br><a href=\"index.php?gesprek=$conversation_ID\">Selecteer</a><br><br><hr>";
								}
							}
						}
					}
					
					echo "<br><hr><a href=\"index.php?nieuw_gesprek\">Begin nieuw gesprek</a><hr>";	
					
					if (isset($_GET['gesprek']))
					{
						echo "<br><h3>Huidig, <b><user>Gesprek " . $_GET['gesprek'] . "</h3></user></b><hr>";
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
						//echo "<br>Gesprek ID - $conversation_ID ";
						echo "<br>Nog geen gesprek gestart <br><br><hr>";
					}					
				}
////////////////////USER AREA/////////////////////////USER AREA///////////////////////////								
			}
			
			//Bepaald welk gesprek wordt getoont
			function chooseConversation($selectedConversation) 
			{
				global $selectedChat;
				global $conversation_ID;
				global $selectedCheck;
				$selectedChat = $selectedConversation;
				$conversation_ID = $selectedConversation;
				$selectedCheck = TRUE;
				
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
				echo "<br><hr><b><h3>Kies een gebruiker om een nieuw gesprek mee te starten.</h3></b><hr>";
				
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
									
						echo "<a href=\"index.php?conversation_user=$user_ID\">Selecteer</a><br>";	
									
						echo "Gebruiker - $user_ID <br>
						Naam - <user> $username </user><br>
						Rechten - $admin_status <br><hr>";
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
				$result = $conn->query($sql);
				
				if ($result->num_rows > 0)
				{
					echo"<br><b> Deze naam bestaat al, probeer een andere naam in te voeren.</b></br>";
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
				header("Location: index.php");
				exit();				
			}
		
			function selectedConversation()
			{	
				global $connection;
				global $selectedCheck;
				global $conversation_ID;
				//global $selectedChat;
				
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
														
							echo "" . $row1["date"]. 
							"<br><br><user>" . $message_owner.
							"</user>: - <b>" . $row1["comment"]. 
							'</b><br><br><a href="'.$row1['uploaded_file'].'">'.$row1['uploaded_file'].'</a></br><hr>';
						}
					}	
					else 
					{
						echo "0 resultaten";
					}
					//$connection->close();							
				}
				else
				{
					//echo "<BR>FALSE<BR>ID - " . $conversation_ID . "<BR>CHECK - " . $selectedCheck . "<BR>";
					//echo "<script>alert('FALSE');</script>"; 
				}
			}
							
			if (isset($_REQUEST['comment']) && $_SESSION != null) 
			{
				$comment = ($_REQUEST['comment']);
				
				if (empty($comment))
				{
					echo"<br><b> Er is niets ingevuld, probeer het opnieuw.</b></br>";
				}
				else 
				{
					$comment_query    = "INSERT into `comment` (comment, user_ID, conversation_ID)
					VALUES ('$comment', '$loggedAs', '$selectedChat')";
					$comment_result   = mysqli_query($connection, $comment_query);
					if ($comment_result) 
					{
						echo "<div class='form'>
							  <h3>Je bericht is succesvol geupload.</h3><br/>
							  </div>";
					} 
					else 
					{
						echo "<div class='form'>
							  <h3>Er is iets fout gegaan, probeer het opnieuw.</h3><br/>
							  </div>";
					}
				}
				
				echo "<BR>Comment - " . $comment . "<BR>loggedAs - " . $loggedAs . "<BR>selectedChat - " . $selectedChat . "<BR>Conversation_ID - " . $conversation_ID . "<BR>";

				$connection->close();	
				header("Location: ");
				exit();
			}		
		?>
		
		<!--Wanneer ingelogd en geplaatst in een gesprek-->
		<?php if ($_SESSION != null && $conversation_ID != null && $selectedChat != null) : ?>
		
			<html>
				<form action="" method="post">
					<br><input type="text" name="comment" required="required"/>
					<input type="submit" value="Bericht Versturen"/>
					<input type="button" value="Bestand Uploaden"/>
				</form>
		<?php endif; ?>
		
		<!--Wanneer ingelogd en admin-->
		<?php if ($_SESSION != null && $admin_status == 1) : ?>		
				<br><a href="register.php"> Klik hier voor te registeren. </a>
			</html>		
		<?php endif; ?>
		
		<!--Wanneer ingelogd-->
		<?php if ($_SESSION != null) : ?>		
				<br><a href="logout.php"> Klik hier om uit te loggen. </a></br>
			</html><br>
		<?php endif; ?>
		
		<!--Wanneer niet ingelogd-->
		<?php if ($_SESSION == null) : ?>
			<html>
				<br><a href="login.php"> Klik hier voor inloggen. </a>
			</html>	
		<?php endif; ?>
		
	</body>
	<footer>
	</footer>
</html>