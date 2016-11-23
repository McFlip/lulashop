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
$lengthErr=$dateErr=$categoryErr=$urlErr="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["btnclear"])){
		foreach ($_POST as $x => $y) {
			$_POST["$x"] = null;
		}
	}
	if (empty($_POST["length"])){
		$lengthErr="***length is required";
		$length = 60;
	} else {
		if (($length = filter_var($_POST["length"], FILTER_VALIDATE_INT)) == false) {
			$lengthErr="***length must be an integer";
			$length = 60;
		}
	}
	if ($length < 1) {
		$lengthErr="***length must be at least 1 minute";
	}
	if (empty($_POST["date"])){
		$dateErr="***date is required";
	} else {
		$date = $_POST["date"];
		var_dump($date);
	}
	//TODO: consolidate date and time
// 	if (empty($_POST["time"])){
// 		$timeErr="***time is required";
// 	} else {
// 		$time = $_POST["time"];
// 	}
	if (empty($_POST["category"])){
		$categoryErr="***event category is required";
	} else {
		$category = $_POST["category"];
	}
	if (!empty($_POST["url"])) {
		$url = $_POST["url"];
		//validate url
		if (($url = filter_var($_POST["url"], FILTER_VALIDATE_URL)) == false) {
			$urlErr="***invalid url";
		}
	} else {
		$url = 'null';
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
		$addressID = 'null';
	}
	$memberID = $_SESSION["userID"];
	$userID = 'null';
	// check for errors and prepare sql statement
	$arrErr = array($lengthErr,$dateErr,$categoryErr,$urlErr);
	$isErr = false;
	foreach ($arrErr as $error) {
		if (!empty($error)) {
			echo $error;
			$isErr = true;
		}
	}
	if (!$isErr && !$modify){
// create event
		$stmt = $conn->prepare("INSERT INTO `event`
		(
		  length,
		  _date,
			category,
			private,
			url,
			addressID,
			userID,
			memberID
		)
		VALUES
		(
		  :length,
			:dateTime,
			:category,
			:private,
			:url,
			:addressID,
			:userID,
			:memberID
		)");
		$stmt->bindParam(':length', $length);
		$stmt->bindParam(':dateTime', $date);
		$stmt->bindParam(':category', $category);
		$stmt->bindParam(':private', $private);
		$stmt->bindParam(':url', $url);
		$stmt->bindParam(':addressID', $addressID);
		$stmt->bindParam(':userID', $userID);
		$stmt->bindParam(':memberID', $memberID);
// 		$stmt->execute();
		$sql = "INSERT INTO `event`
		(
			length,
			_date,
			category,
			private,
			url,
			addressID,
			userID,
			memberID
		)
		VALUES
		(
			'$length',
			'$date',
			'$category',
			'$private',
			'$url',
			'$addressID',
			'$userID',
			'$memberID'
		)";
echo "WTF OVER???????";
echo "<br>".$sql;
		try {
			$conn->exec($sql);
		}
		catch(PDOException $e) {
			echo "Creation of event failed: " . $e->getMessage();
			die(); //enforce ref integrity - don't upload pics if you cant attach to SKU
		}
	}
} else {
	$length = 60;
	$url = null;
	$modify = false;
}
//TODO:remove this function if not needed
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
				<input class="w3-input w3-border" type="number" name="length" min="1" value="<?php echo "$length"; ?>" required>
				<label class="w3-label w3-validate">length in minutes (enter numbers only)</label><span class="error"><?php echo $lengthErr;?></span>
			</div>
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
				</select>
				<label class="w3-label w3-validate">Event category</label><?php echo $categoryErr;?>
			</div>
			<div class="w3-rest">
				<input type="date" name="date" required>
				<label class="w3-label w3-validate">Date</label><span class="error"><?php echo $dateErr;?></span>
			</div>
		</div>

		<div class="w3-row-padding">
			<div class="w3-rest">
				<input class="w3-input w3-border" type="url" name="url" placeholder="link to online event or post about event" <?php if($url != null){echo "value=\"".$url."\"";} ?> >
				<label class="w3-label w3-validate">URL</label>
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
<?php $conn = null;?>
<footer>
<?php include 'foot.php'; ?>
</footer>
</html>