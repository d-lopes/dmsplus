#!/bin/bash

# commen declarations
LOGFILE="/var/run/logs/`date +%Y-%m-%d`_watch.log"
BASE_DIR=/var/run

# skip invalid input - we expect a file path
if [ -z "$1" ]; then
    echo "`date +%Y-%m-%dT%H:%M:%S%:z` - ERROR: no input file provided" >> $LOGFILE 2>&1
    exit 1
fi

# set file related variables
ORIG_FILE=$1
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