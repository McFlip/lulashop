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
$mode = $_POST["mode"];
/**************************************** Add an Address ****************************/
if($mode=="add")
{
  //	<tr><td>addressId:</td><td><input type="text" name="addressId" ></td></tr>
  Print '<h2>Add Address</h2>
  <p>
  <form action=address.php method=post>
  <table>


  <tr><td>street1:</td><td><input type="text" name="street1" ></td></tr>
  <tr><td>street2:</td><td><input type="text" name="street2" ></td></tr>
  <tr><td>city:</td><td><input type="text" name="city" ></td></tr>
  <tr><td>state:</td><td><input type="text" name="state" ></td></tr>
  <tr><td>zip:</td><td><input type="text" name="zip" ></td></tr>
  <tr><td>latitude:</td><td><input type="text" name="latitude" ></td></tr>
  <tr><td>longitude:</td><td><input type="text" name="longitude" ></td></tr>
  <tr><td>appt:</td><td><input type="text" name="appt" ></td></tr>
  <tr><td>ownerID:</td><td><input type="text" name="ownerID" ></td></tr>
  <tr><td colspan="2" align="center"><input
  type="submit" ></td></tr>
  <input type=hidden name=mode value=added>
  </table>
  </form> <p>';
}

if($mode=="added")
{
  $conn->query ("INSERT INTO addressID (street1, street2, city, state, zip, latitude, longitude, appt, ownerID) VALUES ('$addressID', '$street1', '$street2', '$city', 'state', 'zip', 'latitude', 'longitude', 'appt', 'ownerID')");
}

/************************************* Updating Address ******************************/
if($mode=="edit")
{
  Print '<h2>Edit Address</h2>
  <p>
  <form action='.$_SERVER['PHP_SELF'].'method=post>
  <table>';

  //	<tr><td>addressID:</td><td><input type="text" value="'.$addressID.'" name="addressID" ></td></tr>
  Print '
  <tr><td>street1:</td><td><input type="text" value="'.$street1.'" name="street1" ></td></tr>
  <tr><td>street2:</td><td><input type="text" value="'.$street2.'" name="street2" ></td></tr>';
  //print '"name="street2" ></td></tr>
  Print '
  <tr><td>city:</td><td><input type="text" value="'.$city.'" name="city" ></td></tr>
  <tr><td>state:</td><td><input type="text" value="'.$state.'" name="state" ></td></tr>
  <tr><td>zip:</td><td><input type="text" value="'.$zip.'" name="zip" ></td></tr>
  <tr><td>latitude:</td><td><input type="text" value="'.$latitude.'" name="latitude" ></td></tr>
  <tr><td>longitude:</td><td><input type="text" value="'.$longitude.'" name="longitude" ></td></tr>
  <tr><td>appt:</td><td><input type="text" value="'.$appt.'" name="appt" ></td></tr>
  <tr><td>ownerID:</td><td><input type="text" value="'.$ownerID.'" name="ownerID" ></td></tr>
  <tr><td colspan="2" align="center"><input type="submit" ></td></tr>
  <input type=hidden name=mode value=edited>
  <input type=hidden name=addressID value='.$addressID.'>
  </table>
  </form>
  <p>';
}

if($mode=="edited")
{
  $conn->query ("UPDATE address set street1 = '$street1', street2 = '$street2', city = '$city', state = '$state', zip = '$zip', latitude = '$latitude', longitude = '$longitude', appt = '$appt', ownerID = '$ownerID' WHERE addressID = $addressID");
}

Print "Address Updated!<p>";

/* Deleting Address */
if($mode == "remove")
{
  $conn->query ("DELETE FROM addressID where addressID = $addressID");
  Print "Address has been removed <p>";
}

/************************************ Address Form *******************************/
$data = $conn->query("SELECT * FROM address")
or die(mysql_error());
Print "<h2>Address</h2><p>";
Print "<table border cellpadding=3>";
Print "<tr><th width=100>street1</th>
<th width=100>street2</th>
<th width=200>city</th>
<th width=100>state</th>
<th width=100>zip</th>
<th width=100>latitude</th>
<th width=100>longitude</th>
<th width=100>appt</th>
<th width=100>ownerID</th></tr>";
// Print "<td colspan=5 align=right><a href=" .$_SERVER["PHP_SELF"]. "?mode=add>Add
// Address</a></td>";
while($info = $data->fetch())
{
  Print "<td>".$info['street1'] ."</td> ";
  Print "<td>".$info['street2'] ."</td> ";
  Print "<td>".$info['city'] ."</td> ";
  Print "<td>".$info['state'] ."</td> ";
  Print "<td>".$info['zip'] ."</td> ";
  Print "<td>".$info['latitude'] ."</td> ";
  Print "<td>".$info['longitude'] ."</td> ";
  Print "<td>".$info['appt'] ."</td> ";
  Print "<td>".$info['ownerID'] ."</td> ";
  Print "<td><a href=" .$_SERVER['PHP_SELF']. "?addressID=" .$info['addressID']
  ."&street1 =" .$info['street1']
  ."&street2 =" .$info['street2']
  ."&city =" .$info['city']
  ."&state =" .$info['state']
  ."&zip =" .$info['zip']
  ."&latitude =" .$info['latitude']
  ."&longitude =" .$info['longitude']
  ."&appt =" .$info['appt']
  ."&ownerID =" .$info['ownerID']
  ."&mode=edit>Edit</a></td></tr>";
}

Print "</table>";
?>
</body>
</html>
