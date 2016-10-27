<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>LuLa Shop</title>
	<?php include 'menu.php'; ?>
</head>

<!--TODO: implement new seller validation  -->
<body>
<p> This is where you log in to the system or register as a new user </p>
<ul class="w3-navbar w3-black">
  <li><a href="#" onclick="openLogin('currentUser')">Existing User Login</a></li>
  <li><a href="#" onclick="openLogin('newUser')">New Customer Registration</a></li>
  <li><a href="#" onclick="openLogin('newMember')">New Seller Registration</a></li>
</ul>

<div id="currentUser" class="login">
	<div class="w3-container w3-teal">
		<h3>Please enter your email and password.</h2>
	</div>
	<form class="w3-container">
		<fieldset>
			<legend>Are you logging in as a customer or a seller?</legend>
			<label class="w3-label">Customer</label><input class="w3-radio" type="radio" name="userType" value="user" checked>
			<label class="w3-label">Seller</label><input class="w3-radio" type="radio" name="userType" value="member">
		</fieldset>
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<label class="w3-label">Email</label>
				<input class="w3-input w3-border" type="email" name="email" placeholder="email">
			</div>
			<div class="w3-quarter">
				<label class="w3-label">Password</label>
				<input class="w3-input w3-border" type="password" name="pw" placeholder="password">
			</div>
			<p><input class="w3-button" type="submit" value="Login"></p>
		</div>
	</form>
</div>

<div id="newUser" class="login">
	<div class="w3-container w3-teal">
		<h3>Register for a new customer account.</h2>
	</div>
	<form class="w3-container">
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<label class="w3-label">First Name</label>
				<input class="w3-input w3-border" type="text" name="firstName" placeholder="first name">
			</div>
			<div class="w3-quarter">
				<label class="w3-label">Last Name</label>
				<input class="w3-input w3-border" type="text" name="lastName" placeholder="last name">
			</div>
			<div class="w3-quarter">
				<label class="w3-label">Email</label>
				<input class="w3-input w3-border" type="email" name="email" placeholder="email">
			</div>
		</div>
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<label class="w3-label">Password</label>
				<input class="w3-input w3-border" type="password" name="pw1" placeholder="password">
			</div>
			<div class="w3-quarter">
				<label class="w3-label">Confirm Password</label>
				<input class="w3-input w3-border" type="password" name="pw2" placeholder="confirm password">
			</div>
		</div>
		<p><label class="w3-label">Do you want to subscribe to email updates from sellers you follow?</label>
		<input class="w3-check" type="checkbox" name="subscribe" value=subscribed check="checked"></p>
		<p><label class="w3-label">Please select your local timezone</label>
<!-- TODO: Use a python script to parse the tsv file timezones.txt and write out to xhtml -->
		<select class="w3-select w3-quarter" name="timezone">
			<option value="-5">EST(-5)</option>
		</select></p>
		<p><input class="w3-button" type="submit" value="register"></p>
	</form>
</div>

<div id="newMember" class="login">
	<div class="w3-container w3-teal">
		<h3>Register for a new seller account.</h2>
	</div>
	<form class="w3-container">
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<label class="w3-label">First Name</label>
				<input class="w3-input w3-border" type="text" name="firstName" placeholder="first name">
			</div>
			<div class="w3-quarter">
				<label class="w3-label">Last Name</label>
				<input class="w3-input w3-border" type="text" name="lastName" placeholder="last name">
			</div>
			<div class="w3-quarter">
				<label class="w3-label">Email</label>
				<input class="w3-input w3-border" type="email" name="email" placeholder="email">
			</div>
		</div>
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<label class="w3-label">Password</label>
				<input class="w3-input w3-border" type="password" name="pw1" placeholder="password">
			</div>
			<div class="w3-quarter">
				<label class="w3-label">Confirm Password</label>
				<input class="w3-input w3-border" type="password" name="pw2" placeholder="confirm password">
			</div>
		</div>
		<div class="w3-row-padding">
			<div class="w3-full">
				<label class="w3-label">Social Media Profiles - enter each link seperated by a space</label>
				<input class="w3-input w3-border" type="text" name="socialLinks" placeholder="social media">
			</div>
		</div>
		<div class="w3-row-padding">
			<div class="w3-full">
				<label class="w3-label">Briefly tell us about yourself</label>
				<textarea class="w3-container" type="textarea" name="aboutMe" rows="10" cols="40">About Me</textarea>
			</div>
		</div>
		<p><label class="w3-label">Please select your local timezone</label>
<!-- TODO: Use a python script to parse the tsv file timezones.txt and write out to xhtml -->
		<select class="w3-select w3-quarter" name="timezone">
			<option value="-5">EST(-5)</option>
		</select></p>
		<p><input class="w3-button" type="submit" value="register"></p>
	</form>
</div>

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