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
<p> This is where you find events </p>
<ul>
<li> step 1 - select a seller you are following </li>
<li> step 2 - select month </li>
<li> step 3 - select type of event you are searching for </li>
</ul>
<!--TODO: Query Time Zone info to adjust dates. Record Dates in GMT. -->
<!--calendar days heading - hide on small screens-->
<div class="w3-row w3-container w3-hide-small">
	<div class="w3-col m1 w3-container w3-blue">
		<span class="w3-tag">SUN</span>
	</div>
	<div class="w3-col m1 w3-container w3-cyan">
		<span class="w3-tag">MON</span>
	</div>
	<div class="w3-col m1 w3-container w3-blue">
		<span class="w3-tag">TUE</span>
	</div>
	<div class="w3-col m1 w3-container w3-cyan">
		<span class="w3-tag">WED</span>
	</div>
	<div class="w3-col m1 w3-container w3-blue">
		<span class="w3-tag">THU</span>
	</div>
	<div class="w3-col m1 w3-container w3-cyan">
		<span class="w3-tag">FRI</span>
	</div>
	<div class="w3-col m1 w3-container w3-blue">
		<span class="w3-tag">SAT</span>
	</div>
</div>
<!--begin guts of calendar-->
<div class="w3-row w3-container w3-padding-8">
<?php
$date=getdate();  //TODO: allow user to select month. using current mon for testing.
$first=date_create($date['year']."-".$date['mon']."-1");
$days=array("SUN","MON","TUE","WED","THU","FRI","SAT");  //TODO: Delete Me
for($i=0; $i < date_format($first, "w"); $i++)
{
	echo "<div class='w3-col m1 w3-container'>";
	echo "<span class='w3-tag'>  </span>";
	echo "</div>";
}
$daysinmonth=cal_days_in_month(CAL_GREGORIAN, $date['mon'], $date['year']);
$currentday=date_create();
for($i=0; $i < $daysinmonth; $i++)
{
	if(date_format($first, "w")==0)
	{
		echo "</div>";
		echo "<div class='w3-row w3-container w3-padding-8'>";
	}
	echo "<div class='w3-col m1 w3-container'>";
	echo "<span class='w3-tag'>";
	echo date_format($first, "d");
	echo " </span>";
	//TODO: insert SQL here. for each event on this day echo out.
	echo "</div>";
	date_add($first, date_interval_create_from_date_string("1 day"));
}
?>
</div>

</body>

<footer>
<?php include 'foot.php'; ?>
</footer>
</html>