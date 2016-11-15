<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>LuLa Shop</title>
	<?php include 'menu.php'; ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
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
<p> This is where you shop for clothes :D yay! </p>
<nav class="w3-sidenav w3-light-grey w3-card-2" style="width:160px;">
	<form class="w3-container" method="post" action="shop.php">
		<input class="w3-radio" type="radio" name="colorFilter" value="all" checked>
		<label class="w3-label">All Colors</label><br>
		<input class="w3-radio" type="radio" name="colorFilter" value="filter">
		<label class="w3-label">Filter Colors</label>
		<div class="w3-accordion">
			<a onclick="myAccFunc()" href="#">
				Select Colors <i class="fa fa-caret-down"></i>
			</a>
			<div id="demoAcc" class="w3-accordion-content w3-white w3-card-4">
				<input class="w3-check" type="checkbox" name="green" value="green"
				 <?php
					 if ($_SERVER["REQUEST_METHOD"] == "POST") {
						 if (isset($_POST['green'])) {
							 echo "checked";
						 }
					 }
				 ?>>
				 <label class="w3-label">green</label><br>
				 <input class="w3-check" type="checkbox" name="teal" value="teal"
				 <?php
					 if ($_SERVER["REQUEST_METHOD"] == "POST") {
						 if (isset($_POST['teal'])) {
							 echo "checked";
						 }
					 }
				 ?>>
				 <label class="w3-label">teal</label><br>
					 <input class="w3-check" type="checkbox" name="blue" value="blue"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['blue'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">blue</label><br>
						<input class="w3-check" type="checkbox" name="purple" value="purple"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['purple'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">purple</label><br>
						<input class="w3-check" type="checkbox" name="red" value="red"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['red'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">red</label><br>
						<input class="w3-check" type="checkbox" name="pink" value="pink"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['pink'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">pink</label><br>
						<input class="w3-check" type="checkbox" name="flesh" value="flesh"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['flesh'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">flesh</label><br>
						<input class="w3-check" type="checkbox" name="tan" value="tan"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['tan'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">tan</label><br>
						<input class="w3-check" type="checkbox" name="brown" value="brown"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['brown'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">brown</label><br>
						<input class="w3-check" type="checkbox" name="black" value="black"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['black'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">black</label><br>
						<input class="w3-check" type="checkbox" name="lime" value="lime"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['lime'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">lime</label><br>
						<input class="w3-check" type="checkbox" name="yellow" value="yellow"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['yellow'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">yellow</label><br>
						<input class="w3-check" type="checkbox" name="orange" value="orange"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['orange'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">orange</label><br>
						<input class="w3-check" type="checkbox" name="grey" value="grey"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['grey'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">grey</label><br>
						<input class="w3-check" type="checkbox" name="maroon" value="maroon"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['maroon'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">maroon</label><br>
						<input class="w3-check" type="checkbox" name="white" value="white"
						<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['white'])) {
						echo "checked";
						}
						}
						?>>
						<label class="w3-label">white</label>
			</div>
		</div>
	</form>

</nav>

<div class="w3-container" style="margin-left:160px">
	<h4>Search Results</h4>
</div>

<script>
	function myAccFunc() {
		var x = document.getElementById("demoAcc");
		if (x.className.indexOf("w3-show") == -1) {
			x.className += " w3-show";
			x.previousElementSibling.className += " w3-green";
		} else {
			x.className = x.className.replace(" w3-show", "");
			x.previousElementSibling.className =
			        x.previousElementSibling.className.replace(" w3-green", "");
		}
	}
</script>
</body>

<footer>
<?php include 'foot.php'; ?>
</footer>
</html>