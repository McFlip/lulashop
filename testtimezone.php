 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<body>
 <?php
	$date = new DateTime('now', new DateTimeZone('UTC'));
	echo $date->format('Y-m-d H:i:sP') . "<br>";
	$abbrev  = DateTimeZone::listAbbreviations();
	foreach ($abbrev as $x => $val) {
	  echo $x.",";
	  $timezoneName = $val[0]['timezone_id'];
		if ($timezoneName) {
   	  $date->setTimezone(new DateTimeZone($timezoneName));
   	  echo $date->format('P') ."<br>";
	  }
	}
?>
</body>
</html>