style = {}
with open('style.txt','r') as f:
	 for line in f:
		 k = line.split('\"')
		 k = k[1]
		 style[k] = line
	 srt = sorted(style.keys())
	 for key in srt:
		 print style[key]
f.close()
