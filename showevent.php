<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<div class="w3-panel w3-blue">
		<h3>Event Details</h3>
	</div>
	<?php
	//TODO: create account for this app
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "lulashop";
	// Connect to the database
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
		die();
	}
	?>
</head>

<body>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$userType = $_SESSION["userType"];
		$user = $_SESSION["userID"];
		$eventID = $_POST["eventID"];
		$sql = "SELECT * FROM `event` WHERE `eventID` = ".$eventID.";";
		$pdo = $conn->query($sql);
		$result = $pdo->fetch();
		//print all the info
		echo "This event is ";
		if ($result["private"]){
			echo "private.";
		} else {
			echo "public.";
		}
		if ($userType == "member") {
			echo "<form class=\"w3-container\" method=\"post\" action=\"showevent.php\">";
			echo "<input type=\"submit\" name=\"submit\" value=\"DELETE\">";
			echo "</form>";
		}
	}
	$conn = null;
?>
</body>