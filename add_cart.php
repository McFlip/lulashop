<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<div class="w3-panel w3-blue">
		<h3>Cart</h3>
	</div>
	<?php
	
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
		$sku = $_POST["sku"];
		$sql = "SELECT * FROM `inventory` WHERE `sku` = ".$sku;

		$conn->beginTransaction();
		$pdo = $conn->query($sql);
		$result = $pdo->fetch();
		if (isset($_POST["submit"])) {
			if ($_POST["submit"]=="ADD ITEM") {
				if($result['quantity'] > 0){

					$conn->exec("UPDATE 'inventory'
					SET 'quantity' = $result[quantity]-1 
					WHERE sku = $sku;");

					$conn->exec("INSERT INTO cart ('1', 'sku = $sku', 'user = $userID')");

					$conn->commit();
				}
			}

			if ($_POST["submit"]=="REMOVE ITEM") {
				$conn->exec("UPDATE 'inventory'
				SET 'quantity' = $result[quantity] + 1
				WHERE sku = $sku;");

				$conn->exec("DELETE FROM cart WHERE sku = $sku;");
				$conn->commit();
			}
			
		}
	}
	$conn = null;
?>
</body>