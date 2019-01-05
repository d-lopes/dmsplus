#!/bin/sh

###############
# Version 0.9 #
###############

# commen declarations
LOGFILE=/DATA/logs/watcher.log
BASE_DIR=/DATA/ocrmypdf

# activate python virtual env
. /appenv/bin/activate 

# set language to UTF-8 in order to avoid RuntimeError from ocrmypdf (will abort further execution otherwise)
export LC_ALL=C.UTF-8
export LANG=C.UTF-8

# skip empty folder
if [ -z "$(ls -A $BASE_DIR/input)" ]; then
	echo "empty folder " $BASE_DIR"/input" >> $LOGFILE 2>&1
	exit 0
else
	# indicate the job has started in the logs
	echo "`date +%Y-%m-%dT%H:%M:%S%:z` : Starting work" >> $LOGFILE 2>&1
fi

# start processing PDF FILEs
for FILE in $BASE_DIR/input/*.pdf; do #for all pdfs in the input folder
	[ -f "$FILE" ] || break # ensure that, if there are no matching FILEs, the loop will exit without trying to process a non-existent file
	
	# get FILE_NAME
	FILE_NAME=$(basename "$FILE")
	echo "found file " $FILE_NAME
	
	# do the OCR scan
	ocrmypdf -l eng+deu --rotate-pages --deskew $FILE $BASE_DIR/output/$FILE_NAME # run ocrmypdf.

	# figure out if OCR was successful
	if [ $? -eq 0 ]; then
		# originals of successful OCR scans are moved to the backup folder
		echo "OCR conversion successful. moving original file " $FILE "to backup folder"
		mv $FILE $BASE_DIR/bkp/$FILE_NAME

		# upload the file as admin into the root folder of SeedDMS (SEEDDMS_HOME and SCANS_FOLDER_ID are global environmet variables)
		$SEEDDMS_HOME/utils/seeddms-adddoc -F $SCANS_FOLDER_ID -C 'OCR scan uploaded' -f $BASE_DIR/output/$FILE_NAME
		
		# figure out if upload was successful
		if [ $? -eq 0 ]; then
			# successfully uploaded OCR scans are moved to the backup folder as well
			echo "upload successful. moving file " $BASE_DIR/output/$FILE_NAME "to backup folder"
			mv $BASE_DIR/output/$FILE_NAME $BASE_DIR/bkp/uploaded/$FILE_NAME
		else 
			# OCR scans which upload has failed stay where they are but generate an error message for further investigation
			echo "`date +%Y-%m-%dT%H:%M:%S%:z` : ERROR: upload failed. please investigate file " $FILE " further" >> $LOGFILE 2>&1
		fi
	else
		# originals of failed scans are moved to the err folder
		echo "`date +%Y-%m-%dT%H:%M:%S%:z` : processing failed. moving file " $FILE "to err folder" >> $LOGFILE 2>&1
		mv $FILE $BASE_DIR/err/$FILE_NAME
	fi
done

# indicate the job has stopped in the logs
echo "`date +%Y-%m-%dT%H:%M:%S%:z` : finished work" >> $LOGFILE 2>&1