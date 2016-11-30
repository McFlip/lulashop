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
$sql = "SELECT `sku`
        FROM `event`,`inventory`
        WHERE `event`.`memberID`=`inventory`.`memberID`
        AND `event`.`category`='season'
				AND `event`.`start` LIKE CONCAT(CURRENT_DATE,'%')
				AND `inventory`.`pattern` LIKE CONCAT('%',`event`.`season`,'%')";
try {
	$pdo = $conn->query($sql);
} catch(PDOException $e) {
	echo "find seasonal event statement failed: " . $e->getMessage();
	die();
}
// TODO: change to prepared statement
while ($seasonal = $pdo->fetchColumn()){
  $sql = "
  UPDATE `inventory`
	SET `visible`=1
	WHERE `sku`=$seasonal";
	try {
		$conn->exec($sql);
	} catch(PDOException $e) {
		echo "season make visible statement failed: " . $e->getMessage();
		die();
	}
}
echo "season make visible statement succeded\n";
//Check if a season ends today and hide matching patterns
$sql = "SELECT `sku`
        FROM `event`,`inventory`
        WHERE `event`.`memberID`=`inventory`.`memberID`
        AND `event`.`category`='season'
				AND `event`.`end` LIKE CONCAT(CURRENT_DATE,'%')
				AND `inventory`.`pattern` LIKE CONCAT('%',`event`.`season`,'%')";
try {
	$pdo = $conn->query($sql);

} catch(PDOException $e) {
	echo "find seasonal event statement failed: " . $e->getMessage();
	die();
}
while ($seasonal = $pdo->fetchColumn()){
  $sql = "
  UPDATE `inventory`
	SET `visible`=0
	WHERE `sku`=$seasonal";
	try {
		$conn->exec($sql);
	} catch(PDOException $e) {
		echo "season make visible statement failed: " . $e->getMessage();
		die();
	}
}
echo "season hide statement succeded\n";
// Check if an out of office event starts today and hide all inventory
$sql =
 "SELECT `sku`
	FROM `inventory`,`event`
	WHERE `event`.`start` LIKE CONCAT(CURRENT_DATE,'%')
	AND `event`.`category`='outofoffice'
	AND `inventory`.`memberID`=`event`.`memberID`";
try {
	$pdo = $conn->query($sql);

} catch(PDOException $e) {
	echo "find seasonal event statement failed: " . $e->getMessage();
	die();
}
while($outofoffice = $pdo->fetchColumn()){
	$sql =
 "UPDATE `inventory`
  SET `visible`=0
  WHERE `sku`=$outofoffice";
	try {
		$conn->exec($sql);
	} catch(PDOException $e) {
		echo "out of office hide statement failed: " . $e->getMessage();
		die();
	}
}
echo "out of office hide statement succeded\n";
// Check if an out of office event ends today and make visible all inventory that is not still hidden by a season event
$sql ="SELECT * FROM `outofoffice_show`";
try {
	$pdo = $conn->query($sql);

} catch(PDOException $e) {
	echo "find out of office statement failed: " . $e->getMessage();
	die();
}
while($outofoffice = $pdo->fetchColumn()){
	$sql =
	"UPDATE `inventory`
	SET `visible`=1
	WHERE `sku`=$outofoffice";
	try {
		$conn->exec($sql);
	} catch(PDOException $e) {
		echo "out of office make visible statement failed: " . $e->getMessage();
		die();
	}
}
echo "out of office make visible statement succeded\n";

$conn = null;
?>
