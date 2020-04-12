#!/bin/bash

# commen declarations
LOGFILE="/var/run/logs/`date +%Y-%m-%d`_watch.log"
BASE_DIR=/var/run

# set language to UTF-8 in order to avoid RuntimeError from ocrmypdf (will abort further execution otherwise)
export LC_ALL=C.UTF-8
export LANG=C.UTF-8

# skip empty folder
if [ -z "$(ls -A $BASE_DIR/inbox)" ]; then
	exit 0
else
	# indicate the job has started in the logs
	echo "`date +%Y-%m-%dT%H:%M:%S%:z` - INFO: Starting OCR scan process" >> $LOGFILE 2>&1
fi

# start processing PDF FILEs
for ORIG_FILE in $BASE_DIR/inbox/*.pdf; do #for all pdfs in the input folder
	
	# ensure that, if there are no matching files, the loop will exit without trying to process a non-existent file
	# HINT: this can happen when there files in this folder with a different file extension
	[ -f "$ORIG_FILE" ] || break 

	# set file related variables
	FILE_NAME=$(basename "$ORIG_FILE")
	OCRED_FILE=$BASE_DIR/tmp/$FILE_NAME
	SIDECAR_FILE=$BASE_DIR/tmp/$FILE_NAME.txt
	ERROR_FILE=$BASE_DIR/err/$FILE_NAME

	# do the OCR scan
	echo "`date +%Y-%m-%dT%H:%M:%S%:z` - INFO: processing file " $ORIG_FILE " ..." >> $LOGFILE 2>&1
	OUTPUT="$(/usr/local/bin/ocrmypdf -l eng+deu --sidecar $SIDECAR_FILE --deskew --force-ocr --clean --output-type pdfa $ORIG_FILE $OCRED_FILE 2>&1)"
	if [ $? -eq 0 ]; then
		echo "`date +%Y-%m-%dT%H:%M:%S%:z` - INFO: OCR processing successful. moving OCR'ed version of file " $FILE_NAME " to tmp folder ..." >> $LOGFILE 2>&1
		# since the original file has been processed successfully we dont need it anymore and can remove it
		rm $ORIG_FILE
	else
		grep ERROR <<< "$OUTPUT" >> $LOGFILE 2>&1
		echo "`date +%Y-%m-%dT%H:%M:%S%:z` - WARN: OCR processing failed. Trying to add file " $FILE_NAME " anyways. please investigate further ..." >> $LOGFILE 2>&1	
		# originals of failed scans also need to be moved to the tmp since we want to keep them and handle them via manual review	
		mv $ORIG_FILE $OCRED_FILE
	fi

	# upload the file via REST API of laravel application
	/bin/bash /home/scanner/upload.sh $FILE_NAME >> $LOGFILE 2>&1
	
done

# indicate the job has stopped in the logs
echo "`date +%Y-%m-%dT%H:%M:%S%:z` - INFO: processing finished" >> $LOGFILE 2>&1