#!/bin/bash

# stop and remove docker containers
docker-compose down

# remove volume (as it might contain outdated php files)
docker volume rm dms_webdata

# start up docker containers
docker-compose up -d