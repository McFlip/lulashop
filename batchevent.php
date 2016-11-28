<?php
//Run this daily after midnight using chron
//Processes out of office and seasonal events
//connect to DB
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
$today = date_create(null, new DateTimeZone('UTC'));
//seasonal
//Check if a season starts today and make matching patterns visible
$sql = "SELECT `event`.`season`, `event`.`memberID`
        FROM `event`,`inventory`
        WHERE `event`.`memberID`=`inventory`.`memberID`
        AND `event`.`category`='season'
				AND `event`.`start` LIKE '".date_format($today, 'Y-m-d')."%'
				AND `inventory`.`pattern` LIKE CONCAT('%',`event`.`season`,'%')";
try {
	$pdo = $conn->query($sql);
	$seasonal = $pdo->fetch();
} catch(PDOException $e) {
	echo "find seasonal event statement failed: " . $e->getMessage();
	die();
}
if ($seasonal){
  $sql = "UPDATE `inventory`
				SET `visible`=1
				WHERE `inventory`.`pattern` LIKE '%".$seasonal['season']."%'
				AND `inventory`.`memberID`=".$seasonal['memberID'].";";
	try {
		$conn->exec($sql);
	} catch(PDOException $e) {
		echo "season make visible statement failed: " . $e->getMessage();
		die();
	}
}
echo "season make visible statement succeded\n";
//Check if a season ends today and hide matching patterns
$sql = "SELECT `event`.`season`, `event`.`memberID`
        FROM `event`,`inventory`
        WHERE `event`.`memberID`=`inventory`.`memberID`
        AND `event`.`category`='season'
				AND `event`.`end` LIKE '".date_format($today, 'Y-m-d')."%'
				AND `inventory`.`pattern` LIKE CONCAT('%',`event`.`season`,'%')";
try {
	$pdo = $conn->query($sql);
	$seasonal = $pdo->fetch();
} catch(PDOException $e) {
	echo "find seasonal event statement failed: " . $e->getMessage();
	die();
}
if ($seasonal){
  $sql = "UPDATE `inventory`
				SET `visible`=0
				WHERE `inventory`.`pattern` LIKE '%".$seasonal['season']."%'
				AND `inventory`.`memberID`=".$seasonal['memberID'].";";
	try {
		$conn->exec($sql);
	} catch(PDOException $e) {
		echo "season make visible statement failed: " . $e->getMessage();
		die();
	}
}
echo "season hide statement succeded\n";
$conn = null;
?>
