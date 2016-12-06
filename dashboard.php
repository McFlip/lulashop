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
  $userID = $_SESSION['userID'];
  ?>
</head>

<body>
<p> This is where you view the consultant dashboard to do consultant things :) </p>
<div class="w3-container">
  <h2>Create an inventory item</h2>
  <a href="createinventory.php"><button class="w3-btn w3-green w3-large">Create Inventory</button></a>
</div>
<div class="w3-container">
  <h2>Create an event</h2>
  <a href="createevent.php"><button class="w3-btn w3-green w3-large">Create Event</button></a>
</div>
<div class="w3-container">
  <h2>Loyal Customers</h2>
  <h6>Customers who have higher than average number of invoices</h6>
  <table class="w3-striped">
    <tr>
      <th>Customer</th><th>Name</th><th>email</th>
    </tr>
      <?php
      $sql = "SELECT *
            FROM `loyalty`
            WHERE `numInvoices` > (SELECT AVG(`numInvoices`) FROM `loyalty`)";
      $pdo = $conn->query($sql);
      while ($loyal = $pdo->fetch()){
        echo "<tr><td>".$loyal['firstName']."</td><td>".$loyal['lastName']."</td><td>".$loyal['email']."</td></tr>";
      }
      ?>
  </table>
</div>
<div class="w3-container">
  <h2>Stale Inventory</h2>
  <h6>Inventory that is more than two months old</h6>
  <table class="w3-striped">
    <tr>
      <th>Date added</th><th>SKU</th><th>Style</th><th>size</th>
    </tr>
      <?php
      $date = new DateTime(null, new DateTimeZone('UTC'));
      date_sub($date,date_interval_create_from_date_string("2 months"));
      $sql = "SELECT `_date`,`sku`,`category`,`size`
            FROM `inventory`
            WHERE `memberID` = $userID
            AND `quantity` > 0
            AND UNIX_TIMESTAMP(`_date`) <".date_timestamp_get($date);
      try{
        $pdo = $conn->query($sql);
      } catch(PDOException $e) {
        echo "stale failed: " . $e->getMessage();
        die();
      }
      while ($stale = $pdo->fetch()){
        echo "<tr><td>".$stale['_date']."</td><td>".$stale['sku']."</td><td>".$stale['category']."</td><td>".$stale['size']."</tr>";
      }
      ?>
  </table>
</div>
</body>

<footer>
<?php include 'foot.php'; ?>
</footer>
</html>