<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>LuLa Shop</title>
  <?php include 'menu.php'; ?>
  <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
  catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
  }
  ?>
</head>

<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  $userID = $_SESSION["userID"];
  if(isset($_POST["invoice"])){
    $billAddressID = $_POST["billAddressID"];
    if ($_POST["shipAddress"] == "same"){
      $shipAddressID = $billAddressID;
    } else {
      $shipAddressID = $_POST["shipAddressID"];
    }
    if(empty($billAddressID) || empty($shipAddressID)){
      echo "ERROR: Please review your address selections.";
    } else {
      $sql = "SELECT DISTINCT `memberID`
              FROM `cart`,`inventory`
              WHERE `cart`.`sku`=`inventory`.`sku`";
      $pdo = $conn->query($sql);
      while($seller = $pdo->fetchColumn()){
        $sql2 = "SELECT *
                FROM `address`
                WHERE `addressID`=$billAddressID";
        $pdo2 = $conn->query($sql2);
        $billAddressArr = $pdo2->fetch();
        $sql2 = "SELECT *
                FROM `address`
                WHERE `addressID`=$shipAddressID";
        $pdo2 = $conn->query($sql2);
        $shipAddressArr = $pdo2->fetch();
        $sql2 = "INSERT INTO `invoice`
                (
                paid,
                bill_street1,
                bill_street2,
                bill_city,
                bill_state,
                bill_zip,
                ship_street1,
                ship_street2,
                ship_city,
                ship_state,
                ship_zip,
                shipped,
                userID,
                memberID
                )
                VALUES
                (
                0,'".
                $billAddressArr['street1']."','".
                $billAddressArr['street2']."','".
                $billAddressArr['city']."','".
                $billAddressArr['state']."','".
                $billAddressArr['zip']."','".
                $shipAddressArr['street1']."','".
                $shipAddressArr['street2']."','".
                $shipAddressArr['city']."','".
                $shipAddressArr['state']."','".
                $shipAddressArr['zip']."',
                0,
                $userID,
                $seller
                )";
        $conn->exec($sql2);
        $invoiceNumber = $conn->lastInsertId();
        $sql2 = "SELECT `cart`.`sku`
                FROM `cart`,`inventory`
                WHERE `cart`.`sku`=`inventory`.`sku`
                AND `inventory`.`memberID`=$seller";
        $pdo2 = $conn->query($sql2);
        while($skuBySeller = $pdo2->fetchColumn()){
          $sql3 = "INSERT INTO `invoiceItem`
                  (
                  invoiceNumber,
                  sku,
                  quantity,
                  free
                  )
                  VALUES
                  (
                  $invoiceNumber,
                  $skuBySeller,
                  1,
                  0
                  )
                  ";
          $conn->exec($sql3);
        }
      }
      echo "Invoices Created. Congrats! You will recieve a confirmation email.";
      echo "<script>document.getElementById('checkoutForm').style.display='none';</script>"; //TODO: test me
    }
  }
} else {
  echo "access violation";
  die();
}
?>
<div id="checkoutForm" class="w3-container">
  <form action="invoice.php" method="post">
    <div class="w3-row-padding">
      <div class="w3-half">
        <select class="w3-select" name="billAddressID">
          <option value="NULL" disabled>Select billing address</option>
          <?php
            $sql = "SELECT * FROM `address` WHERE `ownerID`='u_".$userID."';";
            echo "<br>".$sql;
            $pdo = $conn->query($sql);
            while ($result = $pdo->fetch()) {
              echo "<option value=\"".$result['addressID']."\"";
              if (isset($_POST["billAddressID"])){
                if ($result['addressID'] == $_POST["billAddressID"]){
                  echo "selected";
                }
              }
              echo ">";
              echo $result['street1']." ".$result['street2']." ".$result['appt']." ".$result['city']." ".$result['state']." ".$result['zip'];
              echo "</option>";
            }
          ?>
        </select>
        <label class="w3-label w3-validate">billing address</label>
      </div>
    </div>
    <div class="w3-row-padding">
      <div class="w3-half">
        <input class="w3-radio" type="radio" name="shipAddress" value="same"
        <?php
        if (isset($_POST["shipAddress"])) {
          if ($_POST['shipAddress']=="same") {
            echo "checked";
          }
        } else {
          echo "checked";
        }
        ?>>
        <label class="w3-label">Shipping address is same as billing</label>
        <input class="w3-radio" type="radio" name="shipAddress" value="diff"
        <?php
        if (isset($_POST["shipAddress"])) {
          if ($_POST['shipAddress']=="diff") {
            echo "checked";
          }
        }
        ?>>
        <label class="w3-label">Use this address for shipping</label>
      </div>
    </div>
    <div class="w3-row-padding">
      <div class="w3-half">
        <select class="w3-select" name="shipAddressID">
          <option value="NULL" disabled>Select shipping address</option>
          <?php
            $pdo = $conn->query($sql);
            while ($result = $pdo->fetch()) {
              echo "<option value=\"".$result['addressID']."\"";
              if (isset($_POST["shipAddressID"])){
                if ($result['addressID'] == $_POST["shipAddressID"]){
                  echo "selected";
                }
              }
              echo ">";
              echo $result['street1']." ".$result['street2']." ".$result['appt']." ".$result['city']." ".$result['state']." ".$result['zip'];
              echo "</option>";
            }
          ?>
        </select>
        <label class="w3-label w3-validate">shipping address</label>
      </div>
    </div>
    <div class="w3-row-padding">
      <button class='w3-button' style='font-size:24px' type='submit' name='invoice' value='checkout'>Checkout<i class='material-icons'>shopping_cart</i></button>
    </div>
  </form>
</body>