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
<p> This is where you check on orders that you have purchased </p>
<div class="w3-container">
	<p> The following orders have not shipped yet: <p>
	<table class="w3-table w3-striped">
		<tr>
			<th>Invoice</th>
			<th>Date</th>
		</tr>
<?php
	$userType = $_SESSION["userType"];
	$user = $_SESSION["userID"];
	$sql = "SELECT `invoiceNumber`, `_date` FROM `invoice` WHERE userID='$user'";
	$pdo = $conn->query($sql);
	while ($result = $pdo->fetch()) {
		echo "<tr><td>".$result["invoiceNumber"]."</td><td>".$result["_date"]."</td></tr>";
	}
?>
</table>
</div>

</body>

<footer>
<?php include 'foot.php'; ?>
</footer>
</html>