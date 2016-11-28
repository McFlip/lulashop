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
$today = date_create();
//seasonal
$sql = "UPDATE `inventory`
				SET `visible`=1
				WHERE `sku`=(
					SELECT `sku`
					FROM `inventory`,`member`,`event`
					WHERE `inventory`.`memberID`=`member`.`memberID`
					  AND `member`.`memberID`=`event`.`memberID`
					  AND `event`.`category`='season'
						AND `event`.`start` LIKE '".date_format($today, 'Y-m-d')."%')";
try {
	$conn->exec($sql);
} catch(PDOException $e) {
	echo "season statement failed: " . $e->getMessage();
	die();
}
echo "season statement succeded\n";
?>
