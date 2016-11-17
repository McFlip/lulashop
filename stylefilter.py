style=[]
str="$arrStyle = array("
with open('style.txt','r') as f:
	 for line in f:
		style = line.split('\"',2)
		str += "\"" + style[1] + "\"" + ","
	 print str
f.close()
