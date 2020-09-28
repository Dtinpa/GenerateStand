#/home/dtinpa/quirkregistry.com/BackEndV2/bin/python2.7

with open("spiderManager.py") as fp:
    for i, line in enumerate(fp):
	if "\xe2" in line:
		print i, repr(line)
