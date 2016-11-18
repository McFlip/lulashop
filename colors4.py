colors=["green","teal","blue","purple","red","pink","flesh","tan","brown","black","lime","yellow","orange","grey","maroon","white"]
str = "$arrColor = array("
for c in colors:
	str = str + "\"" + c + "\"" + ","
print str