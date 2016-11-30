<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<div class="w3-panel w3-blue">
		<h3>Followed Events</h3>
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
	} catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
		die();
	}
	?>
</head>

<body>
<?php
$userType = $_SESSION["userType"];
$user = $_SESSION["userID"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$eventID = $_POST['eventID'];
	$sql = "DELETE FROM `followEvent`
	        WHERE `eventID`=".$eventID."
	        AND `userID`=".$user.";";
	$conn->exec($sql);
}
$sql = "SELECT `category`,`start`,`end`,`firstName`,`lastName`,`followEvent`.`eventID`
	       FROM `event`,`followEvent`,`member`
	       WHERE `followEvent`.`userID`=".$user."
				 AND `followEvent`.`eventID`=`event`.`eventID`
				 AND `event`.`memberID`=`member`.`memberID`;";
$pdo = $conn->query($sql);
?>
<table class="w3-table w3-striped">
	<tr>
		<th>Category</th>
		<th>Start</th>
		<th>End</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Details</th>
		<th>Unfollow</th>
	</tr>
	<?php
		while($result = $pdo->fetch()){
			echo "<tr>";
			for($i = 0; $i < 5; $i++){
				echo "<td>".$result[$i]."</td>";
			}
			echo "<td>";
			echo "<form class=\"w3-container\" method=\"post\" action=\"showevent.php\" target=\"showevent\">";
			echo "<input type=\"submit\" name=\"submit\" value=\"Details\">";
			echo "<input type=\"number\" name=\"eventID\"hidden value=\"".$result["eventID"]."\">";
			echo "</form>";
			echo "</td><td>";
			echo "<form class=\"w3-container\" method=\"post\" action=\"listevent.php\" target=\"_self\">";
			echo "<input type=\"submit\" name=\"submit\" value=\"Unfollow\">";
			echo "<input type=\"number\" name=\"eventID\"hidden value=\"".$result["eventID"]."\">";
			echo "</form>";
			echo "</td></tr>";
		}
	?>
</table>


