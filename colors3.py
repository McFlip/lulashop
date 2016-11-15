colors=["green","teal","blue","purple","red","pink","flesh","tan","brown","black","lime","yellow","orange","grey","maroon","white"]
for c in colors:
	print "if(isset($_POST[\"{}\"]))".format(c)+"{"+"$color"+" = $color.\"{}\".\",\"".format(c)+";}"
