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
?>
<div class="w3-panel w3-blue w3-round-xlarge">
  <p>Create Inventory</p>
</div>
<div class="w3-container w3-card">
	<form class="w3-container" method="post" action="createinventory.php">
		<div class="w3-row-padding">
			<div class="w3-quarter">
				<input class="w3-input w3-border" type="number" name="price" min="0" value="0" required>
				<label class="w3-label w3-validate">Price (enter numbers only)</label><span class="error"><?php echo $priceErr;?></span>
			</div>
			<div class="w3-quarter">
				<select class="w3-select" name="category" required>
<!-- 	TODO: alphabetize this list	 -->
					<option value="" disabled selected>Choose Style</option>
					<option value="sloan">sloan</option>
					<option value="sarah">sarah</option>
					<option value="randy">randy</option>
					<option value="maxi">maxi</option>
					<option value="lucy">lucy</option>
					<option value="madison">madison</option>
					<option value="monroe">monroe</option>
					<option value="nicole">nicole</option>
					<option value="perfectt">perfect t</option>
					<option value="mark">mark</option>
					<option value="mae">mae</option>
					<option value="lola">lola</option>
					<option value="leggings">leggings</option>
					<option value="kidsleggings">kids leggings</option>
					<option value="kidsazure">kids azure</option>
					<option value="julia">julia</option>
					<option value="joy">joy</option>
					<option value="jordan">jordan</option>
					<option value="jill">jill</option>
					<option value="jade">jade</option>
					<option value="irma">irma</option>
					<option value="gracie">gracie</option>
					<option value="classict">classic t</option>
					<option value="cassie">cassie</option>
					<option value="ana">ana</option>
					<option value="amelia">amelia</option>
					<option value="carly">carly</option>
					<option value="patrick">patrick</option>
					<option value="bianka">bianka</option>
					<option value="adeline">adeline</option>
					<option value="azure">azure</option>
					<option value="lindsay">lindsay</option>
					<option value="irma">irma</option>
				</select>
				<label class="w3-label w3-validate">Style category</label><?php echo $categoryErr;?>
			</div>
			<div class="w3-quarter">
				<input class="w3-input w3-border" type="number" name="quantity" min="0" value="0" required>
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
			<div class="w3-half">
				<input class="w3-input w3-border" type="text" name="color" placeholder="enter colors in descending order of dominance" required>
				<label class="w3-label w3-validate">Color</label><?php echo $colorErr;?>
			</div>
			<div class="w3-half">
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