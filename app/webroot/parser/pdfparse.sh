if [ $# = 1 ]; then
	cd /home/laserforce/lfstats.redial.net/lfstats/app/webroot/parser
	mv incoming/$1/*.pdf input/
	java -Xmx100M -jar LFScoreParser.jar
	mkdir -p pending/$1/
	mv output/*.pdf pending/$1/
	mv output/*.csv pending/$1/
	rm output/*
	exit 0
else
	exit 1
fi
