sizelist=["2t","4","6","8","10","12","xxs","xs","s","m","l","xl","2xl","3xl","tween","one size","tall & curvy"]
for sz in sizelist:
	print "<input class=\"w3-check\" type=\"checkbox\" name=\""+sz+"\" value=\""+sz+"\""
	print "<?php"
	print "if ($_SERVER[\"REQUEST_METHOD\"] == \"POST\") {"
	print "  if (isset($_POST[\'"+sz+"\'])) {"
	print "    echo \"checked\";"
	print "  }"
	print "}"
	print "?>>"
	print "<label class=\"w3-label\">"+sz+"</label><br>"
