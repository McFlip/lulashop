<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>LuLa Shop</title>
	<?php include 'menu.php'; ?>
</head>

<body>
<p> This is where you find events </p>

<?php
$date=getdate();
$first=date_create($date['year']."-".$date['mon']."-1");
echo "the first day of the month is: ";
echo date_format($first,"l");
$days=array("SUN","MON","TUE","WED","THU","FRI","SAT");
for($i=0; $i<date_format($first, "w"); $i++)
{
	echo "$days[$i] ";
}
?>
</body>

<footer>
<?php include 'foot.php'; ?>
</footer>
</html>