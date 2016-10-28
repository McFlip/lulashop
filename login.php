<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>LuLa Shop</title>
	<?php include 'menu.php'; ?>
	<style>
	.error {color: #FF0000;}
	</style>
</head>

<!--TODO: implement new seller validation  -->
<body>
<p> This is where you log in to the system or register as a new user </p>
<?php
// define variables and set to empty values
$firstNameErr=$lastNameErr=$passwordErr=$emailErr=$timezoneOffsetErr="";
$firstName=$lastName=$password=$email=$timezoneOffset=$subscribe=$socialLinks=$aboutMe="";
//check required entries
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["firstName"])) {
    $firstNameErr = "First name is required";
  } else {
    $firstName = test_input($_POST["firstName"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
      $firstNameErr = "Only letters and white space allowed";
    }
  }
  if (empty($_POST["lastName"])) {
    $lastNameErr = "First name is required";
  } else {
    $lastName = test_input($_POST["lastName"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$lastName)) {
      $lastNameErr = "Only letters and white space allowed";
    }
  }
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
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
				<label class="w3-label w3-validate">Email</label>
				<input class="w3-input w3-border" type="email" name="email" placeholder="email">
			</div>
			<div class="w3-quarter">
				<label class="w3-label w3-validate">Password</label>
				<input class="w3-input w3-border" type="password" name="password" placeholder="password">
			</div>
			<p><input class="w3-button" type="submit" value="Login"></p>
		</div>
	</form>
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
		<p><input class="w3-button" type="submit" value="registerUser"></p>
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
		<p><input class="w3-button" type="submit" value="registerMember"></p>
	</form>
</div>

<!-- TODO: On register success return to login. On fail go back to reg form -->
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

</body>

<footer>
<?php include 'foot.php'; ?>
</footer>
</html>