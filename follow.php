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
<div class="w3-container">
<?php
$userID = $_SESSION['userID'];
if (isset($_POST['follow'])){
  $memberID = $_POST['memberID'];
  $sql = "INSERT INTO `followMember`(memberID,userID) VALUES($memberID,$userID)";
  $conn->exec($sql);
} else if(isset($_POST['unfollow'])) {
  $memberID = $_POST['memberID'];
  $sql = "DELETE FROM `followMember`
          WHERE `memberID`=$memberID
          AND `userID`=$userID";
  $conn->exec($sql);
}
$sql = "SELECT `firstName`,`lastName`,`followMember`.`memberID`
        FROM `member`,`followMember`
        WHERE `member`.`memberID`=`followMember`.`memberID`
        AND `followMember`.`userID`=$userID";
$pdo = $conn->query($sql);
echo "<table class='w3-striped'>
      <tr><th>consultant</th><th>name</th><th>Unfollow</th></tr>";
while($consultant = $pdo->fetch()){
  echo "<tr><td>".$consultant['firstName']."</td><td>".$consultant['lastName']."</td><td>
          <form method='post' action='follow.php'>
          <input hidden name='memberID' value=".$consultant['memberID'].">
          <input type='submit' name='unfollow' value='unfollow'>
          </form>
        </td></tr>";
}
echo "</table>";
?>
</div>
</body>
<footer>
<?php
include 'foot.php';
$conn = null;
?>
</footer>
</html>