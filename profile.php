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
<p> This is where you check on orders that you have purchased, update your addresses, and manage which sellers you follow.</p>
<div class="w3-container">
  <h2>My Addresses</h2>
  <form method="post" action="address.php">
  <button class="w3-btn w3-green w3-large" type="submit" value="Add Address">Add Address</button>
  <input hidden name="mode" value="add">
  </form>
  <br>
  <form method="post" action="address.php">
  <button class="w3-btn w3-green w3-large" type="submit" value="Add Address">Update Address</button>
  <input hidden name="mode" value="kevinWTF">
  </form>
  <br>
  <form method="post" action="address.php">
  <button class="w3-btn w3-green w3-large" type="submit" value="Add Address">Remove Address</button>
  <input hidden name="mode" value="remove">
  </form>
</div>
<div class="w3-container">
  <h2>Manage Who I Follow</h2>
  <form method="post" action="follow.php">
  <button class="w3-btn w3-green w3-large" type="submit" value="Add Address">Manage</button>
  </form>
</div>
<div class="w3-container">
	<p> The following orders have not shipped yet: <p>
	<table class="w3-table w3-striped">
		<tr>
			<th>Invoice</th>
			<th>Date</th>
		</tr>
    <?php
      if (isset($_SESSION["userID"])) {
        $userType = $_SESSION["userType"];
        $user = $_SESSION["userID"];
        $sql = "SELECT `invoiceNumber`, `_date` FROM `invoice` WHERE `shipped`=0 AND ";
        if ($userType == "user"){
          $sql .="userID='$user'";
        } else {
          $sql .="memberID='$user'";
        }
        $pdo = $conn->query($sql);
        while ($result = $pdo->fetch()) {
          echo "<tr><td><form method=\"post\" action=\"showinvoice.php\" target=\"invoice\">";
          echo "<input type=\"submit\" style=\"font-size:24px\" onclick=\"showInvoice()\" value=\"".$result["invoiceNumber"]."\">";
          echo "<input type='number' hidden name='invoiceNumber' value='".$result["invoiceNumber"]."'></form></td>";
          echo "<td>".$result["_date"]."</td></tr>";
        }
      }
    ?>
  </table>
</div>
<div class="w3-container">
	<p> The following orders have shipped: <p>
	<table class="w3-table w3-striped">
		<tr>
			<th>Invoice</th>
			<th>Date</th>
			<th>Tracking Number</th>
		</tr>
    <?php
      if (isset($_SESSION["userID"])) {
        $userType = $_SESSION["userType"];
        $user = $_SESSION["userID"];
        $sql = "SELECT `invoiceNumber`,`_date`,`tracking` FROM `invoice` WHERE `shipped`=1 AND ";
        if ($userType == "user"){
          $sql .="userID='$user'";
        } else {
          $sql .="memberID='$user'";
        }
        $pdo = $conn->query($sql);
        while ($result = $pdo->fetch()) {
          echo "<tr><td><form method=\"post\" action=\"showinvoice.php\" target=\"invoice\">";
          echo "<input type=\"submit\" style=\"font-size:24px\" onclick=\"showInvoice()\" value=\"".$result["invoiceNumber"]."\">";
          echo "<input type='number' hidden name='invoiceNumber' value='".$result["invoiceNumber"]."'></form></td>";
          echo "<td>".$result["_date"]."</td>";
          echo "<td><a href='".$result["tracking"]."'>".$result["tracking"]."</td></tr>";
        }
      }
    ?>
  </table>
</div>
<div id="invoice" class="w3-modal">
	<div class="w3-modal-content">
		<div class="w3-container">
			<span onclick="document.getElementById('invoice').style.display='none'" class="w3-closebtn">&times;</span>
			<iframe name="invoice" height="400px" width="100%" src="showinvoice.php">invoice</iframe>
		</div>
	</div>
</div>
<script>
function showInvoice(){
  document.getElementById('invoice').style.display='block';
}
</script>
</body>
<!-- close DB connection -->
<?php $conn = null;?>
<footer>
<?php include 'foot.php'; ?>
</footer>
</html>