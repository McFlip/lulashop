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
<p> This is where you shop for clothes :D yay! </p>
<?php
	//sanatize input
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$pattern = test_input($_POST["pattern"]);
	}
?>

<nav class="w3-sidenav w3-light-grey w3-card-2" style="width:160px;">
	<form class="w3-container" method="post" action="shop.php">
		<input class="w3-radio" type="radio" name="colorFilter" value="all"
		<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($_POST['colorFilter']=="all") {
				echo "checked";
			}
		} else {
			echo "checked";
		}
		?>>
		<label class="w3-label">All Colors</label><br>
		<input class="w3-radio" type="radio" name="colorFilter" value="filter"
		<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($_POST['colorFilter']=="filter") {
				echo "checked";
			}
		}
		?>>
		<label class="w3-label">Filter Colors</label>
		<div class="w3-accordion">
			<a onclick="myAccFunc('colorAcc')" href="#">
				Select Colors <i class="fa fa-caret-down"></i>
			</a>
			<div id="colorAcc" class="w3-accordion-content w3-white w3-card-4">
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
		<input class="w3-radio" type="radio" name="styleFilter" value="all"
		<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($_POST['styleFilter']=="all") {
				echo "checked";
			}
		} else {
			echo "checked";
		}
		?>>
		<label class="w3-label">All Styles</label><br>
		<input class="w3-radio" type="radio" name="styleFilter" value="filter"
		<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($_POST['styleFilter']=="filter") {
				echo "checked";
			}
		}
		?>>
		<label class="w3-label">Filter Styles</label>
		<div class="w3-accordion">
			<a onclick="myAccFunc('styleAcc')" href="#">
				Select Styles<i class="fa fa-caret-down"></i>
			</a>
			<div id="styleAcc" class="w3-accordion-content w3-white w3-card-4">
			<input class="w3-check" type="checkbox" name="adeline" value="adeline"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['adeline'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">adeline</label><br>
			<input class="w3-check" type="checkbox" name="amelia" value="amelia"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['amelia'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">amelia</label><br>
			<input class="w3-check" type="checkbox" name="ana" value="ana"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['ana'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">ana</label><br>
			<input class="w3-check" type="checkbox" name="azure" value="azure"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['azure'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">azure</label><br>
			<input class="w3-check" type="checkbox" name="bianka" value="bianka"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['bianka'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">bianka</label><br>
			<input class="w3-check" type="checkbox" name="carly" value="carly"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['carly'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">carly</label><br>
			<input class="w3-check" type="checkbox" name="cassie" value="cassie"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['cassie'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">cassie</label><br>
			<input class="w3-check" type="checkbox" name="classict" value="classict"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['classict'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">classict</label><br>
			<input class="w3-check" type="checkbox" name="gracie" value="gracie"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['gracie'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">gracie</label><br>
			<input class="w3-check" type="checkbox" name="irma" value="irma"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['irma'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">irma</label><br>
			<input class="w3-check" type="checkbox" name="jade" value="jade"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['jade'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">jade</label><br>
			<input class="w3-check" type="checkbox" name="jill" value="jill"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['jill'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">jill</label><br>
			<input class="w3-check" type="checkbox" name="jordan" value="jordan"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['jordan'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">jordan</label><br>
			<input class="w3-check" type="checkbox" name="joy" value="joy"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['joy'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">joy</label><br>
			<input class="w3-check" type="checkbox" name="julia" value="julia"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['julia'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">julia</label><br>
			<input class="w3-check" type="checkbox" name="kidsazure" value="kidsazure"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['kidsazure'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">kidsazure</label><br>
			<input class="w3-check" type="checkbox" name="kidsleggings" value="kidsleggings"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['kidsleggings'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">kidsleggings</label><br>
			<input class="w3-check" type="checkbox" name="leggings" value="leggings"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['leggings'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">leggings</label><br>
			<input class="w3-check" type="checkbox" name="lindsay" value="lindsay"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['lindsay'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">lindsay</label><br>
			<input class="w3-check" type="checkbox" name="lola" value="lola"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['lola'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">lola</label><br>
			<input class="w3-check" type="checkbox" name="lucy" value="lucy"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['lucy'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">lucy</label><br>
			<input class="w3-check" type="checkbox" name="madison" value="madison"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['madison'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">madison</label><br>
			<input class="w3-check" type="checkbox" name="mae" value="mae"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['mae'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">mae</label><br>
			<input class="w3-check" type="checkbox" name="mark" value="mark"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['mark'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">mark</label><br>
			<input class="w3-check" type="checkbox" name="maxi" value="maxi"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['maxi'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">maxi</label><br>
			<input class="w3-check" type="checkbox" name="monroe" value="monroe"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['monroe'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">monroe</label><br>
			<input class="w3-check" type="checkbox" name="nicole" value="nicole"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['nicole'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">nicole</label><br>
			<input class="w3-check" type="checkbox" name="patrick" value="patrick"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['patrick'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">patrick</label><br>
			<input class="w3-check" type="checkbox" name="perfectt" value="perfectt"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['perfectt'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">perfectt</label><br>
			<input class="w3-check" type="checkbox" name="randy" value="randy"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['randy'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">randy</label><br>
			<input class="w3-check" type="checkbox" name="sarah" value="sarah"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['sarah'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">sarah</label><br>
			<input class="w3-check" type="checkbox" name="sloan" value="sloan"
			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (isset($_POST['sloan'])) {
					echo "checked";
				}
			}
			?>>
			<label class="w3-label">sloan</label><br>
			</div>
		</div>
		<input class="w3-radio" type="radio" name="sizeFilter" value="all"
		<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($_POST['sizeFilter']=="all") {
				echo "checked";
			}
		} else {
			echo "checked";
		}
		?>>
		<label class="w3-label">All Sizes</label><br>
		<input class="w3-radio" type="radio" name="sizeFilter" value="filter"
		<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($_POST['sizeFilter']=="filter") {
				echo "checked";
			}
		}
		?>>
		<label class="w3-label">Filter Sizes</label>
		<div class="w3-accordion">
			<a onclick="myAccFunc('sizeAcc')" href="#">
				Select Sizes<i class="fa fa-caret-down"></i>
			</a>
			<div id="sizeAcc" class="w3-accordion-content w3-white w3-card-4">
				<input class="w3-check" type="checkbox" name="2t" value="2t"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['2t'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">2t</label><br>
				<input class="w3-check" type="checkbox" name="4" value="4"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['4'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">4</label><br>
				<input class="w3-check" type="checkbox" name="6" value="6"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['6'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">6</label><br>
				<input class="w3-check" type="checkbox" name="8" value="8"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['8'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">8</label><br>
				<input class="w3-check" type="checkbox" name="10" value="10"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['10'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">10</label><br>
				<input class="w3-check" type="checkbox" name="12" value="12"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['12'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">12</label><br>
				<input class="w3-check" type="checkbox" name="xxs" value="xxs"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['xxs'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">xxs</label><br>
				<input class="w3-check" type="checkbox" name="xs" value="xs"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['xs'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">xs</label><br>
				<input class="w3-check" type="checkbox" name="s" value="s"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['s'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">s</label><br>
				<input class="w3-check" type="checkbox" name="m" value="m"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['m'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">m</label><br>
				<input class="w3-check" type="checkbox" name="l" value="l"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['l'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">l</label><br>
				<input class="w3-check" type="checkbox" name="xl" value="xl"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['xl'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">xl</label><br>
				<input class="w3-check" type="checkbox" name="2xl" value="2xl"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['2xl'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">2xl</label><br>
				<input class="w3-check" type="checkbox" name="3xl" value="3xl"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['3xl'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">3xl</label><br>
				<input class="w3-check" type="checkbox" name="tween" value="tween"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['tween'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">tween</label><br>
				<input class="w3-check" type="checkbox" name="one size" value="one size"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['one size'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">one size</label><br>
				<input class="w3-check" type="checkbox" name="tall & curvy" value="tall & curvy"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST['tall & curvy'])) {
						echo "checked";
					}
				}
				?>>
				<label class="w3-label">tall & curvy</label><br>
			</div>
		</div>
		<input class="w3-input w3-border" type="text" name="pattern" placeholder="pattern keywords" <?php if(!empty($pattern)){echo "value=\"".$pattern."\"";} ?> >
		<label class="w3-label">pattern</label>
		<input class="w3-button" type="submit" name="submit" value="Search">
	</form>
<!-- these breaks are to force a scrollbar for the sidenav search menu due to use of accordions. otherwise menu will go off edge of page -->
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

</nav>

<div class="w3-container" style="margin-left:160px">
	<h4>Search Results</h4>
	<?php
// 	TODO: paginate the results
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$sql = "SELECT * FROM `inventory`, `member` WHERE inventory.memberID=member.memberID AND `visible`=1";
		if (isset($_SESSION["userType"])){
			$userType = $_SESSION["userType"];
			if($userType=="member"){
			//user is looking at their own inventory
				$sql .= " AND `inventory`.`memberID`=".$_SESSION["userID"];
			} else {
				$sql .= " AND `quantity` > 0 ";
			}
		} else {
			$sql .= " AND `quantity` > 0 ";
		}
		//filter by color
		if($_POST["colorFilter"]=="filter"){
			$arrColor = array("green","teal","blue","purple","red","pink","flesh","tan","brown","black","lime","yellow","orange","grey","maroon","white");
			$color = "";
			foreach ($arrColor as $c){
				if (isset($_POST["$c"])){
					if(empty($color)){
						$color = "%".$c."%";
						$sql = $sql." AND (`color` LIKE '"."$color"."' ";
					} else {
						$color = "%".$c."%";
						$sql = $sql."OR `color` LIKE '"."$color"."' ";
					}
				}
			}
		} else {
			$color = "";
		}
		if(!empty($color)){
			$sql = $sql.")";
		}
		//filter by style
		if($_POST["styleFilter"]=="filter"){
			$arrStyle =  array("adeline","amelia","ana","azure","bianka","carly","cassie","classict","gracie","irma","jade","jill",
												"jordan","joy","julia","kidsazure","kidsleggings","leggings","lindsay","lola","lucy","madison",
												"mae","mark","maxi","monroe","nicole","patrick","perfectt","randy","sarah","sloan");
			$style = "";
			foreach ($arrStyle as $s){
				if (isset($_POST["$s"])){
					if(empty($style)){
						$style = $s;
						$sql = $sql." AND (`category` = '"."$style"."' ";
					} else {
						$style = $s;
						$sql = $sql."OR `category` = '"."$style"."' ";
					}
				}
			}
		} else {
			$style = "";
		}
		if(!empty($style)){
			$sql = $sql.")";
		}
		//filter by size
		if($_POST["sizeFilter"]=="filter"){
			$arrSize = array("2t","4","6","8","10","12","xxs","xs","s","m","l","xl","2xl","3xl","tween","one size","tall & curvy");
			$size = "";
			foreach ($arrSize as $s){
				if (isset($_POST["$s"])){
					if(empty($size)){
						$size = $s;
						$sql = $sql." AND (`size` = '"."$size"."' ";
					} else {
						$size = $s;
						$sql = $sql."OR `size` = '"."$size"."' ";
					}
				}
			}
		} else {
			$size = "";
		}
		if(!empty($size)){
			$sql = $sql.")";
		}
		//filter by pattern
		if(!empty($_POST["pattern"])) {
			$sql = $sql." AND `pattern` LIKE '%".$pattern."%'";
		}
		$sql = $sql.";"; //terminate the sql statement
		echo $sql;  //TODO: delete - for testing purposes
		try {
			$pdo = $conn->query($sql);
		}
		catch(PDOException $e) {
			echo "Query of inventory failed: " . $e->getMessage();
			die();
		}
		echo "<table class=\"w3-table w3-bordered\">";
		echo "<tr class=\"w3-blue\"><th>Style</th><th>Size</th><th>Price</th><th>Consultant</th></tr>";
		while ($result = $pdo->fetch()){
			$sql = "SELECT `picURL` FROM `picture` WHERE `sku`=".$result["sku"].";";
			$pdo2 = $conn->query($sql);
			echo "<tr>";
			while($pic = $pdo2->fetch()){
				echo $pic["picURL"];  //TODO: delete - for testing purposes
				echo "<td><div class=\"w3-card-8\"><img src=\"".$pic["picURL"]."\" width=\"300\" height=\"300\"></div></td>";
			}
			echo "</tr><tr>";
			echo "<td>".$result["category"]."</td><td>".$result["size"]."</td><td>".$result["price"]."</td><td>".$result["firstName"]." ".$result["lastName"]."</td>";
			echo "<td><form method=\"post\" action=\"qa.php\" target=\"qa\">";
			echo "<button type=\"submit\" style=\"font-size:24px\" onclick=\"showqa()\" value=\"q&a\">q&a<i class=\"material-icons\">question_answer</i></button>";
			echo "<input type=\"number\" name=\"sku\" hidden value=\"".$result["sku"]."\">";
			echo "</form></td>";
			echo "<td id=\"addItem\"><form method=\"post\" action=\"add_cart.php\" target=\"qa\">";
			echo "<button style=\"font-size:24px\" onclick=\"showcart()\" type=\"submit\" value=\"ADD ITEM\" name=\"submit\">Add<i class=\"material-icons\">add_shopping_cart</i></button>";
			echo "<input type=\"number\" name=\"sku\" hidden value=\"".$result["sku"]."\">";
			echo "</form></td></tr>";
		}
		echo "</table>";
	}
	?>
<!-- TODO: delete these breaks - for testing purposes	 -->
	<!--<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>-->

<div id="qa" class="w3-modal">
	<div class="w3-modal-content">
		<div class="w3-container">
			<span onclick="document.getElementById('qa').style.display='none'" class="w3-closebtn">&times;</span>
			<iframe name="qa" height="400px" width="100%" src="qa.php">Questions and Answers</iframe>
		</div>
	</div>
</div>
</div>
</div>
<script>
	// accordion function for sidenav search menu
	function myAccFunc(acc) {
		var x = document.getElementById(acc);
		if (x.className.indexOf("w3-show") == -1) {
			x.className += " w3-show";
			x.previousElementSibling.className += " w3-green";
		} else {
			x.className = x.className.replace(" w3-show", "");
			x.previousElementSibling.className =
			        x.previousElementSibling.className.replace(" w3-green", "");
		}
	}
	function showqa(){
		document.getElementById('qa').style.display='block';
	}
	function showcart(){
		document.getElementById('addItem').style.display='none';
		document.getElementById('qa').style.display='block';
	}
</script>
</body>

<footer>
<div class="w3-container" style="margin-left:160px">
<?php include 'foot.php'; ?>
</div>
</footer>
</html>