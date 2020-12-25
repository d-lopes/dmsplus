#!/bin/bash

# common declarations
BASE_DIR=/var/run
LOGFILE="$BASE_DIR/logs/`date +%Y-%m-%d`_watch.log"

# skip invalid input - we expect an input directory (relative to the BASE_DIR)
if [ -z "$1" ]; then
    echo "`date +%Y-%m-%dT%H:%M:%S%:z` - ERROR: no input directory provided" >> $LOGFILE 2>&1
    exit 1
fi
INPUT_DIR=$1

# set language to UTF-8 in order to avoid RuntimeError from ocrmypdf (will abort further execution otherwise)
export LC_ALL=C.UTF-8
export LANG=C.UTF-8

# skip folder without PDF files
if [ -z "$(find $BASE_DIR/$INPUT_DIR -type f -name *.pdf)" ]; then
	exit 0
else
	# indicate the job has started in the logs
	echo "`date +%Y-%m-%dT%H:%M:%S%:z` - INFO: Starting OCR scan process for $INPUT_DIR" >> $LOGFILE 2>&1
fi

# get curremt time in seconds
CURRENT_TIME=$(date +%s)

# start processing PDF FILEs
for ORIG_FILE in $BASE_DIR/$INPUT_DIR/*.pdf; do #for all pdfs in the input folder
	
	# ensure that, if there are no matching files, the loop will exit without trying to process a non-existent file
	# HINT: this can happen when there are files in this folder with a different file extension
	[ -f "$ORIG_FILE" ] || break 

	# make sure we pick up only files, that have been last modified at least a minute ago (this will prevent us from using files that are in the middle of a copy/upload process)
	LAST_MODIFIED=$(stat -c %Y $ORIG_FILE)
	DIFF=$(($CURRENT_TIME-$LAST_MODIFIED))
	if [ $DIFF < 60 ]; then
		echo "INFO: $ORIG_FILE is skipped because its last modification is still in the grace period - retrying with the next scheduled run"
		break
	fi
	
	# scan the file via python program OCRMyPDF
	/bin/bash /home/scanner/scan.sh $ORIG_FILE >> $LOGFILE 2>&1

	# upload the file via REST API of laravel application
	/bin/bash /home/scanner/upload.sh $ORIG_FILE >> $LOGFILE 2>&1
	
done

# indicate the job has stopped in the logs
echo "`date +%Y-%m-%dT%H:%M:%S%:z` - INFO: processing finished" >> $LOGFILE 2>&1