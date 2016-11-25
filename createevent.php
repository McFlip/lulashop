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
	if (isset($_POST["btnclear"])){
		foreach ($_POST as $x => $y) {
			$_POST["$x"] = "NULL";
		}
	}
	if (empty($_POST["start"])){
		$startErr="***starting date & time is required";
	} else {
		$start = $_POST["start"];
		var_dump($start);
	}
	if (empty($_POST["end"])){
		$endErr="***ending date & time is required";
	} else {
		$end = $_POST["end"];
		var_dump($end);
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
			start,
			end,
			category,
			season,
			private,
			url,
			addressID,
			userID,
			memberID
		)
		VALUES
		(
			'$start',
			'$end',
			'$category',
			$season,
			$private,
			$url,
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
			die(); //enforce ref integrity - don't upload pics if you cant attach to SKU
		}
	} else if (!$isErr && $modify) {
		//modify existing event

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
					echo "value=\"$start\"";
				}
				?>  >
				<label class="w3-label w3-validate">Start Date-Time</label><span class="error"><?php echo $startErr;?></span>
			</div>
				<input type="datetime-local" name="end" required
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST"){
					echo "value=\"$end\"";
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
							echo "checked";
						}
					}
				?>>
				<label class="w3-label">Private event</label>
			</div>
		</div>

		<br><br>
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<input class="w3-button" type="submit" name="submit" value="Create">
				<input class="w3-button" type="reset">
				<input class="w3-button" type="submit" name="btnclear" value="Clear Out the Form">
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