#!/bin/bash

if [ $# = 1 ]; then
	DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
	echo "InputDirectory=$DIR/incoming/$1/
OutputDirectory=$DIR/output/$1/" > $DIR/LFScoreParser.properties
	mkdir -p $DIR/output/$1/
	mkdir -p $DIR/pending/$1/
	java -Xmx100M -jar $DIR/LFScoreParser.jar
	mv $DIR/output/$1/*.pdf $DIR/../pdf/
	mv $DIR/output/$1/*.csv $DIR/pending/$1/
	rm $DIR/output/$1/*
	exit 0
else
	exit 1
fi
