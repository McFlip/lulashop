<?php
session_start();
?>
<!-- TODO: Check the user type -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>LuLa Shop</title>
	<?php include 'menu.php'; ?>
</head>

<body>
<p> This is where you view the consultant dashboard to do consultant things :) </p>
<div class="w3-container">
  <h2>Create an inventory item</h2>
  <a href="createinventory.php"><button class="w3-btn w3-green w3-large">Create Inventory</button></a>
</div>

</body>

<footer>
<?php include 'foot.php'; ?>
</footer>
</html>