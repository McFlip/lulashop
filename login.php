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
	?>
</head>

<!--TODO: implement new seller validation  -->
<body>
	<p> This is where you log in to the system or register as a new user </p>

	<?php
	// define variables and set to empty values
	$firstNameErr=$lastNameErr=$passwordErr=$emailErr=$timezoneOffsetErr=$loginErr="";
	$firstName=$lastName=$password=$email=$timezoneOffset=$subscribe=$socialLinks=$aboutMe="";
	//check required entries
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST["firstName"])) {
			$firstNameErr = "***First name is required";
		} else {
			$firstName = test_input($_POST["firstName"]);
			// check if name only contains letters and whitespace
			if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
				$firstNameErr = "***Only letters and white space allowed";
			}
		}
		if (empty($_POST["lastName"])) {
			$lastNameErr = "***Last name is required";
		} else {
			$lastName = test_input($_POST["lastName"]);
			// check if name only contains letters and whitespace
			if (!preg_match("/^[a-zA-Z ]*$/",$lastName)) {
				$lastNameErr = "***Only letters and white space allowed";
			}
		}
		if (empty($_POST["email"])) {
			$emailErr = "***Email is required";
		} else {
			$email = test_input($_POST["email"]);
			// check if e-mail address is well-formed
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "***Invalid email format";
			}
			// check if email already exists for new Registration
			if (isset($_POST['btnRegisterUser'])) {
				$sql = "SELECT `email` FROM `user` WHERE email='$email'";
				$pdo = $conn->query($sql);
				$result = $pdo->fetchColumn();
				if ($result) {
					$emailErr = "***This email is already registered";
				}
			}
			if (isset($_POST['btnRegisterMember'])) {
				$sql = "SELECT `email` FROM `member` WHERE email='$email'";
				$pdo = $conn->query($sql);
				$result = $pdo->fetchColumn();
				if ($result) {
					$emailErr = "***This email is already registered";
				}
			}
			if (!isset($_POST["btnLogin"])) {
				$password1 = test_input($_POST["password1"]);
				$password2 = test_input($_POST["password2"]);
				if ($password1 !== $password2) {
					$passwordErr = "***Passwords do not match";
				} else if(empty($password1)) {
					$passwordErr = "***Password is required";
				}
			}
			if (isset($_POST["btnLogin"])) {
				//verify login
				$password = test_input($_POST['password']);
				if (empty($password)) {
					$passwordErr = "***Password is required";
				} else {
					$table = $_POST["userType"];
					$sql = "SELECT `firstName`, `lastName` FROM `$table` WHERE email='$email' AND password='$password'";
					$pdo = $conn->query($sql);
					$result = $pdo->fetch();
					if (!isset($result['firstName'])) {
						$loginErr = "***The email and/or password was incorrect";
					} else {
						//TODO: start session
						echo "User ".$result["firstName"]." ".$result["lastName"]." has been logged in :D\n";
					}
				}
			} else {
				//verify Registration
				$arrErr = array($firstNameErr,$lastNameErr,$passwordErr,$emailErr,$timezoneOffsetErr,$loginErr);
				$isErr = false;
				foreach ($arrErr as $error) {
					if (!empty($error)) {
						$isErr = true;
					}
				}
				if (!$isErr) {
					//TODO:sql insert goes here
					echo "Congratulations you have been successfuly registered! Please log in.\n";
				} else {
					echo "Unfortunately there was an error. Please review the form.\n"; //script will automatically switch to correct form
				}
			}
		}
		if (empty($_POST["timezoneOffset"])) {
			$timezoneOffsetErr="Time Zone is required";
		} else {
			$timezoneOffset=$_POST["timezoneOffset"];
		}
	}
	//sanatize input
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	?>
	<ul class="w3-navbar w3-black">
		<li><a href="#" onclick="openLogin('currentUser')">Existing User Login</a></li>
		<li><a href="#" onclick="openLogin('newUser')">New Customer Registration</a></li>
		<li><a href="#" onclick="openLogin('newMember')">New Seller Registration</a></li>
	</ul>

	<div id="currentUser" class="login">
		<div class="w3-container w3-teal">
			<h3>Please enter your email and password.</h2>
		</div>
		<form class="w3-container" method="post" action="login.php">
			<fieldset>
				<legend>Are you logging in as a customer or a seller?</legend>
				<label class="w3-label">Customer</label>
				<input class="w3-radio" type="radio" name="userType" value="user" checked>
				<label class="w3-label">Seller</label>
				<input class="w3-radio" type="radio" name="userType" value="member">
			</fieldset>
			<div class="w3-row-padding">
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="email" name="email" placeholder="email" required>
					<label class="w3-label w3-validate">Email</label><span class="error"><?php echo $emailErr;?></span>
				</div>
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="password" name="password" placeholder="password" required>
					<label class="w3-label w3-validate">Password</label><?php echo $passwordErr;?></span>
				</div>
				<p><input class="w3-button" type="submit" name="btnLogin" value="Login"></p>
			</div>
		</form>
		<span class="error"><?php echo $loginErr;?></span>
	</div>

	<div id="newUser" class="login">
		<div class="w3-container w3-teal">
			<h3>Register for a new customer account.</h2>
			<p><span class="error">* required field.</span></p>
		</div>
		<form class="w3-container" method="post" action="login.php">
			<div class="w3-row-padding">
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="text" name="firstName" placeholder="first name" required value="<?php echo $firstName;?>">
					<label class="w3-label w3-validate">First Name</label> <span class="error"><?php echo $firstNameErr;?></span>
				</div>
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="text" name="lastName" placeholder="last name" required value="<?php echo $lastName;?>">
					<label class="w3-label w3-validate">Last Name</label><span class="error"><?php echo $lastNameErr;?></span>
				</div>
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="email" name="email" placeholder="email" required value="<?php echo $email;?>">
					<label class="w3-label w3-validate">Email</label><span class="error"><?php echo $emailErr;?></span>
				</div>
			</div>
			<div class="w3-row-padding">
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="password" name="password1" placeholder="password" required>
					<label class="w3-label w3-validate">Password</label><span class="error"><?php echo $passwordErr;?></span>
				</div>
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="password" name="password2" placeholder="confirm password" required>
					<label class="w3-label w3-validate">Confirm Password</label><span class="error"><?php echo $passwordErr;?></span>
				</div>
			</div>
			<p><label class="w3-label">Do you want to subscribe to email updates from sellers you follow?</label>
			<input class="w3-check" type="checkbox" name="subscribe" value="subscribed"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['subscribe'])) {
					echo "checked";
				}
			} else {
				echo "checked";
			}
			?>></p>
	<!-- TODO: Use a python script to parse the tsv file timezones.txt and write out to xhtml -->
			<p><select class="w3-select w3-quarter" name="timezoneOffset" required>
				<option value="" disabled selected>Choose your timezone</option>
				<option value="-5">EST(-5)</option>
			</select>
			<label class="w3-label">Please select your local timezone</label><span class="error"><?php echo $timezoneOffsetErr;?></span></p>
			<p><input class="w3-button" type="submit" name="btnRegisterUser" value="Register"></p>
		</form>
	</div>

	<div id="newMember" class="login">
		<div class="w3-container w3-teal">
			<h3>Register for a new seller account.</h2>
			<p><span class="error">* required field.</span></p>
		</div>
		<form class="w3-container" method="post" action="login.php">
			<div class="w3-row-padding">
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="text" name="firstName" placeholder="first name" required value="<?php echo $firstName;?>">
					<label class="w3-label w3-validate">First Name</label> <span class="error"><?php echo $firstNameErr;?></span>
				</div>
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="text" name="lastName" placeholder="last name" required value="<?php echo $lastName;?>">
					<label class="w3-label w3-validate">Last Name</label><span class="error"><?php echo $lastNameErr;?></span>
				</div>
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="email" name="email" placeholder="email" required value="<?php echo $email;?>">
					<label class="w3-label w3-validate">Email</label><span class="error"><?php echo $emailErr;?></span>
				</div>
			</div>
			<div class="w3-row-padding">
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="password" name="password1" placeholder="password" required>
					<label class="w3-label w3-validate">Password</label><span class="error"><?php echo $passwordErr;?></span>
				</div>
				<div class="w3-quarter">
					<input class="w3-input w3-border" type="password" name="password2" placeholder="confirm password" required>
					<label class="w3-label w3-validate">Confirm Password</label><span class="error"><?php echo $passwordErr;?></span>
				</div>
			</div>
	<!-- 	TODO:validate social media links	 -->
			<div class="w3-row-padding">
				<div class="w3-full">
					<input class="w3-input w3-border" type="text" name="socialLinks" placeholder="social media" value="<?php echo $socialLinks;?>">
					<label class="w3-label">Social Media Profiles - enter each link seperated by a space</label>
				</div>
			</div>
			<div class="w3-row-padding">
				<div class="w3-full">
					<textarea class="w3-container" type="textarea" name="aboutMe" rows="10" cols="40">
					<?php
					if(isset($_POST['aboutMe'])) {
						echo $_POST['aboutMe'];
					} else {
						echo "About Me";
					}
					?>
					</textarea>
					<label class="w3-label">Briefly tell us about yourself</label>
				</div>
			</div>
	<!-- TODO: Use a python script to parse the tsv file timezones.txt and write out to xhtml -->
			<p><select class="w3-select w3-quarter" name="timezoneOffset" required>
				<option value="" disabled selected>Choose your timezone</option>
				<option value="-5">EST(-5)</option>
			</select>
			<label class="w3-label">Please select your local timezone</label><span class="error"><?php echo $timezoneOffsetErr;?></span></p>
			<p><input class="w3-button" type="submit" name="btnRegisterMember" value="Register"></p>
		</form>
	</div>

<!-- script that controls what is displayed -->
	<script>
		openLogin("currentUser")
		function openLogin(formType) {
				var i;
				var x = document.getElementsByClassName("login");
				for (i = 0; i < x.length; i++) {
					x[i].style.display = "none";
				}
				document.getElementById(formType).style.display = "block";
		}
	</script>
	<!-- On register success return to login. On fail go back to reg form -->
	<?php
		if ($isErr && isset($_POST["btnRegisterMember"])) {
		  echo "<script>openLogin(\"newMember\")</script>";
		} else if ($isErr && isset($_POST["btnRegisterUser"])) {
		  echo "<script>openLogin(\"newUser\")</script>";
		}
	?>
</body>

<!-- close DB connection -->
<?php $conn = null;?>

<footer>
<?php include 'foot.php'; ?>
</footer>
</html>