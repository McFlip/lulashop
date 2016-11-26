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
		echo "Event ID: ".$eventID."<br>"; //TODO: DELETE me
		echo "Event Type: ".$result['category']."<br>";
		echo "This event is ";
		if ($result["private"]){
			echo "private.<br>";
		} else {
			echo "public.<br>";
		}
		$start = $result['start'];
		$start = new DateTime($start, new DateTimeZone('UTC'));
		$sql = "SELECT `timezoneOffset` FROM `$userType` WHERE ";
		if($userType == "user"){
			$sql = $sql."	userID='$user';";
		} else {
			$sql = $sql."	memberID='$user';";
		}
		$pdo = $conn->query($sql);
		$userTZ = $pdo->fetchColumn();
		$abbrev  = DateTimeZone::listAbbreviations();
		$timezoneName = $abbrev[$userTZ];
		$timezoneName = $timezoneName[0]['timezone_id'];
		$start->setTimezone(new DateTimeZone($timezoneName));
		$start = $start->format('Y-m-d H:i:s');
		$end = $result['end'];
		$end = new DateTime($end, new DateTimeZone('UTC'));
		$end->setTimezone(new DateTimeZone($timezoneName));
		$end = $end->format('Y-m-d H:i:s');
		echo "FROM: ".$start."<br>";
		echo "TO: ".$end."<br>";
		$url = $result['url'];
		if ($url != NULL){
			echo "URL: <a href=\"$url\" target=\"_blank\">".$url."</a><br>";
		}
		$addressID = $result["addressID"];
		if ($addressID){
			$sql = "SELECT * FROM `address` WHERE `addressID`=$addressID;";
			$pdo = $conn->query($sql);
			$location = $pdo->fetch();
			echo "This event is held at:<br>";
			echo $location['street1']."<br>";
			echo $location['street2']." ".$location['appt']."<br>";
			echo $location['city'].", ".$location['state']." ".$location['zip']."<br>";
		} else {
			echo "This is an online only event or the address is TBD.<br>";
		}
		$userID = $result["userID"];
		if ($userID){
			$sql = "SELECT `firstName`, `lastName`, `email` FROM `user` WHERE `userID`=$userID;";
			$pdo = $conn->query($sql);
			$host = $pdo->fetch();
			echo "This party is hosted by: ".$host['firstName']." ".$host['lastName']."<br>";
			echo "<a href=\"mailto:".$host['email']."?Subject=lulashop%20event%20notification\">";
			echo $host['email']."</a><br>";
		}
		if ($userType == "member") {
			$start[10] = 'T';
			$end[10] = 'T';
			echo "start - ".$start." end - ".$end."<br>"; //TODO: DELETE me
			echo "<form class=\"w3-container\" method=\"post\" action=\"showevent.php\">";
			echo "<input type=\"submit\" name=\"submit\" value=\"DELETE\">";
			echo "<input type=\"number\" name=\"eventID\"hidden value=\"";
			echo $result["eventID"];
			echo "\">";
			echo "</form>";
			echo "<form class=\"w3-container\" method=\"post\" action=\"createevent.php\" target=\"_parent\">";
			echo "<input type=\"submit\" name=\"modify\" value=\"modify\">";
			echo "<input type=\"number\" name=\"eventID\"hidden value=\"";
			echo $result["eventID"];
			echo "\">";
			echo "<input type=\"datetime-local\" name=\"start\"hidden value=\"";
			echo $start;
			echo "\">";
			echo "<input type=\"datetime-local\" name=\"end\"hidden value=\"";
			echo $end;
			echo "\">";
			echo "<input type=\"text\" name=\"category\"hidden value=\"";
			echo $result["category"];
			echo "\">";
			echo "<input type=\"text\" name=\"season\"hidden value=\"";
			echo $result["season"];
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