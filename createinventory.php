<?php
session_start();
if($_SESSION["userType"] != "member"){
	echo "This page is only for sellers.";
	die();
}
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

<body>
<?php
$priceErr=$categoryErr=$quantityErr=$sizeErr=$colorErr=$patternErr="";
$pic1Err=$pic2Err=$pic3Err=$pic4Err=$pic5Err="";
$arrPicErr = array($pic1Err,$pic2Err,$pic3Err,$pic4Err,$pic5Err);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["btnclear"])){
		foreach ($_POST as $x => $y) {
			$_POST["$x"] = null;
		}
	}
	if (empty($_POST["price"])){
		$priceErr="***Price is required";
		$price = 0;
	} else {
		if (($price = filter_var($_POST["price"], FILTER_VALIDATE_INT)) == false) {
			$priceErr="***Price must be an integer";
			$price = 0;
		}
	}
	if (empty($_POST["category"])){
		$categoryErr="***Style category is required";
	} else {
		$category = $_POST["category"];
	}
	if (empty($_POST["quantity"])){
		$quantityErr="***Quantity is required";
		$quantity = 1;
	} else {
		if (($quantity = filter_var($_POST["quantity"], FILTER_VALIDATE_INT)) == false) {
			$quantityErr="***Quantity must be an integer";
			$quantity = 1;
		}
	}
	if (empty($_POST["size"])){
		$sizeErr="***Size is required";
	} else {
		$size = $_POST["size"];
	}
	if (!isset($_POST["green"]) &&
			!isset($_POST["teal"]) &&
			!isset($_POST["blue"]) &&
			!isset($_POST["purple"]) &&
			!isset($_POST["red"]) &&
			!isset($_POST["pink"]) &&
			!isset($_POST["flesh"]) &&
			!isset($_POST["tan"]) &&
			!isset($_POST["brown"]) &&
			!isset($_POST["black"]) &&
			!isset($_POST["lime"]) &&
			!isset($_POST["yellow"]) &&
			!isset($_POST["orange"]) &&
			!isset($_POST["grey"]) &&
			!isset($_POST["maroon"]) &&
			!isset($_POST["white"])){
		$colorErr="***At least 1 color is required";
	} else {
		$color = ""; // csv string
		if(isset($_POST["green"])){$color = $color."green".",";}
		if(isset($_POST["teal"])){$color = $color."teal".",";}
		if(isset($_POST["blue"])){$color = $color."blue".",";}
		if(isset($_POST["purple"])){$color = $color."purple".",";}
		if(isset($_POST["red"])){$color = $color."red".",";}
		if(isset($_POST["pink"])){$color = $color."pink".",";}
		if(isset($_POST["flesh"])){$color = $color."flesh".",";}
		if(isset($_POST["tan"])){$color = $color."tan".",";}
		if(isset($_POST["brown"])){$color = $color."brown".",";}
		if(isset($_POST["black"])){$color = $color."black".",";}
		if(isset($_POST["lime"])){$color = $color."lime".",";}
		if(isset($_POST["yellow"])){$color = $color."yellow".",";}
		if(isset($_POST["orange"])){$color = $color."orange".",";}
		if(isset($_POST["grey"])){$color = $color."grey".",";}
		if(isset($_POST["maroon"])){$color = $color."maroon".",";}
		if(isset($_POST["white"])){$color = $color."white";}
	}
	if (empty($_POST["pattern"])){
		$patternErr="***Pattern is required";
	} else {
		$pattern = test_input($_POST["pattern"]);
	}
	if (!empty($_POST["fitfeel"])){
		$fitfeel = test_input($_POST["fitfeel"]);
	} else {
		$fitfeel = null;
	}
	if (!empty($_POST["thread"])){
		$thread = test_input($_POST["thread"]);
	} else {
		$thread = null;
	}
	if (isset($_POST["visible"])){
		$visible = 1;
	} else {
		$visible = 0;
	}
	$memberID = $_SESSION["userID"];
	// check for errors and prepare sql statement
	$arrErr = array($priceErr,$categoryErr,$quantityErr,$sizeErr,$colorErr,$patternErr);
	$isErr = false;
	foreach ($arrErr as $error) {
		if (!empty($error)) {
			$isErr = true;
		}
	}
	if (!$isErr){
// sql inserts on 2 tables - only insert pic table on Inventory success and upload success
// 	TODO: update item functionality
// 	TODO: come up with a thumbnailing scheme
		$stmt = $conn->prepare("INSERT INTO `inventory`
		(
		  price,
		  quantity,
			category,
			size,
			color,
			fitFeel,
			thread,
			visible,
			pattern,
			memberID
		)
		VALUES
		(
		  :price,
			:quantity,
			:category,
			:size,
			:color,
			:fitfeel,
			:thread,
			:visible,
			:pattern,
			:memberID
		)");
		$stmt->bindParam(':price', $price);
		$stmt->bindParam(':quantity', $quantity);
		$stmt->bindParam(':category', $category);
		$stmt->bindParam(':size', $size);
		$stmt->bindParam(':color', $color);
		$stmt->bindParam(':fitfeel', $fitfeel);
		$stmt->bindParam(':thread', $thread);
		$stmt->bindParam(':visible', $visible);
		$stmt->bindParam(':pattern', $pattern);
		$stmt->bindParam(':memberID', $memberID);
		try {
			$stmt->execute();
		} catch(PDOException $e) {
			echo "Creation of inventory failed: " . $e->getMessage();
			die(); //enforce ref integrity - don't upload pics if you cant attach to SKU
		}
		//Find the SKU of the item we just inserted
		$sku = $conn->lastInsertId();
		$stmt = $conn->prepare("INSERT INTO `picture`
		(
			picURL,
			sku
		)
		VALUES
		(
			:picURL,
			:sku
		)");
		$stmt->bindParam(':picURL', $target_file);
		$stmt->bindParam(':sku', $sku);
		$target_dir = "pics/";
		for($i=1; $i<6;$i++){
			$uploadOk = 1;
			$fileToUpload = "pic"."$i";
			if(empty($_FILES["$fileToUpload"]["tmp_name"])){
				continue;
			}
			//  rename the uploaded file according to the convention sku-[1-5].extension
			$target_file = $target_dir . basename($_FILES["$fileToUpload"]["name"]);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			$name = "$sku"."-"."$i"."."."$imageFileType";  // example "sku"-1.jpg
			$_FILES["$fileToUpload"]["name"] = "$name";
			$target_file = $target_dir . basename($_FILES["$fileToUpload"]["name"]);
			// Check if image file is a actual image or fake image
			$check = getimagesize($_FILES["$fileToUpload"]["tmp_name"]);
			if($check == false) {
				$uploadOk = 0;
				$arrPicErr[$i-1] = "File is not an image.";
			}
			// Check file size  TODO: decide on a limit
			/*if ($_FILES["$fileToUpload"]["size"] > 500000) {
				$uploadOk = 0;
				$arrPicErr[$i-1] = "Sorry, your file is too large.";
			}*/
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif" ) {
				$uploadOk = 0;
				$arrPicErr[$i-1] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file and update picture table
			} else {
				if (move_uploaded_file($_FILES["$fileToUpload"]["tmp_name"], $target_file)) {
					echo "The file ". basename( $_FILES["$fileToUpload"]["name"]). " has been uploaded.";
					//only execute the prepared sql statement on successful upload
					$stmt->execute();
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}
	}
} else {
	$price = 0;
	$quantity = 1;
}
//sanatize input
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<div class="w3-panel w3-blue w3-round-xlarge">
  <p>Create Inventory</p>
</div>
<div class="w3-container w3-card">
	<form class="w3-container" method="post" action="createinventory.php" enctype="multipart/form-data">
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<input class="w3-input w3-border" type="number" name="price" min="0" value="<?php echo "$price"; ?>" required>
				<label class="w3-label w3-validate">Price (enter numbers only)</label><span class="error"><?php echo $priceErr;?></span>
			</div>
			<div class="w3-quarter">
				<select class="w3-select" name="category" required>
				<?php
					if($_SERVER["REQUEST_METHOD"] == "POST"){
						echo "<option value=\"".$category."\" selected>".$category."</option>";
					} else {
						echo "<option value=\"\" disabled selected>Choose Style</option>";
					}
				?>
					<option value="adeline">adeline</option>
					<option value="amelia">amelia</option>
					<option value="ana">ana</option>
					<option value="azure">azure</option>
					<option value="bianka">bianka</option>
					<option value="carly">carly</option>
					<option value="cassie">cassie</option>
					<option value="classict">classic t</option>
					<option value="gracie">gracie</option>
					<option value="irma">irma</option>
					<option value="jade">jade</option>
					<option value="jill">jill</option>
					<option value="jordan">jordan</option>
					<option value="joy">joy</option>
					<option value="julia">julia</option>
					<option value="kidsazure">kids azure</option>
					<option value="kidsleggings">kids leggings</option>
					<option value="leggings">leggings</option>
					<option value="lindsay">lindsay</option>
					<option value="lola">lola</option>
					<option value="lucy">lucy</option>
					<option value="madison">madison</option>
					<option value="mae">mae</option>
					<option value="mark">mark</option>
					<option value="maxi">maxi</option>
					<option value="monroe">monroe</option>
					<option value="nicole">nicole</option>
					<option value="patrick">patrick</option>
					<option value="perfectt">perfect t</option>
					<option value="randy">randy</option>
					<option value="sarah">sarah</option>
					<option value="sloan">sloan</option>
				</select>
				<label class="w3-label w3-validate">Style category</label><?php echo $categoryErr;?>
			</div>
			<div class="w3-quarter">
				<input class="w3-input w3-border" type="number" name="quantity" min="1" value="<?php echo "$quantity"; ?>" required>
				<label class="w3-label w3-validate">Quantity (enter numbers only)</label><span class="error"><?php echo $quantityErr;?></span>
			</div>
			<div class="w3-quarter">
				<select class="w3-select" name="size" required>
				<?php
					if($_SERVER["REQUEST_METHOD"] == "POST"){
						echo "<option value=\"".$size."\" selected>".$size."</option>";
					} else {
						echo "<option value=\"\" disabled selected>Choose Size</option>";
					}
				?>
					<option value="2t">2t</option>
					<option value="4">4</option>
					<option value="6">6</option>
					<option value="8">8</option>
					<option value="10">10</option>
					<option value="12">12</option>
					<option value="xxs">xxs</option>
					<option value="xs">xs</option>
					<option value="s">s</option>
					<option value="m">m</option>
					<option value="l">l</option>
					<option value="xl">xl</option>
					<option value="2xl">2xl</option>
					<option value="3xl">3xl</option>
					<option value="tween">tween</option>
					<option value="onesize">one size</option>
					<option value="tallcurvy">tall & curvy</option>
				</select>
				<label class="w3-label w3-validate">Size</label><span class="error"><?php echo $sizeErr;?></span>
			</div>
		</div>
		<div class="w3-row-padding">
					<div class="w3-rest"><fieldset><legend>Colors<?php echo $colorErr ?></legend>
				<input class="w3-check" type="checkbox" name="green" value="green"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['green'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">green</label>
				<input class="w3-check" type="checkbox" name="teal" value="teal"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['teal'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">teal</label>
				<input class="w3-check" type="checkbox" name="blue" value="blue"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['blue'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">blue</label>
				<input class="w3-check" type="checkbox" name="purple" value="purple"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['purple'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">purple</label>
				<input class="w3-check" type="checkbox" name="red" value="red"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['red'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">red</label>
				<input class="w3-check" type="checkbox" name="pink" value="pink"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['pink'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">pink</label>
				<input class="w3-check" type="checkbox" name="flesh" value="flesh"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['flesh'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">flesh</label>
				<input class="w3-check" type="checkbox" name="tan" value="tan"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['tan'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">tan</label>
				<input class="w3-check" type="checkbox" name="brown" value="brown"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['brown'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">brown</label>
				<input class="w3-check" type="checkbox" name="black" value="black"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['black'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">black</label>
				<input class="w3-check" type="checkbox" name="lime" value="lime"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['lime'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">lime</label>
				<input class="w3-check" type="checkbox" name="yellow" value="yellow"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['yellow'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">yellow</label>
				<input class="w3-check" type="checkbox" name="orange" value="orange"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['orange'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">orange</label>
				<input class="w3-check" type="checkbox" name="grey" value="grey"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['grey'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">grey</label>
				<input class="w3-check" type="checkbox" name="maroon" value="maroon"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['maroon'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">maroon</label>
				<input class="w3-check" type="checkbox" name="white" value="white"
				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				  if (isset($_POST['white'])) {
				    echo "checked";
				  }
				}
				?>>
				<label class="w3-label">white</label>
			</fieldset></div>
			<div class="w3-third">
							<input class="w3-input w3-border" type="text" name="pattern" placeholder="describe the pattern" <?php if(!empty($pattern)){echo "value=\"".$pattern."\"";} ?> required>
				<label class="w3-label w3-validate">Pattern</label><?php echo $patternErr;?>
			</div>
		</div>
		<div class="w3-row-padding">
			<div class="w3-half">
				<input class="w3-input w3-border" type="text" name="fitfeel" placeholder="describe fit and feel (optional)" <?php if(!empty($fitfeel)){echo "value=\"".$fitfeel."\"";} ?> >
				<label class="w3-label w3-validate">Fit & Feel</label>
			</div>
			<div class="w3-half">
				<input class="w3-input w3-border" type="text" name="thread" placeholder="describe the thread content (optional)" <?php if(!empty($thread)){echo "value=\"".$thread."\"";} ?>>
				<label class="w3-label w3-validate">Thread Content</label>
			</div>
		</div>
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<input class="w3-check" type="checkbox" name="visible" value="visible"
				<?php
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						if (isset($_POST['visible'])) {
							echo "checked";
						}
					} else {
						echo "checked";
					}
				?>>
				<label class="w3-label">Visible to Shoppers</label>
			</div>
		</div>
		<fieldset><legend>Upload Pictures - Picture 1 is the main picutre. Upload up to 5 pictures.</legend>
		<div class="w3-row-padding">
			<div class="w3-third">
				<input type="file" name="pic1" id="pic1">
				<label class="w3-label w3-validate">Picture 1</label><span class="error"><?php echo $arrPicErr[0];?></span>
			</div>
			<div class="w3-third">
				<input type="file" name="pic2" id="pic2">
				<label class="w3-label w3-validate">Picture 2</label><span class="error"><?php echo $arrPicErr[1];?></span>
			</div>
			<div class="w3-third">
				<input type="file" name="pic3" id="pic3">
				<label class="w3-label w3-validate">Picture 3</label><span class="error"><?php echo $arrPicErr[2];?></span>
			</div>
		</div>
		<div class="w3-row-padding">
			<div class="w3-third">
				<input type="file" name="pic4" id="pic4">
				<label class="w3-label w3-validate">Picture 4</label><span class="error"><?php echo $arrPicErr[3];?></span>
			</div>
			<div class="w3-third">
				<input type="file" name="pic5" id="pic5">
				<label class="w3-label w3-validate">Picture 5</label><span class="error"><?php echo $arrPicErr[4];?></span>
			</div>
		</div>
		</fieldset>
		<br><br>
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<input class="w3-button" type="submit" name="submit" value="Create">
				<input class="w3-button" type="reset">
				<input class="w3-button" type="submit" name="btnclear" value="Clear Out the Form">
			</div>
		</div>
	</form>
</div>

</body>
<!-- close DB connection -->
<?php $conn = null;?>
<footer>
<?php include 'foot.php'; ?>
</footer>
</html>