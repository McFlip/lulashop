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
<?php
$userID = $_SESSION['userID'];
$googleMap = "https://www.google.com/maps/embed/v1/place?key=AIzaSyCUEb0gUKh0MIzZQ8rHL_r_Ghr1b0-jK5I";
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["byCity"])){
      $state = $_POST["state"];
      $city = $_POST["city"];
      $sql = "SELECT `memberID`,`addressID`,`firstName`,`lastName`,`email`,`aboutMe`
              FROM `member`,`address`
              WHERE `ownerID`=CONCAT('m_',`memberID`)
              AND `address`.`state`='$state'
              AND `address`.`city`='$city'";
    }
    else if(isset($_POST["bySavedAddr"]))
    {
      $addressID = $_POST['addressID'];
      $radius = $_POST['searchArea'] / 2;
      $sql = "SELECT `latitude`,`longitude`
              FROM `address`
              WHERE `addressID`=$addressID";
      $pdo = $conn->query($sql);
      $coord = $pdo->fetch();
      $latitude = $coord['latitude'];
      $longitude = $coord['longitude'];
      $sql = "SELECT `memberID`,`addressID`,`firstName`,`lastName`,`email`,`aboutMe`
              FROM `member`,`address`
              WHERE `ownerID`=CONCAT('m_',`memberID`)
              AND `latitude` > $latitude - $radius
              AND `latitude` < $latitude + $radius
              AND `longitude` > $longitude - $radius
              AND `longitude` < $longitude + $radius";
    }
    else {
      $sql = "SELECT `memberID`,`addressID`,`firstName`,`lastName`,`email`,`aboutMe`
              FROM `member`,`address`
              WHERE `ownerID`=CONCAT('m_',`memberID`)";
    }
    echo $sql;
    $pdo = $conn->query($sql);
    echo "<table class='w3-striped'>
          <tr><th>Consultant</th><th>Name</th><th>email</th><th>about me</th><th>FOLLOW ME</th><th>Show on Map</th></tr>";
    //TODO: hide the follow button for members that the user is already following
    while ($consultant = $pdo->fetch()){
      echo "<tr><td>".$consultant['firstName']."</td><td>".$consultant['lastName']."</td><td>".$consultant['email']."</td><td>".$consultant['aboutMe']."</td><td>
      <form method='post' action='follow.php'>
      <input hidden name='memberID' value=".$consultant['memberID'].">
      <input type='submit' name='follow' value='follow'>";
      if(isset($_POST["byCity"])){
        echo "<input hidden name='byCity'value='x'>
        <input hidden name='city' value='".$city."'>
        <input hidden name='state' value='".$state."'>";
      }
      echo "</form></td>
      <td><form method='post' action='findconsultant.php'>
      <input hidden name='addressID' value=".$consultant['addressID'].">
      <input type='submit' name='showMap' value='Show on Map'>";
      if(isset($_POST["byCity"])){
        echo "<input hidden name='byCity'value='x'>
        <input hidden name='city' value='".$city."'>
        <input hidden name='state' value='".$state."'>";
      }
      echo "</form></td></tr>";
    }
    echo "</table>";
    if(isset($_POST["showMap"])){ //TODO:put this in a modal
      $sql = "SELECT * FROM `address` WHERE `addressID`=".$_POST["addressID"];
      $pdo = $conn->query($sql);
      $address = $pdo->fetch();
      //TODO: use latitude and longitude instead
      $googleMap .= "&q=".urlencode($address['street1'])."+".
                    urlencode($address['street2'])."+".
                    urlencode($address['appt'])."+".
                    urlencode($address['city'])."+".
                    urlencode($address['state'])."+".
                    urlencode($address['zip']);
    } else {
      $googleMap .= "&q=Florida+State+University";
    }
  } else {
    $googleMap .= "&q=Florida+State+University";
  }
?>
<p> This is where you find consultants </p>
<ul class="w3-navbar w3-black">
  <li><a href="#" onclick="openSearch('savedAddress')">Saved Address</a></li>
  <li><a href="#" onclick="openSearch('pickCity')">Choose City</a></li>
  <li><a href="#" onclick="openSearch('enterAddress')">Enter Address</a></li>
</ul>

<div id="savedAddress" class="searchAddress">
  <div class="w3-container w3-teal">
    <h3>Search using your saved addresses. You must be logged in to use saved addresses.</h3>
  </div>
  <form class="w3-container" method="post" action="findconsultant.php">
    <div class="w3-row-padding">
      <div class="w3-rest">
        <select class="w3-select" name="addressID">
        <?php
        $sql = "SELECT * FROM `address` WHERE `ownerID`=CONCAT(\"u_\",\"$userID\")";
        $pdo = $conn->query($sql);
        while ($result = $pdo->fetch()) {
          echo "<option value=\"".$result['addressID']."\"";
          if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if ($result['addressID'] == $addressID){
              echo "selected";
            }
          }
          echo ">";
          echo $result['street1']." ".$result['street2']." ".$result['appt']." ".$result['city']." ".$result['state']." ".$result['zip'];
          echo "</option>";
        }
        ?>
        </select>
        <label class="w3-label w3-validate">address</label>
      </div>
    </div>
    <div class="w3-row-padding">
      <div class="w3-quarter">
        <select class="w3-select" name="searchArea">
          <option value="" disabled selected>Choose search size</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
        </select>
        <input class="w3-button" type="submit" name ="bySavedAddr" value="Search">
      </div>
    </div>
  </form>
</div>

<div id="pickCity" class="searchAddress">
  <div class="w3-container w3-teal">
    <h3>Search by city. First pick your state, then the city.</h3>
  </div>
  <form class="w3-container" method="post" action="findconsultant.php">
    <div class="w3-row-padding">
      <div class="w3-quarter">
        <label>State</label>
        <input list="states" name="state">
        <datalist id="states">
          <?php
          $sql = "SELECT DISTINCT `state`
                  FROM `address`";
          $pdo = $conn->query($sql);
          while ($st = $pdo->fetchColumn()){
            echo "<option value='$st'>";
          }
          ?>
        </datalist>
      </div>
      <div class="w3-half">
        <label>City</label>
        <input list="cities" name="city">
        <datalist id="cities">
        <?php
        //TODO: use ajax to narrow cities to match selected state
        $sql = "SELECT DISTINCT `city`
        FROM `address`";
        $pdo = $conn->query($sql);
        while ($c = $pdo->fetchColumn()){
          echo "<option value='$c'>";
        }
        ?>
        </datalist>
      </div>
    </div>
    <div class="w3-row-padding">
      <input class="w3-button" type="submit" name="byCity" value="Search">
    </div>
  </form>
</div>
<div id="enterAddress" class="searchAddress">
  <div class="w3-container w3-teal">
    <h3>Search by specifying an address and a search area in square miles.</h2>
  </div>
  <form class="w3-container">
    <div class="w3-row-padding">
      <div class="w3-quarter">
        <label>Street1</label>
        <input class="w3-input w3-border" type="text" name="street1" placeholder="street1">
      </div>
      <div class="w3-quarter">
        <label>Street2</label>
        <input class="w3-input w3-border" type="text" name="street2" placeholder="street2">
      </div>
      <div class="w3-quarter">
        <label>State</label>
        <select class="w3-select w3-border" name="state" placeholder="Choose State">
          <option value="" disabled selected>Choose state</option>
          <option value="fl">fl</option>
        </select>
      </div>
      <div class="w3-quarter">
        <label>City</label>
        <input class="w3-input w3-border" type="text" name="city" placeholder="city">
      </div>
    </div>
    <div class="w3-row-padding">
      <div class="w3-quarter">
        <select class="w3-select" name="searchArea">
          <option value="" disabled selected>Choose search size</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
        </select>
        <input class="w3-button" type="submit" value="Search">
      </div>
    </div>
  </form>
</div>

<script>
openSearch("savedAddress")
function openSearch(searchMethod) {
    var i;
    var x = document.getElementsByClassName("searchAddress");
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";
    }
    document.getElementById(searchMethod).style.display = "block";
}
</script>

<!-- TODO: add in the following properties w/ our URL
  &attribution_source=LuLa+Shop
  &attribution_web_url=http://localhost/lulashop/findconsultant.php -->
<div class="w3-container w3-center">
  <iframe height="500px" width="500px" src=<?php echo "$googleMap"; ?> allowfullscreen>" name="map"></iframe>
</div>
</body>

<footer>
<?php
  include 'foot.php';
  $conn = null;
?>
</footer>
</html>
