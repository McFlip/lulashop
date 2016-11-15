colors=["green","teal","blue","purple","red","pink","flesh","tan","brown","black","lime","yellow","orange","grey","maroon","white"]
for c in colors:
	print "<input class=\"w3-check\" type=\"checkbox\" name=\""+c+"\" value=\""+c+"\""
	print "<?php"
	print "if ($_SERVER[\"REQUEST_METHOD\"] == \"POST\") {"
	print "  if (isset($_POST[\'"+c+"\'])) {"
	print "    echo \"checked\";"
	print "  }"
	print "}"
	print "?>>"
	print "<label class=\"w3-label\">"+c+"</label>"
