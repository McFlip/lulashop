<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>LuLa Shop</title>
	<?php include 'menu.php'; ?>
</head>

<body>
<?php
$priceErr=$categoryErr=$quantityErr=$sizeErr=$colorErr=$patternErr="";
$pic1Err=$pic2Err=$pic3Err=$pic4Err=$pic5Err="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// 	TODO: come up with a naming convention for pics
// 	TODO: redo error msgs
// 	TODO: form info saving and err checks
// 	TODO: reset button
// 	TODO: sql query next sku
// 	TODO: sql inserts on 2 tables
// 	TODO: create seperate update item page
	$target_dir = "pics/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		    if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
	}
	// Check if file already exists
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
}
?>
<div class="w3-panel w3-blue w3-round-xlarge">
  <p>Create Inventory</p>
</div>
<div class="w3-container w3-card">
	<form class="w3-container" method="post" action="createinventory.php" enctype="multipart/form-data">
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<input class="w3-input w3-border" type="number" name="price" min="0" value="0" required>
				<label class="w3-label w3-validate">Price (enter numbers only)</label><span class="error"><?php echo $priceErr;?></span>
			</div>
			<div class="w3-quarter">
				<select class="w3-select" name="category" required>
					<option value="" disabled selected>Choose Style</option>
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
				<input class="w3-input w3-border" type="number" name="quantity" min="1" value="1" required>
				<label class="w3-label w3-validate">Quantity (enter numbers only)</label><span class="error"><?php echo $quantityErr;?></span>
			</div>
			<div class="w3-quarter">
				<select class="w3-select" name="size" required>
					<option value="" disabled selected>Choose Size</option>
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
				<input class="w3-input w3-border" type="text" name="pattern" placeholder="describe the pattern" required>
				<label class="w3-label w3-validate">Pattern</label><?php echo $patternErr;?>
			</div>
		</div>
		<div class="w3-row-padding">
			<div class="w3-half">
				<input class="w3-input w3-border" type="text" name="color" placeholder="describe fit and feel (optional)">
				<label class="w3-label w3-validate">Fit & Feel</label>
			</div>
			<div class="w3-half">
				<input class="w3-input w3-border" type="text" name="thread" placeholder="describe the thread content (optional)">
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
		<div class="w3-row-padding">
			<fieldset><legend>Upload Pictures</legend>
				<input type="file" name="fileToUpload" id="fileToUpload">
			</fieldset>
		</div>
		<br><br>
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<input class="w3-button" type="submit" name="submit" value="Create">
			</div>
		</div>
	</form>
</div>

</body>

<footer>
<?php include 'foot.php'; ?>
</footer>
</html>