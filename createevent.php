<?php
session_start();
if($_SESSION["userType"] != "member"){
	echo "This page is only for sellers.";
	die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>LuLa Shop</title>
	<?php include 'menu.php'; ?>
	<style>
	.error {color: #FF0000;}
	</style>
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
	catch(PDOException $e)
	{
		echo "Connection failed: " . $e->getMessage();
		die();
	}
	?>
</head>

<body>
<?php
$memberID = $_SESSION["userID"];
$userID = "NULL";
$startErr=$endErr=$categoryErr=$urlErr=$seasonErr="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["start"])){
		$startErr="***starting date & time is required";
	} else {
		$start = $_POST["start"];
		$startLocal = $start; //save local timezone for display
		var_dump($start); //TODO: Delete me
		//translate from local to UTC
		$sql = "SELECT `timezoneOffset` FROM `member` WHERE `memberID`=$memberID;";
		$pdo = $conn->query($sql);
		$result = $pdo->fetchColumn();
		$abbrev  = DateTimeZone::listAbbreviations();
		$timezoneName = $abbrev[$result];
		$timezoneName = $timezoneName[0]['timezone_id'];
		$start = new DateTime($start, new DateTimeZone($timezoneName));
		$start = $start->setTimezone(new DateTimeZone('UTC'));
		$start = $start->format('Y-m-d H:i:s');
		$start[10] = 'T';
		var_dump($start); //TODO: Delete me
	}
	if (empty($_POST["end"])){
		$endErr="***ending date & time is required";
	} else {
		$end = $_POST["end"];
		$endLocal = $end;
		//translate from local to UTC
		$end = new DateTime($end, new DateTimeZone($timezoneName));
		$end = $end->setTimezone(new DateTimeZone('UTC'));
		$end = $end->format('Y-m-d H:i:s');
		$end[10] = 'T';
		var_dump($end); //TODO: Delete me
	}
	if (empty($_POST["category"])){
		$categoryErr="***event category is required";
	} else {
		$category = $_POST["category"];
		if ($category == "season") {
			if (empty($_POST["season"])){
				$seasonErr="***keyword required for season event";
			} else {
				$season = test_input($_POST["season"]);
				$sql = "SELECT `sku` FROM `inventory` WHERE `pattern` LIKE '%$season%';";
				$pdo = $conn->query($sql);
				if ($pdo->fetch()==false){
					$seasonErr="The season keyword did not match to any inventory";
				}
			}
		} else {
			$season = "NULL";
		}
	}
	if (!empty($_POST["url"])) {
		$url = $_POST["url"];
		//validate url
		if (($url = filter_var($_POST["url"], FILTER_VALIDATE_URL)) == false) {
			$urlErr="***invalid url";
		}
	} else {
		$url = "NULL";
	}
	if (isset($_POST["private"])){
		$private = 1;
	} else {
		$private = 0;
	}
	if (isset($_POST["modify"])){
		$modify = true;
		$eventID = $_POST["eventID"];
	} else {
		$modify = false;
	}
	if (isset($_POST["addressID"])) {
		$addressID = $_POST["addressID"];
	} else {
		$addressID = "NULL";
	}
	// check for errors and prepare sql statement
	$arrErr = array($startErr,$endErr,$categoryErr,$urlErr,$seasonErr);
	$isErr = false;
	foreach ($arrErr as $error) {
		if (!empty($error)) {
			echo $error;
			$isErr = true;
		}
	}
	if (!$isErr && !$modify){
// create event
		$sql = "INSERT INTO `event`
		(
			";
		if($season != "NULL"){
		 $sql .= "season,";
		}
		if($url != "NULL"){
			$sql .= "url,";
		}
		$sql .= "
			start,
			end,
			category,
			private,
			addressID,
			userID,
			memberID
		)
		VALUES
		(";
		if($season != "NULL"){
		  $sql .= "'$season',";
		}
		if($url != "NULL"){
		  $sql .= "'$url',";
		}
		$sql .= "
			'$start',
			'$end',
			'$category',
			$private,
			$addressID,
			$userID,
			$memberID
		)";
echo "<br>".$sql; //TODO: Delete me
		try {
			$conn->exec($sql);
		}
		catch(PDOException $e) {
			echo "Creation of event failed: " . $e->getMessage();
		}
	} else if (!$isErr && $modify && isset($_POST['submit'])) {
		//modify existing event
		//get existing values
		$sql = "SELECT * FROM `event` WHERE eventID=$eventID";
		echo "<br>".$sql;
		try {
			$pdo = $conn->query($sql);
		} catch(PDOException $e){
			echo "Error finding event to modify: ".$e->getMessage();
			die();
		}
		$result = $pdo->fetch();
		$oldStart = $result['start'];
		$oldStart[10] = 'T';
		$oldEnd = $result['end'];
		$oldEnd[10] = 'T';
		$oldCategory = $result['category'];
		$oldSeason = $result['season'];
		if ($oldSeason == NULL){
		  $oldSeason = "NULL";
		}
		$oldPrivate = $result['private'];
		$oldUrl = $result['url'];
		if ($oldUrl == NULL){
			$oldUrl="NULL";
		}
		$oldAddressID = $result['addressID'];
		if ($oldAddressID == NULL){
			$oldAddressID="NULL";
		}
		//compare against new values and build UPDATE statement
		$sql = "UPDATE `event` SET ";
		$nochange = $sql; //save for reference
		if ($start != $oldStart) {
			$sql = $sql."`start`='$start'";
		}
		if ($end != $oldEnd) {
			if ($sql != $nochange){
				$sql = $sql.", ";
			}
			$sql = $sql."`end`='$end'";
		}
		if ($category != $oldCategory){
			if ($sql != $nochange){
				$sql = $sql.", ";
			}
			$sql = $sql."`category`='$category'";
		}
		if ($season != $oldSeason){
			if ($sql != $nochange){
				$sql = $sql.", ";
			}
			if ($season == "NULL"){
  			$sql = $sql."`season`=$season";
			} else {
			  $sql .= "`season`='$season'";
			}
		}
		if ($private != $oldPrivate){
			if ($sql != $nochange){
				$sql = $sql.", ";
			}
			$sql = $sql."`private`=$private";
		}
		if ($url != $oldUrl){
			if ($sql != $nochange){
				$sql = $sql.", ";
			}
			if ($url == "NULL"){
			  $sql .="`url`=$url";
			} else {
				$sql .= "`url`='$url'";
			}
		}
		if ($addressID != $oldAddressID){
			if ($sql != $nochange){
				$sql = $sql.", ";
			}
			$sql = $sql."`addressID`=$addressID";
		}
		if ($sql == $nochange){
			echo "<br>There was no change. The event was not modified.";
		} else {
			$sql .= " WHERE `eventID`=$eventID;";
			echo "<br>".$sql; //TODO: Delete me
			$conn->exec($sql);
		}
	}
} else {
	$url = "NULL";
	$season = "NULL";
	$modify = false;
}
//sanatize input
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<div class="w3-panel w3-blue w3-round-xlarge">
  <p><?php
  if($modify){
		echo "Modify ";
	} else {
		echo "Create ";
	}
	?>Event</p>
</div>
<div class="w3-container w3-card">
	<form class="w3-container" method="post" action="createevent.php">
		<?php
			//maintain state
			if($modify){
				echo "<input type=\"number\" name=\"modify\"hidden value=\"1\">";
				echo "<input type=\"number\" name=\"eventID\"hidden value=\"".$eventID."\">";
			}
		?>
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<select class="w3-select" name="category" required>
				<?php
					if($_SERVER["REQUEST_METHOD"] == "POST"){
						echo "<option value=\"".$category."\" selected>".$category."</option>";
					} else {
						echo "<option value=\"\" disabled selected>Choose type of event</option>";
					}
				?>
					<option value="openhr">Open Hours</option>
					<option value="party">Party</option>
					<option value="outofoffice">Out of Office</option>
					<option value="fair">Party - Multi Consultant</option>
					<option value="season">Season</option>
				</select>
				<label class="w3-label w3-validate">Event category</label><?php echo $categoryErr;?>
			</div>
			<div class="w3-quarter">
				<input class="w3-input w3-border" type="text" name="season" placeholder="season"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST"){
					if($season != "NULL"){echo "value=\"$season\"";}
				}
				?>  >
				<label class="w3-label w3-validate">Season keyword to match Item Pattern</label><span class="error"><?php echo $seasonErr;?></span>
			</div>
			<div class="w3-quarter">
				<input type="datetime-local" name="start" required
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST"){
					echo "value=\"$startLocal\"";
				}
				?>  >
				<label class="w3-label w3-validate">Start Date-Time</label><span class="error"><?php echo $startErr;?></span>
			</div>
				<input type="datetime-local" name="end" required
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST"){
					echo "value=\"$endLocal\"";
				}
				?>  >
				<label class="w3-label w3-validate">End Date-Time</label><span class="error"><?php echo $endErr;?></span>
			</div>
		</div>

		<div class="w3-row-padding">
			<div class="w3-rest">
				<input class="w3-input w3-border" type="url" name="url" placeholder="link to online event or post about event"
				<?php
					if ($_SERVER["REQUEST_METHOD"] == "POST"){
						if($url != "NULL"){echo "value=\"".$url."\"";}
					}
				 ?> >
				<label class="w3-label w3-validate">URL</label>
			</div>
		</div>
		<div class="w3-row-padding">
			<div class="w3-rest">
				<select class="w3-select" name="addressID">
					<option value="NULL">This is an online event or address is TBD</option>
					<?php
						$sql = "SELECT * FROM `address` WHERE `ownerID`='m_".$memberID."';";
						echo "<br>".$sql;
						$pdo = $conn->query($sql);
						while ($result = $pdo->fetch()) {
							echo "<option value=\"".$result['addressID']."\"";
							if ($_SERVER["REQUEST_METHOD"] == "POST"){
								if ($result['addressID'] == $addressID){
									echo "selected";
								}
							}
							echo ">";
							echo $result['street1']." ".$result['street2']." ".$result['appt']." ".$result['city']." ".$result['state']." ".$result['zip'];
							echo "</option>";
						}
					?>
				</select>
				<label class="w3-label w3-validate">address</label>
			</div>
		</div>
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<input class="w3-check" type="checkbox" name="private" value="private"
				<?php
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['private'])) {
							if ($_POST['private']==true){
								echo "checked";
							}
						}
					}
				?>>
				<label class="w3-label">Private event</label>
			</div>
		</div>

		<br><br>
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<input class="w3-button" type="submit" name="submit" value=
				<?php
				if ($modify){
				  echo "\"Modify\"";
				} else {
				  echo "\"Create\"";
				}
				?> >
				<input class="w3-button" type="reset">
			</div>
		</div>
	</form>
</div>

</body>
<!-- close DB connection -->
<?php $conn = "NULL";?>
<footer>
<?php include 'foot.php'; ?>
</footer>
</html>