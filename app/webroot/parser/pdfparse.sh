#!/bin/bash

if [ $# = 1 ]; then
	DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
	cd $DIR
	echo "InputDirectory=$DIR/incoming/$1/
OutputDirectory=$DIR/output/$1/" > LFScoreParser.properties
	mkdir -p output/$1/
	mkdir -p pending/$1/
	java -Xmx100M -jar LFScoreParser.jar
	mv output/$1/*.pdf ../pdf/
	mv output/$1/*.xml pending/$1/
	rm output/$1/*
	exit 0
else
	exit 1
fi
