import sys
import random
import re

#print("SETTING UP AI")

def batch():
	listy = []
	for argument in sys.argv:
		if re.match('.*DUMMY.py.*',argument):
			continue
		else:
			listy.append([random.uniform(-2,8),random.uniform(-2,8)])
	return listy
	
#print("OPENING FILE")
filew = open("output.txt", 'w')
#print("ANALYZING IMAGES")
filew.write(str(batch()))
filew.close()

#print ("DONE FINDING FOOD")
