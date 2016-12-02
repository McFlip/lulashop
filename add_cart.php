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
		echo "gate 0";
		$userType = $_SESSION["userType"];
		$user = $_SESSION["userID"];
		$sku = $_POST["sku"];
		$sql = "SELECT * FROM `inventory` WHERE `sku` = ".$sku;

		$conn->beginTransaction();
		$pdo = $conn->query($sql);
		$result = $pdo->fetch();
		if (isset($_POST["submit"])) {
			echo "gate 1";
			if ($_POST["submit"]=="ADD ITEM") {
				echo "gate 2";
				if($result['quantity'] > 0){
					echo "gate 3";
					$conn->exec("UPDATE `inventory`
					SET `quantity` = $result[quantity]-1
					WHERE sku = $sku;");
					echo "INSERT INTO `cart` (quantity, sku, userID) VALUES(1, '$sku', '$user')";

					$conn->exec("INSERT INTO `cart` (quantity, sku, userID) VALUES(1, '$sku', '$user')");

					$conn->commit();
				}
			}

			if ($_POST["submit"]=="REMOVE ITEM") {
				$conn->exec("UPDATE `inventory`
				SET `quantity` = $result[quantity] + 1
				WHERE sku = $sku;");

				$conn->exec("DELETE FROM cart WHERE sku = $sku;");
				$conn->commit();
			}

		}
		echo "<h3>Items in Cart</h3>";
		$sql = "SELECT `inventory`.`sku`,`inventory`.`category`,`member`.`firstName`,`member`.`lastName`
						FROM `inventory`,`cart`,`member`
						WHERE `cart`.`userID` =".$user."
						AND `inventory`.`sku`=`cart`.`sku`
						AND `member`.`memberID` = `inventory`.`memberID`";
		echo "<br>".$sql."<br>";
		$pdo = $conn->query($sql);
		echo "<table class=\"w3-striped\">";
		echo "<tr><th>Style</th><th>Seller</th><th>Name</th><th>        </th><th>      </th><tr>";
		while($cart = $pdo->fetch()){
			$sql2 = "SELECT `picURL` FROM `picture` WHERE `sku`=".$cart["sku"];
			$pdo2 = $conn->query($sql2);
			echo "<tr>";
			while($pic = $pdo2->fetch()){
				echo "<td><div class=\"w3-card-8\"><img src=\"".$pic["picURL"]."\" width=\"300\" height=\"300\"></div></td>";
			}
			echo "</tr>";
			echo "<tr><td>".$cart["category"]."</td><td>".$cart["firstName"]."</td><td>".$cart["lastName"]."</td>";
			echo "<td><form method=\"post\" action=\"add_cart.php\" target=\"_self\">";
			echo "<input type=\"submit\" value=\"REMOVE ITEM\" name=\"submit\">";
			echo "<input type=\"number\" name=\"sku\" hidden value=\"".$cart["sku"]."\">";
			echo "</td></form></tr>";
		}
		echo "</table>";
	}
	$conn = null;
?>
</body>