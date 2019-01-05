#!/bin/bash

# export Seed DMS home variable for later use
SEEDDMS_HOME=/var/www/html/seeddms-5.1.8

# start mysql service if it is not running
MYSQL_RUNNING=$(pgrep mysql | wc -l);
if [ "$MYSQL_RUNNING" -lt 1 ]; then
	service mysql start
fi

# create 'scans' folder
export SEEDDMS_HOME
cd $SEEDDMS_HOME/utils
./seeddms-createfolder -n scans -F 1

# retrieve folder ID
FOLDER_ID=$(mysql -u root -ps3cret < /DATA/tmp/get-scans-folder-ID.sql |  tail -n 1)

# store Seed DMS home and folder ID permanently for later use
cat <<EOT >> ~/.profile

# additional variables for automatic ingestion of PDF files into Seed DMS
export SEEDDMS_HOME=$SEEDDMS_HOME
export SCANS_FOLDER_ID=$FOLDER_ID
EOT