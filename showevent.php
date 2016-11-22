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
		if (isset($_POST["submit"])) {
			if ($_POST["submit"]=="DELETE") {
				$sql = "DELETE FROM `event` WHERE `eventID` = ".$eventID.";";
				$conn->exec($sql);
				echo "This event has been deleted. Those following this event have been notified. <br>";
				echo "This event will disappear from your calendar view when it is refreshed.";
			}
		}

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
			echo "<input type=\"number\" name=\"eventID\"hidden value=\"";
			echo $result["eventID"];
			echo "\">";
			echo "</form>";
			echo "<form class=\"w3-container\" method=\"post\" action=\"createevent.php\" target=\"_parent\">";
			echo "<input type=\"submit\" name=\"submit\" value=\"modify\">";
			echo "<input type=\"number\" name=\"eventID\"hidden value=\"";
			echo $result["eventID"];
			echo "\">";
			echo "<input type=\"number\" name=\"length\"hidden value=\"";
			echo $result["length"];
			echo "\">";
			echo "<input type=\"text\" name=\"category\"hidden value=\"";
			echo $result["category"];
			echo "\">";
			echo "<input type=\"number\" name=\"private\"hidden value=\"";
			echo $result["private"];
			echo "\">";
			echo "<input type=\"text\" name=\"url\"hidden value=\"";
			echo $result["url"];
			echo "\">";
			echo "<input type=\"number\" name=\"addressID\"hidden value=\"";
			echo $result["addressID"];
			echo "\">";
			echo "<input type=\"number\" name=\"userID\"hidden value=\"";
			echo $result["userID"];
			echo "\">";
			echo "<input type=\"number\" name=\"memberID\"hidden value=\"";
			echo $result["memberID"];
			echo "\">";
			echo "<input type=\"number\" name=\"modify\"hidden value=\"1\">";
			echo "</form>";
		}
	}
	$conn = null;
?>
</body>