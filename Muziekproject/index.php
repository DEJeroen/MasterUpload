<!DOCTYPE html>
<?php require_once("Backend/config.php"); ?>
<html>
	<head>
		<title>Indexpagina MuziekProject</title>
	</head>
	<body>
		<h1>Indexpagina MuziekProject</h1>
		
		<?php
			$conn = $db_connection; 
			//Check connection
			if ($conn->connect_error) 
			{
				die("Connection failed: " . $conn->connect_error);
			} 
			
			echo "<hr><p>PHP gebied.</p>";
			
			$sql = "SELECT * FROM comment";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) 
			{
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo "<hr><b>comment_ID: " . $row["comment_ID"]. 
					"</b><br> - conversation_ID: " . $row["conversation_ID"]. 
					"<br> - comment: " . $row["comment"]. 
					"<br> - date: " . $row["date"]. 
					"<br> - message_from: " . $row["message_from"]. 
					"<br> - uploaded_file: " . $row["uploaded_file"]. "<br><hr>";
				}
			} 
			else 
			{
				echo "0 results";
			}
			$conn->close();
		?>
		
		<br><a href="login.php"> Klik hier voor inloggen. 
        <br><a href="register.php"> Klik hier voor te registeren. 
	</body>
</html>