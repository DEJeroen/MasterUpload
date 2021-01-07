<!DOCTYPE html>
<html>
	<head>
		<title>Indexpagina MuziekProject</title>
	</head>
	<body>
		<h1>Indexpagina MuziekProject</h1>
		
		<?php
			require("Backend/config.php");
			//include("Backend/auth_session.php");
		
			$connection = $db_connection; 
			//Check connection
			if ($connection->connect_error) 
			{
				die("Connection failed: " . $connection->connect_error);
			} 
			
			$Session = "";
			//Test om te kijken of er session login is
			foreach ($_SESSION as $key=>$Session)
			{
				echo "Welkom, <b>".$Session."</b><hr>";
			}
				
			$processed_1 ="";
			$processed_2 ="";
			$processed_3 ="";
			$processed_4 ="";
			
			$query_part1 = "SELECT user_ID FROM user WHERE user_name='$Session'";
			$query_result1 = $connection->query($query_part1);
			if ($query_result1->num_rows > 0) 
			{
				// output data of each row
				while($row1 = $query_result1->fetch_assoc()) 
				{
					$processed_1 = $row1["user_ID"];
					//echo "<br> query_part1 -  $processed_1";
				}
				
				$query_part2 = "SELECT DISTINCT conversation_ID FROM comment WHERE user_ID='$processed_1'";
				$query_result2 = $connection->query($query_part2);
				if ($query_result2->num_rows > 0) 
				{
					// output data of each row
					while($row1 = $query_result2->fetch_assoc()) 
					{
						$processed_2 = $row1["conversation_ID"];
						//echo "<br> query_part2 -  $processed_2";
					}
				}
				
				$query_part3 = "SELECT * FROM comment WHERE conversation_ID='$processed_2'";
				$query_result3 = $connection->query($query_part3);
				if ($query_result3->num_rows > 0) 
				{
					// output data of each row
					while($row1 = $query_result3->fetch_assoc()) 
					{
						$processed_3 = $row1["conversation_ID"];
						//echo "<br> query_part3 -  $processed_3";
						
						$processed_4 = $row1["user_ID"];
						//echo "<br> query_part4 -  $processed_4";
						
						$query_part4 = "SELECT user_name FROM user WHERE user_ID='$processed_4'";
						$query_result4 = $connection->query($query_part4);
						$message_owner ="";
										
						// output data of each row
						while($row2 = $query_result4->fetch_assoc()) 
						{
							$message_owner = $row2["user_name"];;
						}
						
						echo "" . $row1["date"]. 
						"<br><b>" . $message_owner.
						"</b><br><br>" . $row1["comment"]. 
						'<br><a href="'.$row1['uploaded_file'].'">'.$row1['uploaded_file'].'</a></br><hr>';
					}
				}	
				else 
				{
					echo "0 resultaten";
				}
				//$connection->close();				
			}
		
//////////////////////DANGER ZONE////////////////////////DANGER ZONE//////////////////////////////
			
			//require("Backend/config.php");
			//include("Backend/auth_session.php");
		
			//$connection = $db_connection; 
			//Check connection
			//if ($connection->connect_error) 
			//{
			//	die("Connection failed: " . $connection->connect_error);
			//} 
			if (isset($_REQUEST['comment'])) 
			{
				// removes backslashes
				//$comment = stripslashes($_REQUEST['comment']);
				//escapes special characters in a string
				//$comment = mysqli_real_escape_string($connection, $comment);
				
				$comment = ($_REQUEST['comment']);
				$user_ID = $processed_1;
				$conversation_ID = $processed_2;
				
				//echo"$comment <br>";
				//echo"$user_ID <br>";
				//echo"$conversation_ID <br>";
				
				if (empty($comment))
				{
					echo"<br><b> Er is niets ingevuld, probeer het opnieuw.</b></br>";
				}
				else 
				{
					$comment_query    = "INSERT into `comment` (comment, user_ID, conversation_ID)
					VALUES ('$comment', '$user_ID', '$conversation_ID')";
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
				
				$connection->close();	
				header("Location: index.php");
				exit();
			}		
			else 
			{
		
			}
			
//////////////////////DANGER ZONE////////////////////////DANGER ZONE//////////////////////////////
		?>
		
		<?php if ($_SESSION != null) : ?>
		
			<html>
				<form action="index.php" method="post">
					<br><input type="text" name="comment" required="required"/>
					<input type="submit" value="Bericht Versturen"/>
					<input type="button" value="Bestand Uploaden"/>
				</form>
				
				<br><a href="logout.php"> Klik hier om uit te loggen. </a>
				
			</html>
			
		<?php endif; ?>
		
		<?php if ($_SESSION == null) : ?>
		
			<html>
				<br><a href="login.php"> Klik hier voor inloggen. </a>
				<br><a href="register.php"> Klik hier voor te registeren. </a>
			</html>
			
		<?php endif; ?>
		
	</body>
</html>