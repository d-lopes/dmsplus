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
FILE_NAME=$1
OCRED_FILE=$BASE_DIR/tmp/$FILE_NAME
SIDECAR_FILE=$BASE_DIR/tmp/$FILE_NAME.txt
FAILED_UPLOAD_FILE=$BASE_DIR/err/failed_upload-$FILE_NAME
ERROR_FILE=$BASE_DIR/err/$FILE_NAME
DOCUMENT_ID="undefined" # this is going to be set later at runtime

# get contents from side car file and delete it (if available) 
if [ -f "$SIDECAR_FILE" ]; then 
    CONTENT=$(cat "$SIDECAR_FILE" | tr '\n' ' '| tr -cd '\11\12\15\40-\176')
    # if content has nothing more than whitespace replace with empty string 
    #   -> otherwise error occurs during creation of document in DMS webapp
    if [[ -z "${CONTENT// }" ]]; then
        CONTENT=""
    fi
    rm $SIDECAR_FILE
else 
    CONTENT=""
fi

# build JSON request to send
JSON_BODY="{ \"filename\": \"$FILE_NAME\", \"content\": \"$CONTENT\"}"

#################################
# send document meta data to API
#################################
echo "`date +%Y-%m-%dT%H:%M:%S%:z` - INFO: creating document for file " $FILE_NAME " in DMS web application ..." >> $LOGFILE 2>&1
RESPONSE=$(curl -s -X POST $DOCUMENT_API_URL -H "accept: application/json" -H "Content-Type: application/json" -d "$JSON_BODY" -o -)

# figure out if cURL command was successful at all
#   -> OCR scans where is was not possible to create the document meta data for are moved to the error folder
if [ $? -ne 0 ]; then 
    echo "`date +%Y-%m-%dT%H:%M:%S%:z` - ERROR: unable to make API call $DOCUMENT_API_URL ..." >> $LOGFILE 2>&1
    echo "     abort processing. moving file " $FILE_NAME " to error folder. please investigate file " $FILE_NAME " further ..." >> $LOGFILE 2>&1
    mv $OCRED_FILE $ERROR_FILE
    exit 1
else
    # additionally, check if we were able to extract the Document ID (must be integer) from the response
    DOCUMENT_ID=$(echo "$RESPONSE" | jq '.id')
    if ! [[ $DOCUMENT_ID =~ ^[0-9]+$ ]] ; then
        echo "`date +%Y-%m-%dT%H:%M:%S%:z` - ERROR: unable to interpret Document ID $DOCUMENT_ID as Integer from HTTP response $RESPONSE ..." >> $LOGFILE 2>&1
        echo "     JSON_BODY: $JSON_BODY" >> $LOGFILE 2>&1
        echo "     abort processing. moving file " $FILE_NAME " to error folder. please investigate file " $FILE_NAME " further ..." >> $LOGFILE 2>&1
    mv $OCRED_FILE $ERROR_FILE
        exit 1
    fi
fi

#################################
# sent document file to API
#################################
echo "`date +%Y-%m-%dT%H:%M:%S%:z` - INFO: adding file $FILE_NAME to document with ID $DOCUMENT_ID in DMS web application ..." >> $LOGFILE 2>&1
HTTP_STATUS_CODE=$(curl -F document=@$OCRED_FILE "$DOCUMENT_API_URL/$DOCUMENT_ID/binary" -s -o /dev/null -w "%{http_code}")

# figure out if cURL command was successful at all
#   -> OCR scans which upload has failed are moved to a special folder
if [ $? -ne 0 ]; then 
    echo "`date +%Y-%m-%dT%H:%M:%S%:z` - ERROR: unable to upload $FILE_NAME to $DOCUMENT_API_URL." >> $LOGFILE 2>&1
    echo "     moving file " $FILE_NAME " to error folder. please investigate file " $FILE_NAME " further ..." >> $LOGFILE 2>&1
    mv $OCRED_FILE $FAILED_UPLOAD_FILE
    exit 1
else 
    # additionally, check if HTTP_STATUS_CODE indicates successful processing of the uploaded file
    case "$HTTP_STATUS_CODE" in
     2*) echo "`date +%Y-%m-%dT%H:%M:%S%:z` - INFO: upload successful ..." >> $LOGFILE 2>&1
         # successfully uploaded files are removed from the harddrive (as they are now available in the DMS webapp)
         rm $OCRED_FILE
         ;;
      *) echo "`date +%Y-%m-%dT%H:%M:%S%:z` - ERROR: Unexpected server response (HTTP_STATUS_CODE: $HTTP_STATUS_CODE)." >> $LOGFILE 2>&1
         echo "     moving file " $FILE_NAME " to error folder. please investigate file " $FILE_NAME " further ..." >> $LOGFILE 2>&1
         # failed uploads need to be moved to a folder where they can be investigated further
         mv $OCRED_FILE $FAILED_UPLOAD_FILE
         exit 1
         ;;
    esac
fi