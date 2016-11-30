<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>LuLa Shop</title>
	<?php include 'menu.php'; ?>
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
	echo "<p>All times are listed according to your local timezone in your profile. CURRENT LOCAL DATE & TIME:</p>";
	$userType = $_SESSION["userType"];
	$user = $_SESSION["userID"];
	//show followed events if a user
	if ($userType == "user") {
		echo "<div class=\"w3-container\">";
		echo "<iframe name=\"listevent\" height=\"200px\" width=\"100%\" src=\"listevent.php\"></iframe>";
		echo "</div>";
	}
	//timezone offset based off user timeznone abbrev in database
	$date = new DateTime('now', new DateTimeZone('UTC'));
	$sql = "SELECT `timezoneOffset` FROM `$userType` WHERE ";
	if($userType == "user"){
	  $sql = $sql."	userID='$user'";
	} else {
		$sql = $sql."	memberID='$user'";
	}
	$pdo = $conn->query($sql);
	$result = $pdo->fetchColumn();
	$abbrev  = DateTimeZone::listAbbreviations();
	$timezoneName = $abbrev[$result];
	$timezoneName = $timezoneName[0]['timezone_id'];
	$date->setTimezone(new DateTimeZone($timezoneName));
	echo $date->format('Y-m-d H:i:sP') . "<br>";
?>
<form class="w3-card" method="post" action="calendar.php">
	<div class="w3-row-padding">
		<div class="w3-half">
			<?php
			if ($userType == "user"){
			echo "<select class=\"w3-select\" name=\"memberID\" required>";
			$sql = "SELECT `followMember`.`memberID`,`firstName`,`lastName` FROM `followMember`,`member`
			  WHERE `followMember`.`userID`='$user' AND `followMember`.`memberID`=`member`.`memberID`;";
			$pdo = $conn->query($sql);
			while ($result = $pdo->fetch()){
				echo "<option value=\"";
				echo $result['memberID'];
				echo "\">";
				echo $result['firstName']." ";
				echo $result['lastName'];
				echo "</option>";
			}
			echo "</select>";
			echo "<label class=\"w3-validate\">Select a seller that you are following</label>";
			}
			?>
		</div>
		<div class="w3-quarter">
			<input type="month" name="month"
			<?php
			if($_SERVER["REQUEST_METHOD"] == "POST"){
			  echo "value=\"".$_POST['month']."\"";
			}
			?>
			required>
			<label class="w3-validate">Choose Month</label>
		</div>
		<div class="w3-quarter">
			<select class="w3-select"  name="category" required>
				<option value="all" selected>All</option>
				<option value="openhr">Open Hours</option>
				<option value="party">Party</option>
				<option value="outofoffice">Out of Office</option>
				<option value="fair">Party - Multi Consultant</option>
				<option value="season">Season</option>
			</select>
			<label class="w3-validate">Filter by type</label>
		</div>
	</div>
	<div class="w3-row-padding">
		<input type="submit" name="showCalender" value="Show calendar">
	</div>
</form>
<!--Query Time Zone info to adjust dates. Record Dates in GMT. -->
<!--calendar days heading - hide on small screens-->
<div class="w3-row w3-container w3-hide-small">
	<div class="w3-col m1 w3-container w3-blue">
		<span class="w3-tag">SUN</span>
	</div>
	<div class="w3-col m1 w3-container w3-cyan">
		<span class="w3-tag">MON</span>
	</div>
	<div class="w3-col m1 w3-container w3-blue">
		<span class="w3-tag">TUE</span>
	</div>
	<div class="w3-col m1 w3-container w3-cyan">
		<span class="w3-tag">WED</span>
	</div>
	<div class="w3-col m1 w3-container w3-blue">
		<span class="w3-tag">THU</span>
	</div>
	<div class="w3-col m1 w3-container w3-cyan">
		<span class="w3-tag">FRI</span>
	</div>
	<div class="w3-col m1 w3-container w3-blue">
		<span class="w3-tag">SAT</span>
	</div>
</div>
<!--begin guts of calendar-->
<div class="w3-row w3-container w3-padding-8">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$category = $_POST['category'];
// 	who's calendar to display?
	if ($userType == "member") {
		$calOwner = $user;
	} else {
		$calOwner = $_POST['memberID'];
	}
//first is set to first day of selected month
	$first=date_create($_POST['month']."-1");
// 	set up empty slots before the 1st
	for($i=0; $i < date_format($first, "w"); $i++)
	{
		echo "<div class='w3-col m1 w3-container'>";
		echo "<span class='w3-tag'>  </span>";
		echo "</div>";
	}
	$daysinmonth=cal_days_in_month(CAL_GREGORIAN, date_format($first,"m"), date_format($first,"Y"));
	for($i=0; $i < $daysinmonth; $i++){
		//start a new row on sunday
		if(date_format($first, "w")==0){
			echo "</div>";
			echo "<div class='w3-row w3-container w3-padding-8'>";
		}
		//label the day
		echo "<div class='w3-col m1 w3-container'>";
		echo "<span class='w3-tag'>";
		echo date_format($first, "d");
		echo " </span>";
		//for each event on this day echo out.
		//for a seller show their own calendar with all event types
		$sql = "SELECT `eventID`, `category`
			FROM `event`
			WHERE `start` LIKE '".date_format($first, 'Y-m-d')."%'
			AND `memberID` = '".$calOwner."'";
		if ($category != "all"){
		  $sql .= " AND `category`='".$category."'";
		}
		$sql .= " ORDER BY `start` ASC;";
		$pdo = $conn->query($sql);
		while ($result = $pdo->fetch()) {
			echo "<form class=\"w3-container\" method=\"post\" action=\"showevent.php\" target=\"showevent\">";
			echo "<input type=\"submit\" name=\"submit\" ";
			echo "value=\"";
			echo $result["category"];
			echo "\">";
			echo "<input type=\"number\" name=\"eventID\"hidden value=\"";
			echo $result["eventID"];
			echo "\">";
			echo "</form>";
		}
		if ($category == "all" || $category == "fair") {
		  $sql = "SELECT `event`.`eventID`,`category`
		    FROM `event`,`fair`
		    WHERE `start` LIKE '".date_format($first, 'Y-m-d')."%'
			  AND `fair`.`memberID` = '".$calOwner."'
			  AND `fair`.`eventID` = `event`.`eventID`";
			$pdo = $conn->query($sql);
			while ($result = $pdo->fetch()) {
				echo "<form class=\"w3-container\" method=\"post\" action=\"showevent.php\" target=\"showevent\">";
				echo "<input type=\"submit\" name=\"submit\" ";
				echo "value=\"";
				echo $result["category"];
				echo "\">";
				echo "<input type=\"number\" name=\"eventID\"hidden value=\"";
				echo $result["eventID"];
				echo "\">";
				echo "</form>";
			}
		}
		echo "</div>";
		date_add($first, date_interval_create_from_date_string("1 day"));
	}
}
?>
</div>
<div class="w3-container">
	<iframe name="showevent" height="400px" width="100%" src="showevent.php"></iframe>
</div>
</body>

<footer>
<?php include 'foot.php'; ?>
</footer>
</html>