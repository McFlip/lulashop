<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>LuLa Shop</title>
	<?php include 'menu.php'; ?>
</head>

<style>
.mySlides {display:none;}
</style>
<body>

<div class="w3-panel w3-card-8 w3-center  w3-tangerine">
  <header class="w3-xxxlarge w3-padding-1">Welcome to LuLa Shop!</header>
	<p class="w3-xlarge w3-padding-1">A great place to find LuLaRoe clothing online or in real life</p>
</div>

<div class="w3-content w3-section" style="max-width:500px">
  <img class="mySlides" src="img_top_ad1.jpg" style="width:100%">
  <img class="mySlides" src="img_top_ad2.jpg" style="width:100%">
  <img class="mySlides" src="img_top_ad3.jpg" style="width:100%">
</div>

<script>
var myIndex = 0;
carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName("mySlides");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}
    x[myIndex-1].style.display = "block";
    setTimeout(carousel, 2000); // Change image every 2 seconds
}
</script>

<div class="w3-container">
	<h1>About LuLa Shop</h1>
	<p>LuLa Shop provides a convenient way for people to browse collections of LuLaRoe clothing from a favorite consultant or from all consultants as well as browse and sign up for events. Click on the Shop button to start looking at clothes. Click the Calendar button to browse events in your area or to host a party. Click the Find a Consultant button to search for consultants in your area. Click on the About the Styles button to learn about the different cuts and styles offered.</p>
</div>
</body>
<footer>
<?php include 'foot.php'; ?>
</footer>
</html>
 
