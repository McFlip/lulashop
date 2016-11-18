sizelist=["2t","4","6","8","10","12","xxs","xs","s","m","l","xl","2xl","3xl","tween","one size","tall & curvy"]
str="$arrStyle = array("
for sz in sizelist:
	str += "\"" + sz + "\"" + ","
print str