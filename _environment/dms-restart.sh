#!/bin/bash

# initialization
CURR_DIR=`dirname "$0"`
CONFIG_FILE="dms.env"
COMPOSE_FILE="$CURR_DIR/docker-compose.yml"

# make sure environment file for docker compose is present
if [ ! -f "$CURR_DIR/$CONFIG_FILE" ]; then
    echo "$CONFIG_FILE does not exist in directory $CURR_DIR. Please copy $CONFIG_FILE.example and adjust it to your needs."
    exit -4711
fi

# allow alternative versions for docker compose file
if [ ! -z "$1" ]; then
    COMPOSE_FILE="$1"
    if [ ! -f "$COMPOSE_FILE" ]; then
        echo "$1 does not exist. Please reference an existing docker compose file."
        exit -0815
    fi  
fi

# stop and remove docker containers
docker-compose -p dms -f "$COMPOSE_FILE" down

# remove volume (as it might contain outdated php files)
docker volume rm dms_webdata

# start up docker containers
docker-compose -p dms -f "$COMPOSE_FILE" up -d