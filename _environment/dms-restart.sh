#!/bin/bash

CURR_DIR=`dirname "$0"`
CONFIG_FILE="dms.env"
if [ ! -f "$CURR_DIR/$CONFIG_FILE" ]; then
    echo "$CONFIG_FILE does not exist in directory $CURR_DIR. Please copy $CONFIG_FILE.example and adjust it to your needs."
    exit -4711
fi

# stop and remove docker containers
docker-compose -f "$CURR_DIR/docker-compose.yml" down

# remove volume (as it might contain outdated php files)
docker volume rm dms_webdata

# start up docker containers
docker-compose -f "$CURR_DIR/docker-compose.yml" up -d