style=[]
with open('style.txt','r') as f:
	 for line in f:
		style = line.split('\"',2)
		print "<input class=\"w3-check\" type=\"checkbox\" name=\""+style[1]+"\" value=\""+style[1]+"\""
		print "<?php"
		print "if ($_SERVER[\"REQUEST_METHOD\"] == \"POST\") {"
		print "  if (isset($_POST[\'"+style[1]+"\'])) {"
		print "    echo \"checked\";"
		print "  }"
		print "}"
		print "?>>"
		print "<label class=\"w3-label\">"+style[1]+"</label><br>"
f.close()
