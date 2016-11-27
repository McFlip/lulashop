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
				//TODO: email all event followers
				$to = "gradydenton@yahoo.com";
				$subject = "lulashop event notification";
				$txt = "An event you have been following has been deleted.";
				mail($to,$subject,$txt);
				echo "This event has been deleted. Those following this event have been notified. <br>";
				echo "This event will disappear from your calendar view when it is refreshed.";
			} else if ($_POST["submit"]=="HOST") {
			  $sql = "UPDATE `event` SET `userID`=".$user." WHERE `eventID` = ".$eventID.";";
				$conn->exec($sql);
			  $sql = "INSERT INTO `followEvent` VALUES ($user,$eventID)";
				$conn->exec($sql);
				echo "You are now set as the host of this event.<br>";
			} else if ($_POST["submit"]=="FOLLOW") {
			  $sql = "INSERT INTO `followEvent` VALUES ($user,$eventID)";
				echo $sql;
				$conn->exec($sql);
				echo "You are now following this event.<br>";
			} else if ($_POST["submit"]=="ADD") {
			  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
			  if ($email){
			    $sql = "SELECT `memberID` FROM `member` WHERE `email`='".$email."';";
			    $pdo = $conn->query($sql);
			    $result = $pdo->fetchColumn();
			    if($result) {
			      $sql = "INSERT INTO `fair` VALUES ($eventID,$result)";
			      $conn->exec($sql);
			    }
			  }
			} else if ($_POST["submit"]=="REMOVE") {
			  $sql = "DELETE FROM `fair` WHERE `eventID`=".$eventID."
			          AND `memberID`=".$_POST["memberID"].";";
			  $conn->exec($sql);
			}
		}
		$sql = "SELECT * FROM `event` WHERE `eventID` = ".$eventID.";";
		$pdo = $conn->query($sql);
		$result = $pdo->fetch();
		//print all the info
		echo "Event ID: ".$eventID."<br>"; //TODO: DELETE me
		echo "Event Type: ".$result['category']."<br>";
		if($result['season']){
		  echo $result['season']."<br>";
		}
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
		} else if (($result['category']=="party" || $result['category']=="fair") && $userType=="user"){
		  echo "<form class=\"w3-container\" method=\"post\" action=\"showevent.php\">";
		  echo "<label>I would like to host this party</label>";
		  echo "<input type=\"submit\" name=\"submit\" value=\"HOST\">";
			echo "<input type=\"number\" name=\"eventID\"hidden value=\"";
			echo $result["eventID"];
			echo "\">";
			echo "</form>";
		}
		if ($result["category"] == "fair") {
		  echo "The following consultants are participating:<br>";
		  echo "<table class=\"w3-table w3-striped\">";
		  echo "<tr><th>First Name</th><th>Last Name</th><th>email</th></tr>";
		  $sql = "SELECT `fair`.`memberID`,`firstName`,`lastName`,`email`
		    FROM `fair`,`member`
			  WHERE `fair`.`eventID`='$eventID' AND `fair`.`memberID`=`member`.`memberID`;";
		  $pdo = $conn->query($sql);
		  while ($fair = $pdo->fetch()){
		    echo "<tr>";
		    echo "<td>".$fair['firstName']."</td><td>".$fair['lastName']."</td><td>".
		    "<a href=\"mailto:".$fair['email'].
		    "?Subject=lulashop%20event%20notification\">".$fair['email']."</td>";
		    if ($userType == "member") {
		      echo "<td>";
		      echo "<form class=\"w3-container\" method=\"post\" action=\"showevent.php\">";
		      echo "<input type=\"submit\" name=\"submit\" value=\"REMOVE\">";
		      echo "<input type=\"number\" name=\"eventID\"hidden value=\"".$eventID."\">";
					echo "<input type=\"number\" name=\"memberID\"hidden value=\"".$fair['memberID']."\">";
					echo "</form></td>";
		    }
		    echo "</tr>";
		  }
		  if ($userType == "member") {
		    echo "<form class=\"w3-container\" method=\"post\" action=\"showevent.php\">";
	      echo "<input class=\"w3-input\" type=\"email\" name=\"email\">";
	      echo "<label class=\"w3-label w3-validate\">Enter email of participating consultants</label>";
	      echo "<input type=\"submit\" name=\"submit\" value=\"ADD\">";
	      echo "<input type=\"number\" name=\"eventID\"hidden value=\"".$eventID."\">";
				echo "</form>";
		  }
		}
		if ($userType == "member") {
			$start[10] = 'T';
			$end[10] = 'T';
			echo "<form class=\"w3-container\" method=\"post\" action=\"showevent.php\">";
			echo "<input type=\"submit\" name=\"submit\" value=\"DELETE\">";
			echo "<input type=\"number\" name=\"eventID\"hidden value=\"".$result["eventID"]."\">";
			echo "</form>";
			echo "<form class=\"w3-container\" method=\"post\" action=\"createevent.php\" target=\"_parent\">";
			echo "<input type=\"submit\" name=\"modify\" value=\"modify\">";
			echo "<input type=\"number\" name=\"eventID\"hidden value=\"".$result["eventID"]."\">";
			echo "<input type=\"datetime-local\" name=\"start\"hidden value=\"".$start."\">";
			echo "<input type=\"datetime-local\" name=\"end\"hidden value=\"".$end."\">";
			echo "<input type=\"text\" name=\"category\"hidden value=\"".$result["category"]."\">";
			echo "<input type=\"text\" name=\"season\"hidden value=\"".$result["season"]."\">";
			echo "<input type=\"number\" name=\"private\"hidden value=\"".$result["private"]."\">";
			echo "<input type=\"text\" name=\"url\"hidden value=\"".$result["url"]."\">";
			echo "<input type=\"number\" name=\"addressID\"hidden value=\"".$result["addressID"]."\">";
			echo "<input type=\"number\" name=\"userID\"hidden value=\"".$result["userID"]."\">";
			echo "<input type=\"number\" name=\"memberID\"hidden value=\"".$result["memberID"]."\">";
			echo "<input type=\"number\" name=\"modify\"hidden value=\"1\">";
			echo "</form>";
		} else {
		  $sql = "SELECT `userID` FROM `followEvent` WHERE `eventID`=".$eventID."
		          AND `userID`=".$user;
			$pdo = $conn->query($sql);
			$result = $pdo->fetchColumn();
			if ($result == false){
			  echo "<form class=\"w3-container\" method=\"post\" action=\"showevent.php\">";
			  echo "<label>I would like to follow this event</label>";
			  echo "<input type=\"submit\" name=\"submit\" value=\"FOLLOW\">";
				echo "<input type=\"number\" name=\"eventID\"hidden value=\"".$eventID."\">";
				echo "</form>";
			}
		}
	}
	$conn = null;
?>
</body>