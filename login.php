<?php
session_start();
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
	?>
</head>

<!--TODO: implement new seller validation  -->
<body>
	<p> This is where you log in to the system or register as a new user </p>

	<?php
	// define variables and set to empty values
	$firstNameErr=$lastNameErr=$passwordErr=$emailErr=$timezoneOffsetErr=$loginErr=$isErr="";
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
				// check optional fields in member Registration
				if (isset($_POST["aboutMe"])) {
					$aboutMe = test_input($_POST["aboutMe"]);
				}
				if (isset($_POST["socialLinks"])) {
					//TODO: sanatize social links
					//1. tokenize input
					//2. Foreach link verify valid url
					//3. if find an Invalid url set error
					//4. reassemble the string
					$socialLinks = $_POST["socialLinks"];
				}
			}
			if (!isset($_POST["btnLogin"])) {
				$password1 = test_input($_POST["password1"]);
				$password2 = test_input($_POST["password2"]);
				if ($password1 !== $password2) {
					$passwordErr = "***Passwords do not match";
				} else if(empty($password1)) {
					$passwordErr = "***Password is required";
				} else {
					$password = $password1;
				}
			}
			if (empty($_POST["timezoneOffset"])) {
				$timezoneOffsetErr = "***Time Zone is required";
			} else {
				$timezoneOffset = $_POST["timezoneOffset"];
			}
			if (isset($_POST["btnLogin"])) {
				//verify login
				$password = test_input($_POST['password']);
				if (empty($password)) {
					$passwordErr = "***Password is required";
				} else {
					$table = $_POST["userType"];
					$sql = "SELECT `firstName`, `lastName`, `".$table."ID` AS 'userID' FROM `$table` WHERE email='$email' AND password='$password'";
					$pdo = $conn->query($sql);
					$result = $pdo->fetch();
					if (!isset($result['firstName'])) {
						$loginErr = "***The email and/or password was incorrect";
					} else {
						//TODO: start session
						echo "User ".$result["firstName"]." ".$result["lastName"]." has been logged in :D\n";
						$_SESSION["userID"] = $result["userID"];
						$_SESSION["userType"] = $table;
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
					//sql insert goes here
					if (isset($_POST["btnRegisterUser"])) {
						$table = "user";
					} else {
						$table = "member";
					}
					if ($table == "user") {
						if ($_POST['subscribe']=="subscribed") {
							$subscribed = "1";
						} else {
							$subscribed = "0";
						}
					} else {
						$memberStart = date("Y-m-d");
						$memberDuration = "180";
						$memberPrice = "9.99";
						$memberFree = "1";
						$memberActive = "1";
					}
					$sql = "INSERT INTO $table
					(
						firstName,
						lastName,
						password,
						email, ";
					if ($table == "user") {
						$sql = $sql."subscribed, ";
					}
					$sql = $sql." timezoneOffset";
					if($table == "member") {
						$sql = $sql.", memberStart, memberDuration, memberPrice, memberFree, memberActive, aboutMe, socialLinks";
					}
					$sql = $sql."
					)
					VALUES
					(
						'$firstName',
						'$lastName',
						'$password',
						'$email', ";
					if ($table == "user") {
						$sql = $sql."'$subscribed', ";
					}
					$sql = $sql."'$timezoneOffset'";
					if ($table == "member") {
						$sql = $sql.", '$memberStart', '$memberDuration', '$memberPrice', '$memberFree', '$memberActive', '$aboutMe', '$socialLinks' ";
					}
					$sql = $sql.");";
					echo $sql; echo "<br>"; //TODO: Delete me - for testing purposes
					try {
						$conn->exec($sql);
					}
					catch(PDOException $e) {
						echo $sql . "<br>" . $e->getMessage();
					}
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
				<option value="" disabled>Choose your timezone</option>
				<option value="nut">nut -11:00</option>
				<option value="sst">sst -11:00</option>
				<option value="ckhst">ckhst -10:00</option>
				<option value="ckt">ckt -10:00</option>
				<option value="hdt">hdt -10:00</option>
				<option value="hst">hst -10:00</option>
				<option value="taht">taht -10:00</option>
				<option value="mart">mart -09:30</option>
				<option value="bdt">bdt -09:00</option>
				<option value="gamt">gamt -09:00</option>
				<option value="hadt">hadt -09:00</option>
				<option value="hast">hast -09:00</option>
				<option value="ahdt">ahdt -08:00</option>
				<option value="ahst">ahst -08:00</option>
				<option value="akdt">akdt -08:00</option>
				<option value="akst">akst -08:00</option>
				<option value="capt">capt -08:00</option>
				<option value="cat">cat -08:00</option>
				<option value="cawt">cawt -08:00</option>
				<option value="pnt">pnt -08:00</option>
				<option value="yst">yst -08:00</option>
				<option value="pdt">pdt -07:00</option>
				<option value="ppt">ppt -07:00</option>
				<option value="pst">pst -07:00</option>
				<option value="pwt">pwt -07:00</option>
				<option value="yddt">yddt -07:00</option>
				<option value="ydt">ydt -07:00</option>
				<option value="ypt">ypt -07:00</option>
				<option value="ywt">ywt -07:00</option>
				<option value="chdt">chdt -06:00</option>
				<option value="galt">galt -06:00</option>
				<option value="mddt">mddt -06:00</option>
				<option value="mdt">mdt -06:00</option>
				<option value="mpt">mpt -06:00</option>
				<option value="mst">mst -06:00</option>
				<option value="mwt">mwt -06:00</option>
				<option value="pddt">pddt -06:00</option>
				<option value="sjmt">sjmt -06:00</option>
				<option value="acst">acst -05:00</option>
				<option value="act">act -05:00</option>
				<option value="cddt">cddt -05:00</option>
				<option value="cdt">cdt -05:00</option>
				<option value="cost">cost -05:00</option>
				<option value="cot">cot -05:00</option>
				<option value="cpt">cpt -05:00</option>
				<option value="cst">cst -05:00</option>
				<option value="cwt">cwt -05:00</option>
				<option value="easst">easst -05:00</option>
				<option value="east">east -05:00</option>
				<option value="ect">ect -05:00</option>
				<option value="emt">emt -05:00</option>
				<option value="pest">pest -05:00</option>
				<option value="pet">pet -05:00</option>
				<option value="ppmt">ppmt -05:00</option>
				<option value="qmt">qmt -05:00</option>
				<option value="ant">ant -04:00</option>
				<option value="bmt">bmt -04:00</option>
				<option value="bost">bost -04:00</option>
				<option value="bot">bot -04:00</option>
				<option value="eddt">eddt -04:00</option>
				<option value="edt">edt -04:00</option>
				<option value="ehdt">ehdt -04:00</option>
				<option value="ept">ept -04:00</option>
				<option value="est" selected>est -04:00</option>
				<option value="ewt">ewt -04:00</option>
				<option value="ffmt">ffmt -04:00</option>
				<option value="gbgt">gbgt -04:00</option>
				<option value="gyt">gyt -04:00</option>
				<option value="hmt">hmt -04:00</option>
				<option value="sdmt">sdmt -04:00</option>
				<option value="vet">vet -04:00</option>
				<option value="addt">addt -03:00</option>
				<option value="adt">adt -03:00</option>
				<option value="apt">apt -03:00</option>
				<option value="arst">arst -03:00</option>
				<option value="art">art -03:00</option>
				<option value="awt">awt -03:00</option>
				<option value="clst">clst -03:00</option>
				<option value="clt">clt -03:00</option>
				<option value="cmt">cmt -03:00</option>
				<option value="fkst">fkst -03:00</option>
				<option value="fkt">fkt -03:00</option>
				<option value="gft">gft -03:00</option>
				<option value="negt">negt -03:00</option>
				<option value="pmt">pmt -03:00</option>
				<option value="pyst">pyst -03:00</option>
				<option value="pyt">pyt -03:00</option>
				<option value="rott">rott -03:00</option>
				<option value="smt">smt -03:00</option>
				<option value="srt">srt -03:00</option>
				<option value="uyhst">uyhst -03:00</option>
				<option value="uyst">uyst -03:00</option>
				<option value="uyt">uyt -03:00</option>
				<option value="warst">warst -03:00</option>
				<option value="wart">wart -03:00</option>
				<option value="wgst">wgst -03:00</option>
				<option value="wgt">wgt -03:00</option>
				<option value="nddt">nddt -02:30</option>
				<option value="ndt">ndt -02:30</option>
				<option value="npt">npt -02:30</option>
				<option value="nst">nst -02:30</option>
				<option value="nwt">nwt -02:30</option>
				<option value="brst">brst -02:00</option>
				<option value="brt">brt -02:00</option>
				<option value="fnst">fnst -02:00</option>
				<option value="fnt">fnt -02:00</option>
				<option value="pmdt">pmdt -02:00</option>
				<option value="pmst">pmst -02:00</option>
				<option value="azomt">azomt -01:00</option>
				<option value="azost">azost -01:00</option>
				<option value="azot">azot -01:00</option>
				<option value="cgst">cgst -01:00</option>
				<option value="cgt">cgt -01:00</option>
				<option value="cvst">cvst -01:00</option>
				<option value="cvt">cvt -01:00</option>
				<option value="egst">egst -01:00</option>
				<option value="egt">egt -01:00</option>
				<option value="bdst">bdst +00:00</option>
				<option value="bst">bst +00:00</option>
				<option value="cant">cant +00:00</option>
				<option value="dmt">dmt +00:00</option>
				<option value="fmt">fmt +00:00</option>
				<option value="ghst">ghst +00:00</option>
				<option value="gmt">gmt +00:00</option>
				<option value="isst">isst +00:00</option>
				<option value="lrt">lrt +00:00</option>
				<option value="madmt">madmt +00:00</option>
				<option value="madst">madst +00:00</option>
				<option value="madt">madt +00:00</option>
				<option value="uct">uct +00:00</option>
				<option value="utc">utc +00:00</option>
				<option value="wemt">wemt +00:00</option>
				<option value="cemt">cemt +01:00</option>
				<option value="cest">cest +01:00</option>
				<option value="cet">cet +01:00</option>
				<option value="nest">nest +01:00</option>
				<option value="net">net +01:00</option>
				<option value="wat">wat +01:00</option>
				<option value="west">west +01:00</option>
				<option value="wet">wet +01:00</option>
				<option value="cut">cut +02:00</option>
				<option value="eest">eest +02:00</option>
				<option value="eet">eet +02:00</option>
				<option value="fet">fet +02:00</option>
				<option value="iddt">iddt +02:00</option>
				<option value="idt">idt +02:00</option>
				<option value="ist">ist +02:00</option>
				<option value="jmt">jmt +02:00</option>
				<option value="kmt">kmt +02:00</option>
				<option value="lst">lst +02:00</option>
				<option value="rmt">rmt +02:00</option>
				<option value="sast">sast +02:00</option>
				<option value="swat">swat +02:00</option>
				<option value="trst">trst +02:00</option>
				<option value="trt">trt +02:00</option>
				<option value="wast">wast +02:00</option>
				<option value="wmt">wmt +02:00</option>
				<option value="ast">ast +03:00</option>
				<option value="beat">beat +03:00</option>
				<option value="beaut">beaut +03:00</option>
				<option value="eat">eat +03:00</option>
				<option value="mdst">mdst +03:00</option>
				<option value="mmt">mmt +03:00</option>
				<option value="msd">msd +03:00</option>
				<option value="msk">msk +03:00</option>
				<option value="msm">msm +03:00</option>
				<option value="stat">stat +03:00</option>
				<option value="syot">syot +03:00</option>
				<option value="tsat">tsat +03:00</option>
				<option value="volst">volst +03:00</option>
				<option value="volt">volt +03:00</option>
				<option value="irdt">irdt +03:30</option>
				<option value="irst">irst +03:30</option>
				<option value="tmt">tmt +03:30</option>
				<option value="amst">amst +04:00</option>
				<option value="amt">amt +04:00</option>
				<option value="azst">azst +04:00</option>
				<option value="azt">azt +04:00</option>
				<option value="bakst">bakst +04:00</option>
				<option value="bakt">bakt +04:00</option>
				<option value="gest">gest +04:00</option>
				<option value="get">get +04:00</option>
				<option value="gst">gst +04:00</option>
				<option value="kuyst">kuyst +04:00</option>
				<option value="kuyt">kuyt +04:00</option>
				<option value="must">must +04:00</option>
				<option value="mut">mut +04:00</option>
				<option value="ret">ret +04:00</option>
				<option value="sct">sct +04:00</option>
				<option value="tbist">tbist +04:00</option>
				<option value="tbit">tbit +04:00</option>
				<option value="tbmt">tbmt +04:00</option>
				<option value="yerst">yerst +04:00</option>
				<option value="yert">yert +04:00</option>
				<option value="aft">aft +04:30</option>
				<option value="aktst">aktst +05:00</option>
				<option value="aktt">aktt +05:00</option>
				<option value="aqtst">aqtst +05:00</option>
				<option value="aqtt">aqtt +05:00</option>
				<option value="ashst">ashst +05:00</option>
				<option value="asht">asht +05:00</option>
				<option value="dusst">dusst +05:00</option>
				<option value="dust">dust +05:00</option>
				<option value="fort">fort +05:00</option>
				<option value="kart">kart +05:00</option>
				<option value="mawt">mawt +05:00</option>
				<option value="mvt">mvt +05:00</option>
				<option value="orast">orast +05:00</option>
				<option value="orat">orat +05:00</option>
				<option value="pkst">pkst +05:00</option>
				<option value="pkt">pkt +05:00</option>
				<option value="samst">samst +05:00</option>
				<option value="samt">samt +05:00</option>
				<option value="shest">shest +05:00</option>
				<option value="shet">shet +05:00</option>
				<option value="svest">svest +05:00</option>
				<option value="svet">svet +05:00</option>
				<option value="tasst">tasst +05:00</option>
				<option value="tast">tast +05:00</option>
				<option value="tft">tft +05:00</option>
				<option value="tjt">tjt +05:00</option>
				<option value="urast">urast +05:00</option>
				<option value="urat">urat +05:00</option>
				<option value="uzst">uzst +05:00</option>
				<option value="uzt">uzt +05:00</option>
				<option value="yekst">yekst +05:00</option>
				<option value="yekt">yekt +05:00</option>
				<option value="burt">burt +05:30</option>
				<option value="ihst">ihst +05:30</option>
				<option value="lkt">lkt +05:30</option>
				<option value="almst">almst +06:00</option>
				<option value="almt">almt +06:00</option>
				<option value="btt">btt +06:00</option>
				<option value="dact">dact +06:00</option>
				<option value="frust">frust +06:00</option>
				<option value="frut">frut +06:00</option>
				<option value="iot">iot +06:00</option>
				<option value="kgst">kgst +06:00</option>
				<option value="kgt">kgt +06:00</option>
				<option value="kizst">kizst +06:00</option>
				<option value="kizt">kizt +06:00</option>
				<option value="novst">novst +06:00</option>
				<option value="novt">novt +06:00</option>
				<option value="omsst">omsst +06:00</option>
				<option value="omst">omst +06:00</option>
				<option value="qyzst">qyzst +06:00</option>
				<option value="qyzt">qyzt +06:00</option>
				<option value="vost">vost +06:00</option>
				<option value="xjt">xjt +06:00</option>
				<option value="cct">cct +06:30</option>
				<option value="cxt">cxt +07:00</option>
				<option value="davt">davt +07:00</option>
				<option value="hovst">hovst +07:00</option>
				<option value="hovt">hovt +07:00</option>
				<option value="ict">ict +07:00</option>
				<option value="javt">javt +07:00</option>
				<option value="krast">krast +07:00</option>
				<option value="krat">krat +07:00</option>
				<option value="plmt">plmt +07:00</option>
				<option value="wib">wib +07:00</option>
				<option value="awdt">awdt +08:00</option>
				<option value="awst">awst +08:00</option>
				<option value="bnt">bnt +08:00</option>
				<option value="bortst">bortst +08:00</option>
				<option value="bort">bort +08:00</option>
				<option value="chost">chost +08:00</option>
				<option value="chot">chot +08:00</option>
				<option value="hkst">hkst +08:00</option>
				<option value="hkt">hkt +08:00</option>
				<option value="imt">imt +08:00</option>
				<option value="irkst">irkst +08:00</option>
				<option value="irkt">irkt +08:00</option>
				<option value="jwst">jwst +08:00</option>
				<option value="malst">malst +08:00</option>
				<option value="malt">malt +08:00</option>
				<option value="most">most +08:00</option>
				<option value="mot">mot +08:00</option>
				<option value="myt">myt +08:00</option>
				<option value="phst">phst +08:00</option>
				<option value="pht">pht +08:00</option>
				<option value="sgt">sgt +08:00</option>
				<option value="ulast">ulast +08:00</option>
				<option value="ulat">ulat +08:00</option>
				<option value="jcst">jcst +08:30</option>
				<option value="acwdt">acwdt +08:45</option>
				<option value="acwst">acwst +08:45</option>
				<option value="jdt">jdt +09:00</option>
				<option value="jst">jst +09:00</option>
				<option value="kdt">kdt +09:00</option>
				<option value="kst">kst +09:00</option>
				<option value="tlt">tlt +09:00</option>
				<option value="wita">wita +09:00</option>
				<option value="wit">wit +09:00</option>
				<option value="yakst">yakst +09:00</option>
				<option value="yakt">yakt +09:00</option>
				<option value="chut">chut +10:00</option>
				<option value="chst">chst +10:00</option>
				<option value="ddut">ddut +10:00</option>
				<option value="vlast">vlast +10:00</option>
				<option value="vlat">vlat +10:00</option>
				<option value="acdt">acdt +10:30</option>
				<option value="cast">cast +10:30</option>
				<option value="aedt">aedt +11:00</option>
				<option value="aest">aest +11:00</option>
				<option value="kost">kost +11:00</option>
				<option value="lhdt">lhdt +11:00</option>
				<option value="lhst">lhst +11:00</option>
				<option value="magst">magst +11:00</option>
				<option value="magt">magt +11:00</option>
				<option value="mist">mist +11:00</option>
				<option value="ncst">ncst +11:00</option>
				<option value="nct">nct +11:00</option>
				<option value="nft">nft +11:00</option>
				<option value="nmt">nmt +11:00</option>
				<option value="pgt">pgt +11:00</option>
				<option value="pont">pont +11:00</option>
				<option value="sakst">sakst +11:00</option>
				<option value="sakt">sakt +11:00</option>
				<option value="sbt">sbt +11:00</option>
				<option value="sret">sret +11:00</option>
				<option value="vust">vust +11:00</option>
				<option value="vut">vut +11:00</option>
				<option value="anast">anast +12:00</option>
				<option value="anat">anat +12:00</option>
				<option value="fjst">fjst +12:00</option>
				<option value="fjt">fjt +12:00</option>
				<option value="gilt">gilt +12:00</option>
				<option value="kwat">kwat +12:00</option>
				<option value="mht">mht +12:00</option>
				<option value="nrt">nrt +12:00</option>
				<option value="petst">petst +12:00</option>
				<option value="pett">pett +12:00</option>
				<option value="tvt">tvt +12:00</option>
				<option value="wakt">wakt +12:00</option>
				<option value="wft">wft +12:00</option>
				<option value="nzdt">nzdt +13:00</option>
				<option value="nzmt">nzmt +13:00</option>
				<option value="nzst">nzst +13:00</option>
				<option value="phot">phot +13:00</option>
				<option value="tkt">tkt +13:00</option>
				<option value="tost">tost +13:00</option>
				<option value="tot">tot +13:00</option>
				<option value="chadt">chadt +13:45</option>
				<option value="chast">chast +13:45</option>
				<option value="lint">lint +14:00</option>
				<option value="sdt">sdt +14:00</option>
				<option value="wsdt">wsdt +14:00</option>
				<option value="wsst">wsst +14:00</option>
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
				<option value="" disabled>Choose your timezone</option>
				<option value="" disabled>Choose your timezone</option>
				<option value="nut">nut -11:00</option>
				<option value="sst">sst -11:00</option>
				<option value="ckhst">ckhst -10:00</option>
				<option value="ckt">ckt -10:00</option>
				<option value="hdt">hdt -10:00</option>
				<option value="hst">hst -10:00</option>
				<option value="taht">taht -10:00</option>
				<option value="mart">mart -09:30</option>
				<option value="bdt">bdt -09:00</option>
				<option value="gamt">gamt -09:00</option>
				<option value="hadt">hadt -09:00</option>
				<option value="hast">hast -09:00</option>
				<option value="ahdt">ahdt -08:00</option>
				<option value="ahst">ahst -08:00</option>
				<option value="akdt">akdt -08:00</option>
				<option value="akst">akst -08:00</option>
				<option value="capt">capt -08:00</option>
				<option value="cat">cat -08:00</option>
				<option value="cawt">cawt -08:00</option>
				<option value="pnt">pnt -08:00</option>
				<option value="yst">yst -08:00</option>
				<option value="pdt">pdt -07:00</option>
				<option value="ppt">ppt -07:00</option>
				<option value="pst">pst -07:00</option>
				<option value="pwt">pwt -07:00</option>
				<option value="yddt">yddt -07:00</option>
				<option value="ydt">ydt -07:00</option>
				<option value="ypt">ypt -07:00</option>
				<option value="ywt">ywt -07:00</option>
				<option value="chdt">chdt -06:00</option>
				<option value="galt">galt -06:00</option>
				<option value="mddt">mddt -06:00</option>
				<option value="mdt">mdt -06:00</option>
				<option value="mpt">mpt -06:00</option>
				<option value="mst">mst -06:00</option>
				<option value="mwt">mwt -06:00</option>
				<option value="pddt">pddt -06:00</option>
				<option value="sjmt">sjmt -06:00</option>
				<option value="acst">acst -05:00</option>
				<option value="act">act -05:00</option>
				<option value="cddt">cddt -05:00</option>
				<option value="cdt">cdt -05:00</option>
				<option value="cost">cost -05:00</option>
				<option value="cot">cot -05:00</option>
				<option value="cpt">cpt -05:00</option>
				<option value="cst">cst -05:00</option>
				<option value="cwt">cwt -05:00</option>
				<option value="easst">easst -05:00</option>
				<option value="east">east -05:00</option>
				<option value="ect">ect -05:00</option>
				<option value="emt">emt -05:00</option>
				<option value="pest">pest -05:00</option>
				<option value="pet">pet -05:00</option>
				<option value="ppmt">ppmt -05:00</option>
				<option value="qmt">qmt -05:00</option>
				<option value="ant">ant -04:00</option>
				<option value="bmt">bmt -04:00</option>
				<option value="bost">bost -04:00</option>
				<option value="bot">bot -04:00</option>
				<option value="eddt">eddt -04:00</option>
				<option value="edt">edt -04:00</option>
				<option value="ehdt">ehdt -04:00</option>
				<option value="ept">ept -04:00</option>
				<option value="est" selected>est -04:00</option>
				<option value="ewt">ewt -04:00</option>
				<option value="ffmt">ffmt -04:00</option>
				<option value="gbgt">gbgt -04:00</option>
				<option value="gyt">gyt -04:00</option>
				<option value="hmt">hmt -04:00</option>
				<option value="sdmt">sdmt -04:00</option>
				<option value="vet">vet -04:00</option>
				<option value="addt">addt -03:00</option>
				<option value="adt">adt -03:00</option>
				<option value="apt">apt -03:00</option>
				<option value="arst">arst -03:00</option>
				<option value="art">art -03:00</option>
				<option value="awt">awt -03:00</option>
				<option value="clst">clst -03:00</option>
				<option value="clt">clt -03:00</option>
				<option value="cmt">cmt -03:00</option>
				<option value="fkst">fkst -03:00</option>
				<option value="fkt">fkt -03:00</option>
				<option value="gft">gft -03:00</option>
				<option value="negt">negt -03:00</option>
				<option value="pmt">pmt -03:00</option>
				<option value="pyst">pyst -03:00</option>
				<option value="pyt">pyt -03:00</option>
				<option value="rott">rott -03:00</option>
				<option value="smt">smt -03:00</option>
				<option value="srt">srt -03:00</option>
				<option value="uyhst">uyhst -03:00</option>
				<option value="uyst">uyst -03:00</option>
				<option value="uyt">uyt -03:00</option>
				<option value="warst">warst -03:00</option>
				<option value="wart">wart -03:00</option>
				<option value="wgst">wgst -03:00</option>
				<option value="wgt">wgt -03:00</option>
				<option value="nddt">nddt -02:30</option>
				<option value="ndt">ndt -02:30</option>
				<option value="npt">npt -02:30</option>
				<option value="nst">nst -02:30</option>
				<option value="nwt">nwt -02:30</option>
				<option value="brst">brst -02:00</option>
				<option value="brt">brt -02:00</option>
				<option value="fnst">fnst -02:00</option>
				<option value="fnt">fnt -02:00</option>
				<option value="pmdt">pmdt -02:00</option>
				<option value="pmst">pmst -02:00</option>
				<option value="azomt">azomt -01:00</option>
				<option value="azost">azost -01:00</option>
				<option value="azot">azot -01:00</option>
				<option value="cgst">cgst -01:00</option>
				<option value="cgt">cgt -01:00</option>
				<option value="cvst">cvst -01:00</option>
				<option value="cvt">cvt -01:00</option>
				<option value="egst">egst -01:00</option>
				<option value="egt">egt -01:00</option>
				<option value="bdst">bdst +00:00</option>
				<option value="bst">bst +00:00</option>
				<option value="cant">cant +00:00</option>
				<option value="dmt">dmt +00:00</option>
				<option value="fmt">fmt +00:00</option>
				<option value="ghst">ghst +00:00</option>
				<option value="gmt">gmt +00:00</option>
				<option value="isst">isst +00:00</option>
				<option value="lrt">lrt +00:00</option>
				<option value="madmt">madmt +00:00</option>
				<option value="madst">madst +00:00</option>
				<option value="madt">madt +00:00</option>
				<option value="uct">uct +00:00</option>
				<option value="utc">utc +00:00</option>
				<option value="wemt">wemt +00:00</option>
				<option value="cemt">cemt +01:00</option>
				<option value="cest">cest +01:00</option>
				<option value="cet">cet +01:00</option>
				<option value="nest">nest +01:00</option>
				<option value="net">net +01:00</option>
				<option value="wat">wat +01:00</option>
				<option value="west">west +01:00</option>
				<option value="wet">wet +01:00</option>
				<option value="cut">cut +02:00</option>
				<option value="eest">eest +02:00</option>
				<option value="eet">eet +02:00</option>
				<option value="fet">fet +02:00</option>
				<option value="iddt">iddt +02:00</option>
				<option value="idt">idt +02:00</option>
				<option value="ist">ist +02:00</option>
				<option value="jmt">jmt +02:00</option>
				<option value="kmt">kmt +02:00</option>
				<option value="lst">lst +02:00</option>
				<option value="rmt">rmt +02:00</option>
				<option value="sast">sast +02:00</option>
				<option value="swat">swat +02:00</option>
				<option value="trst">trst +02:00</option>
				<option value="trt">trt +02:00</option>
				<option value="wast">wast +02:00</option>
				<option value="wmt">wmt +02:00</option>
				<option value="ast">ast +03:00</option>
				<option value="beat">beat +03:00</option>
				<option value="beaut">beaut +03:00</option>
				<option value="eat">eat +03:00</option>
				<option value="mdst">mdst +03:00</option>
				<option value="mmt">mmt +03:00</option>
				<option value="msd">msd +03:00</option>
				<option value="msk">msk +03:00</option>
				<option value="msm">msm +03:00</option>
				<option value="stat">stat +03:00</option>
				<option value="syot">syot +03:00</option>
				<option value="tsat">tsat +03:00</option>
				<option value="volst">volst +03:00</option>
				<option value="volt">volt +03:00</option>
				<option value="irdt">irdt +03:30</option>
				<option value="irst">irst +03:30</option>
				<option value="tmt">tmt +03:30</option>
				<option value="amst">amst +04:00</option>
				<option value="amt">amt +04:00</option>
				<option value="azst">azst +04:00</option>
				<option value="azt">azt +04:00</option>
				<option value="bakst">bakst +04:00</option>
				<option value="bakt">bakt +04:00</option>
				<option value="gest">gest +04:00</option>
				<option value="get">get +04:00</option>
				<option value="gst">gst +04:00</option>
				<option value="kuyst">kuyst +04:00</option>
				<option value="kuyt">kuyt +04:00</option>
				<option value="must">must +04:00</option>
				<option value="mut">mut +04:00</option>
				<option value="ret">ret +04:00</option>
				<option value="sct">sct +04:00</option>
				<option value="tbist">tbist +04:00</option>
				<option value="tbit">tbit +04:00</option>
				<option value="tbmt">tbmt +04:00</option>
				<option value="yerst">yerst +04:00</option>
				<option value="yert">yert +04:00</option>
				<option value="aft">aft +04:30</option>
				<option value="aktst">aktst +05:00</option>
				<option value="aktt">aktt +05:00</option>
				<option value="aqtst">aqtst +05:00</option>
				<option value="aqtt">aqtt +05:00</option>
				<option value="ashst">ashst +05:00</option>
				<option value="asht">asht +05:00</option>
				<option value="dusst">dusst +05:00</option>
				<option value="dust">dust +05:00</option>
				<option value="fort">fort +05:00</option>
				<option value="kart">kart +05:00</option>
				<option value="mawt">mawt +05:00</option>
				<option value="mvt">mvt +05:00</option>
				<option value="orast">orast +05:00</option>
				<option value="orat">orat +05:00</option>
				<option value="pkst">pkst +05:00</option>
				<option value="pkt">pkt +05:00</option>
				<option value="samst">samst +05:00</option>
				<option value="samt">samt +05:00</option>
				<option value="shest">shest +05:00</option>
				<option value="shet">shet +05:00</option>
				<option value="svest">svest +05:00</option>
				<option value="svet">svet +05:00</option>
				<option value="tasst">tasst +05:00</option>
				<option value="tast">tast +05:00</option>
				<option value="tft">tft +05:00</option>
				<option value="tjt">tjt +05:00</option>
				<option value="urast">urast +05:00</option>
				<option value="urat">urat +05:00</option>
				<option value="uzst">uzst +05:00</option>
				<option value="uzt">uzt +05:00</option>
				<option value="yekst">yekst +05:00</option>
				<option value="yekt">yekt +05:00</option>
				<option value="burt">burt +05:30</option>
				<option value="ihst">ihst +05:30</option>
				<option value="lkt">lkt +05:30</option>
				<option value="almst">almst +06:00</option>
				<option value="almt">almt +06:00</option>
				<option value="btt">btt +06:00</option>
				<option value="dact">dact +06:00</option>
				<option value="frust">frust +06:00</option>
				<option value="frut">frut +06:00</option>
				<option value="iot">iot +06:00</option>
				<option value="kgst">kgst +06:00</option>
				<option value="kgt">kgt +06:00</option>
				<option value="kizst">kizst +06:00</option>
				<option value="kizt">kizt +06:00</option>
				<option value="novst">novst +06:00</option>
				<option value="novt">novt +06:00</option>
				<option value="omsst">omsst +06:00</option>
				<option value="omst">omst +06:00</option>
				<option value="qyzst">qyzst +06:00</option>
				<option value="qyzt">qyzt +06:00</option>
				<option value="vost">vost +06:00</option>
				<option value="xjt">xjt +06:00</option>
				<option value="cct">cct +06:30</option>
				<option value="cxt">cxt +07:00</option>
				<option value="davt">davt +07:00</option>
				<option value="hovst">hovst +07:00</option>
				<option value="hovt">hovt +07:00</option>
				<option value="ict">ict +07:00</option>
				<option value="javt">javt +07:00</option>
				<option value="krast">krast +07:00</option>
				<option value="krat">krat +07:00</option>
				<option value="plmt">plmt +07:00</option>
				<option value="wib">wib +07:00</option>
				<option value="awdt">awdt +08:00</option>
				<option value="awst">awst +08:00</option>
				<option value="bnt">bnt +08:00</option>
				<option value="bortst">bortst +08:00</option>
				<option value="bort">bort +08:00</option>
				<option value="chost">chost +08:00</option>
				<option value="chot">chot +08:00</option>
				<option value="hkst">hkst +08:00</option>
				<option value="hkt">hkt +08:00</option>
				<option value="imt">imt +08:00</option>
				<option value="irkst">irkst +08:00</option>
				<option value="irkt">irkt +08:00</option>
				<option value="jwst">jwst +08:00</option>
				<option value="malst">malst +08:00</option>
				<option value="malt">malt +08:00</option>
				<option value="most">most +08:00</option>
				<option value="mot">mot +08:00</option>
				<option value="myt">myt +08:00</option>
				<option value="phst">phst +08:00</option>
				<option value="pht">pht +08:00</option>
				<option value="sgt">sgt +08:00</option>
				<option value="ulast">ulast +08:00</option>
				<option value="ulat">ulat +08:00</option>
				<option value="jcst">jcst +08:30</option>
				<option value="acwdt">acwdt +08:45</option>
				<option value="acwst">acwst +08:45</option>
				<option value="jdt">jdt +09:00</option>
				<option value="jst">jst +09:00</option>
				<option value="kdt">kdt +09:00</option>
				<option value="kst">kst +09:00</option>
				<option value="tlt">tlt +09:00</option>
				<option value="wita">wita +09:00</option>
				<option value="wit">wit +09:00</option>
				<option value="yakst">yakst +09:00</option>
				<option value="yakt">yakt +09:00</option>
				<option value="chut">chut +10:00</option>
				<option value="chst">chst +10:00</option>
				<option value="ddut">ddut +10:00</option>
				<option value="vlast">vlast +10:00</option>
				<option value="vlat">vlat +10:00</option>
				<option value="acdt">acdt +10:30</option>
				<option value="cast">cast +10:30</option>
				<option value="aedt">aedt +11:00</option>
				<option value="aest">aest +11:00</option>
				<option value="kost">kost +11:00</option>
				<option value="lhdt">lhdt +11:00</option>
				<option value="lhst">lhst +11:00</option>
				<option value="magst">magst +11:00</option>
				<option value="magt">magt +11:00</option>
				<option value="mist">mist +11:00</option>
				<option value="ncst">ncst +11:00</option>
				<option value="nct">nct +11:00</option>
				<option value="nft">nft +11:00</option>
				<option value="nmt">nmt +11:00</option>
				<option value="pgt">pgt +11:00</option>
				<option value="pont">pont +11:00</option>
				<option value="sakst">sakst +11:00</option>
				<option value="sakt">sakt +11:00</option>
				<option value="sbt">sbt +11:00</option>
				<option value="sret">sret +11:00</option>
				<option value="vust">vust +11:00</option>
				<option value="vut">vut +11:00</option>
				<option value="anast">anast +12:00</option>
				<option value="anat">anat +12:00</option>
				<option value="fjst">fjst +12:00</option>
				<option value="fjt">fjt +12:00</option>
				<option value="gilt">gilt +12:00</option>
				<option value="kwat">kwat +12:00</option>
				<option value="mht">mht +12:00</option>
				<option value="nrt">nrt +12:00</option>
				<option value="petst">petst +12:00</option>
				<option value="pett">pett +12:00</option>
				<option value="tvt">tvt +12:00</option>
				<option value="wakt">wakt +12:00</option>
				<option value="wft">wft +12:00</option>
				<option value="nzdt">nzdt +13:00</option>
				<option value="nzmt">nzmt +13:00</option>
				<option value="nzst">nzst +13:00</option>
				<option value="phot">phot +13:00</option>
				<option value="tkt">tkt +13:00</option>
				<option value="tost">tost +13:00</option>
				<option value="tot">tot +13:00</option>
				<option value="chadt">chadt +13:45</option>
				<option value="chast">chast +13:45</option>
				<option value="lint">lint +14:00</option>
				<option value="sdt">sdt +14:00</option>
				<option value="wsdt">wsdt +14:00</option>
				<option value="wsst">wsst +14:00</option>
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