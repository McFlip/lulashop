<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
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
if (isset($_SESSION["userID"])) {
  $userType = $_SESSION["userType"];
  $user = $_SESSION["userID"];
  $invoiceNumber=$_POST["invoiceNumber"];
  if(isset($_POST['submit'])){
    if ($_POST['submit'] == "Update Tracking #"){
      $sql = "UPDATE `invoice`
              SET `shipped`=1, `tracking`='".$_POST["tracking"]."'
              WHERE `invoiceNumber`=$invoiceNumber";
      $conn->exec($sql);
    } else if ($_POST['submit'] == "Add Givaway"){
      $sku = $_POST['sku'];
      $sql = "UPDATE `inventory`
              SET `quantity`=`quantity`-1
              WHERE `sku`=$sku AND `quantity`>0";
      $conn->exec($sql);
      $sql = "INSERT INTO `invoiceItem`(quantity,free,invoiceNumber,sku)
              VALUES (1,1,$invoiceNumber,$sku)";
      $conn->exec($sql);
    } else if ($_POST['submit'] == "Mark Paid"){
      $sql = "UPDATE `invoice`
              SET `paid`=1
              WHERE `invoiceNumber`=$invoiceNumber";
      $conn->exec($sql);
    }
  }
  echo "Invoice Number: $invoiceNumber <br>";
  $sql = "SELECT `firstName`,`lastName`
          FROM `user`,`invoice`
          WHERE `user`.`userID`=`invoice`.`userID`
          AND `invoiceNumber`=$invoiceNumber";
  $pdo = $conn->query($sql);
  $customer = $pdo->fetch();
  echo "Customer: ".$customer['firstName']." ".$customer['lastName']."<br>";
  $sql = "SELECT * FROM `invoice` WHERE `invoiceNumber`=$invoiceNumber";
  $pdo = $conn->query($sql);
  $invoice = $pdo->fetch();
  echo "Date: ";
  $date = $invoice['_date'];
  $date = new DateTime($date, new DateTimeZone('UTC'));
  $sql = "SELECT `timezoneOffset` FROM `$userType` WHERE ";
  if($userType == "user"){
    $sql = $sql."	userID='$user';";
  } else {
    $sql = $sql."	memberID='$user';";
  }
  $pdo = $conn->query($sql);
  $userTZ = $pdo->fetchColumn();
  $abbrev  = DateTimeZone::listAbbreviations();
  $timezoneName = $abbrev[$userTZ];
  $timezoneName = $timezoneName[0]['timezone_id'];
  $date->setTimezone(new DateTimeZone($timezoneName));
  $date = $date->format('Y-m-d H:i:s');
  echo $date."<br>";
  echo "Billing Address:<br>";
  echo $invoice['bill_street1']."<br>";
  echo $invoice['bill_street2']."<br>";
  echo $invoice['bill_city']."<br>";
  echo $invoice['bill_state']."<br>";
  echo $invoice['bill_zip']."<br>";
  echo "Shipping Address:<br>";
  echo $invoice['ship_street1']."<br>";
  echo $invoice['ship_street2']."<br>";
  echo $invoice['ship_city']."<br>";
  echo $invoice['ship_state']."<br>";
  echo $invoice['ship_zip']."<br>";
  echo "Paid: ";
  if($invoice['paid']){
    echo "YES<br>";
  } else {
    echo "NO<br>";
  }
  echo "Shipped: ";
  if($invoice['shipped']){
    echo "YES<br>";
  } else {
    echo "NO<br>";
  }
  echo "Invoice Items:<br>";
  echo "<table class='w3-striped'>";
  echo "<tr><th>sku</th><th>free</th><th>style</th><th>size</th></tr>";
  $sql = "SELECT `inventory`.`sku`,`free`,`inventory`.`category`,`inventory`.`size`
          FROM `invoice`,`invoiceItem`,`inventory`
          WHERE `invoice`.`invoiceNumber`=`invoiceItem`.`invoiceNumber`
          AND `invoiceItem`.`sku`=`inventory`.`sku`";
  $pdo = $conn->query($sql);
  while($items = $pdo->fetch()){
    echo "<tr><td>".$items['sku']."</td><td>";
    if($items['free']){
      echo "YES</td>";
    } else {
      echo "NO</td>";
    }
    echo "<td>".$items["category"]."</td>";
    echo "<td>".$items["size"]."</td></tr>";
  }
  if($userType == "member"){
    echo "<form method=\"post\" action=\"showinvoice.php\" target=\"_self\">";
    echo "<input hidden type='number' name='invoiceNumber' value=$invoiceNumber>";
    echo "<input type='number' name='sku' class='w3-input w3-border'><label class='w3-validate'>Add a free item by sku</label>";
    echo "<input type='submit' name='submit' value='Add Givaway'><br>";
    if(empty($invoice['tracking'])){
      echo "<input type='text' name='tracking' class='w3-input w3-border'><label class='w3-validate'>Tracking Number</label>";
      echo "<input type='submit' name='submit' value='Update Tracking #'>";
    }
    if(!$invoice['paid']){
      echo "<br><input type='submit' name='submit' value='Mark Paid'>";
    }
    echo "</form>";
  }
} else {
  echo "access violation";
  die();
}

$conn = null;
?>
</body>
