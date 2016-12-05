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
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["byCity"])){
      $state = $_POST["state"];
      $city = $_POST["city"];
      $sql = "SELECT `memberID`,`fistName`,`lastName`,`email`,`aboutMe`
              FROM `member`,address`
              WHERE =`address`.`ownerID` LIKE CONCAT('m_',`member`.`memberID`)";
//               AND `address`.`state`=$state
//               AND `address`.`city`=$city";
      $pdo = $conn->query($sql);
      echo "<table class='w3-striped'>
            <tr><th>Consultant</th><th>Name</th><th>email</th><th>about me</th><th>FOLLOW ME</th></tr>";
      while ($consultant = $pdo->fetch()){
        echo "<tr><td>".$consultant['firstName']."</td><td>".$consultant['lastName']."</td><td>".$consultant['email']."</td><td>".$consultant['aboutMe']."</td><td>
        <form method='post' action='follow.php'>
          <input hidden name='memberID' value=".$consultant['memberID'].">
          <input type='submit' value='follow'>
        </form></td></tr>";
      }
      echo "</table>";
    }
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
	<form class="w3-container">
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<p><input class="w3-radio" type="radio" name="address" value="1" checked></p>
				<label class="w3-validate">
					<div class="w3-card-4">
						<p>FriendlyName</p>
						<p>Street1</p>
						<p>Street2</p>
						<p>City, St, Zip</p>
					</div>
				</label>
			</div>
			<div class="w3-quarter">
				<p><input class="w3-radio" type="radio" name="address" value="2"></p>
				<label class="w3-validate">
					<div class="w3-card-4">
						<p>FriendlyName</p>
						<p>Street1</p>
						<p>Street2</p>
						<p>City, St, Zip</p>
					</div>
				</label>
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
	<iframe height="500px" width="500px" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCUEb0gUKh0MIzZQ8rHL_r_Ghr1b0-jK5I&q=Florida+State+University" allowfullscreen>" name="map"></iframe>
</div>
</body>

<footer>
<?php
  include 'foot.php';
  $conn = null;
?>
</footer>
</html>