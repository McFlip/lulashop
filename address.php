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
<body>
<?php
$mode = $_POST["mode"];
if (isset($_POST["addressID"])){
  $addressID = $_POST["addressID"];
}
if (isset($_POST["street1"])){
  $street1 = $_POST["street1"];
}
if (isset($_POST["street2"])){
  $street2 = $_POST["street2"];
}
if (isset($_POST["city"])){
  $city = $_POST["city"];
}
if (isset($_POST["state"])){
  $state = $_POST["state"];
}
if (isset($_POST["zip"])){
  $zip = $_POST["zip"];
}
if (isset($_POST["latitude"])){
  $latitude = $_POST["latitude"];
}
if (isset($_POST["longitude"])){
  $longitude = $_POST["longitude"];
}
if (isset($_POST["appt"])){
  $appt = $_POST["appt"];
}
if (isset($_POST["ownerID"])){
  $ownerID = $_POST["ownerID"];
}
$userType = $_SESSION["userType"];
$ID = $_SESSION["userID"];
if ($userType == "member"){
  $ownerID = "m_".$ID;
} else if ($userType == "user"){
  $ownerID = "u_".$ID;
}
/**************************************** Add an Address ****************************/
if($mode=="add")
{
  //	<tr><td>addressID:</td><td><input type="text" name="addressID" ></td></tr>
  Print '<h2>Add Address</h2>
  <p>
  <form action=address.php method=post>
  <table>


  <tr><td>street1:</td><td><input type="text" name="street1" ></td></tr>
  <tr><td>street2:</td><td><input type="text" name="street2" ></td></tr>
  <tr><td>city:</td><td><input type="text" name="city" ></td></tr>
  <tr><td>state:</td><td><input type="text" name="state" ></td></tr>
  <tr><td>zip:</td><td><input type="text" name="zip" ></td></tr>
  <tr><td>appt:</td><td><input type="text" name="appt" ></td></tr>
  <tr><td colspan="2" align="center"><input type="submit" ></td></tr>
  <input type=hidden name=mode value=added>
  </table>
  </form> <p>';
}

if($mode=="added")
{
// Function using JSON
function lookup($string)
{
    $string = str_replace (" ", "+", urlencode($string));
    $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $details_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);

   // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
   if ($response['status'] != 'OK')
   {
    return null;
   }

//    print_r($response);
   $geometry = $response['results'][0]['geometry'];

    $longitude = $geometry['location']['lng'];
    $latitude = $geometry['location']['lat'];

    $array = array(
        'latitude' => $geometry['location']['lat'],
        'longitude' => $geometry['location']['lng'],
        'location_type' => $geometry['location_type'],
    );

    return $array;
}
// End of JSON function

// $city = $_POST['city'];
$addressARR = array($street1,$street2,$city,$state,$zip);
$lookupStr = "";
foreach ($addressARR as $addr){
  $lookupStr .= $addr." ";
}
echo "<br>".$lookupStr."<br>";
$array = lookup($lookupStr);
$latitude = $array['latitude'];
$longitude = $array['longitude'];

$conn->exec ("INSERT INTO address (street1, street2, city, state, zip, latitude, longitude, appt, ownerID) VALUES ( '$street1', '$street2', '$city', '$state', '$zip', '$latitude', '$longitude', '$appt', '$ownerID')");
}

/************************************* Updating Address ******************************/
if($mode=="edit")
{
  Print '<h2>Edit Address</h2>
  <p>
  <form action=address.php method=post>
  <table>';

  Print '
  <tr><td>street1:</td><td><input type="text" value="'.$street1.'" name="street1" ></td></tr>
  <tr><td>street2:</td><td><input type="text" value="'.$street2.'" name="street2" ></td></tr>';
  Print '
  <tr><td>city:</td><td><input type="text" value="'.$city.'" name="city" ></td></tr>
  <tr><td>state:</td><td><input type="text" value="'.$state.'" name="state" ></td></tr>
  <tr><td>zip:</td><td><input type="text" value="'.$zip.'" name="zip" ></td></tr>
  <tr><td>latitude:</td><td><input type="text" value="'.$latitude.'" name="latitude" ></td></tr>
  <tr><td>longitude:</td><td><input type="text" value="'.$longitude.'" name="longitude" ></td></tr>
  <tr><td>appt:</td><td><input type="text" value="'.$appt.'" name="appt" ></td></tr>
  <tr><td><input hidden type="text" value="'.$ownerID.'" name="ownerID" ></td></tr>
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
if($mode == "removed")
{
//   var_dump($addressID);
  try{
    $conn->exec ("DELETE FROM address WHERE addressID = '$addressID'");
  } catch(PDOException $e) {
    echo "Address Removal failed: " . $e->getMessage();
    die();
  }
  Print "Address has been removed <p>";
}

/************************************ Address Form *******************************/


$data = $conn->query("SELECT * FROM address WHERE `ownerID`='$ownerID'")
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
</tr>";

$data->setFetchMode(PDO::FETCH_ASSOC);
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
  if($mode == "remove"){
    Print "<td><form method='post' action='address.php'>
    <input type='submit' name='submit' value='Remove Address'>
    <input hidden name='mode' value='removed'>";
    foreach ($info as $idx => $val){
      Print "<input hidden name='$idx' value='$val'>";
    }
  } else {
    Print "<td><form method='post' action='address.php'>
    <input type='submit' name='submit' value='Edit Address'>
    <input hidden name='mode' value='edit'>";
    foreach ($info as $idx => $val){
      Print "<input hidden name='$idx' value='$val'>";
    }
  }
  Print "</form></td></tr>";
}

Print "</table>";
?>
</body>
<?php $conn = null;?>
<footer>
<?php include 'foot.php'; ?>
</footer>
</html>
