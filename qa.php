<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
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
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		//sanatize input
		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		if (isset($_SESSION["userType"])){
			$userType = $_SESSION["userType"];
			$user = $_SESSION["userID"];
		}
		$abbrev  = DateTimeZone::listAbbreviations();
		$sku = $_POST["sku"];
		$sql = "SELECT `firstName`,`lastName`,`timezoneOffset`,`_date`,`rank`,`txt`,`questionID`
						FROM `question`,`user`
						WHERE `sku`=".$sku." AND `question`.`userID`=`user`.`userID`
						ORDER BY `rank` DESC";
		echo $sql;
		$pdo = $conn->query($sql);
		while ($questionList = $pdo->fetch()){
			$qdate = new DateTime($questionList["_date"], new DateTimeZone('UTC'));
			$userTZ = $questionList["timezoneOffset"];
			$timezoneName = $abbrev[$userTZ];
			$timezoneName = $timezoneName[0]['timezone_id'];
			$qdate->setTimezone(new DateTimeZone($timezoneName));
			$qdate = $qdate->format('Y-m-d H:i:s');
			echo "<div class=\"w3-container w3-card\">";
			echo "<h10>(".$qdate.")".$questionList["firstName"]." ".$questionList["lastName"]." asks:</h10>";
			echo "<p>".$questionList["txt"]."</p>";
			echo "</div>";
			$sql = "SELECT `firstName`,`lastName`,`timezoneOffset`,`_date`,`rank`,`txt`,`answerID`
							FROM `answers`,`member`
							WHERE `questionID`=".$questionList["questionID"]."
							AND `answers`.`memberID`=`member`.`memberID`
							ORDER BY `rank` DESC";
			$pdo2 = $conn->query($sql);
			while ($answerList = $pdo2->fetch()){
				$adate = new DateTime($answerList["_date"], new DateTimeZone('UTC'));
				$memberTZ = $answerList["timezoneOffset"];
				$timezoneName = $abbrev[$memberTZ];
				$timezoneName = $timezoneName[0]['timezone_id'];
				$adate->setTimezone(new DateTimeZone($timezoneName));
				$adate = $adate->format('Y-m-d H:i:s');
				echo "<div class='w3-row w3-container w3-padding-8'>";
				echo "<div class='w3-quarter'>";
				echo "---->";
				echo "</div>";
				echo "<div class='w3-rest'>";
				echo "<div class=\" w3-card\">";
				echo "<h10>(".$adate.")".$answerList["firstName"]." ".$answerList["lastName"]." answers:</h10>";
				echo "<p>".$answerList["txt"]."</p>";
				echo "</div></div>";
			}
			if ($userType == "member"){
				echo "<div class=\"w3-card\">";
				echo "<form method=\"post\" action=\"qa.php\" target=\"_self\">";
				echo "<input class=\"w3-input w3-border\" type=\"text\" name=\"answer\">";
				echo "<label class=\"w3-validate\"> Reply Here</label>";
				echo "<input type=\"submit\" name=\"submit\" value=\"ANSWER\">";
				echo "<input type=\"number\" name=\"questionID\" hidden value=\"".$questionList["questionID"]."\">";
				echo "<input type=\"number\" name=\"sku\" hidden value=\"".$sku."\">";
				echo "</form></div>";
			}
		}
		if (isset($_SESSION["userType"])){
			if ($userType == "user"){
			echo "<div class=\"w3-card\">";
			echo "<form method=\"post\" action=\"qa.php\" target=\"_self\">";
			echo "<input class=\"w3-input w3-border\" type=\"text\" name=\"question\">";
			echo "<label class=\"w3-validate\"> Enter Your Question Here</label>";
			echo "<input type=\"submit\" name=\"submit\" value=\"ASK\">";
			echo "<input type=\"number\" name=\"sku\" hidden value=\"".$sku."\">";
			echo "</form></div>";
			}
		}
		if (isset($_POST["submit"])){
			if ($_POST["submit"] == "ANSWER"){
				$sql = "INSERT INTO `answers`(rank,txt,questionID,memberID)
				VALUES(0,'".test_input($_POST["answer"])."',".$_POST["questionID"].",".$user.")";
				$conn->exec($sql);
			} else if ($_POST["submit"] == "ASK"){
				$sql = "INSERT INTO `question`(rank,txt,sku,userID)
				VALUES(0,'".test_input($_POST["question"])."',".$sku.",".$user.")";
				$conn->exec($sql);
			}
		}
	} else {
		echo "not post";
	}
	$conn = null;
?>

</body>