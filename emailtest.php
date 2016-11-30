<?php
$to="denton@localhost.com";
$subject="testing php";
$txt="hello world!\n";
$headers="From: donotreply@localhost.com";
if (!($mail=mail($to,$subject,$txt,$headers))){
	echo "Email notification failed.<br>";
} else {
	echo "success!";
	echo $mail;
}
?>