if [ $# = 1 ]; then
	cd /home/laserforce/lfstats.redial.net/lfstats/app/webroot/parser
	echo "InputDirectory=/home/laserforce/lfstats.redial.net/lfstats/app/webroot/parser/incoming/$1/
OutputDirectory=/home/laserforce/lfstats.redial.net/lfstats/app/webroot/parser/output/$1/" >LFScoreParser.properties
	mkdir -p /home/laserforce/lfstats.redial.net/lfstats/app/webroot/parser/output/$1/
	mkdir -p /home/laserforce/lfstats.redial.net/lfstats/app/webroot/parser/pending/$1/
	java -Xmx100M -jar LFScoreParser.jar
	mv output/$1/*.pdf ../pdf/
	mv output/$1/*.csv pending/$1/
	rm output/$1/*
	exit 0
else
	exit 1
fi
