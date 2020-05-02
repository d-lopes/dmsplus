#!/bin/bash

for ORIG_FILE in ./samples/*.pdf; do 

    
    # ensure that, if there are no matching files, the loop will exit without trying to process a non-existent file
	# HINT: this can happen when there files in this folder with a different file extension
	[ -f "$ORIG_FILE" ] || break 

	# set file related variables
	FILE_NAME=$(basename "$ORIG_FILE")
	OCRED_FILE=./output/$FILE_NAME
	SIDECAR_FILE=./output/$FILE_NAME.txt

    docker run --rm -i -v `pwd`:/app jbarlow83/ocrmypdf -l eng+deu --sidecar $SIDECAR_FILE --deskew --force-ocr --clean --output-type pdfa $ORIG_FILE $OCRED_FILE

done