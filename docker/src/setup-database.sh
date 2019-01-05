#!/bin/bash

# start mysql service if it is not running
MYSQL_RUNNING=$(pgrep mysql | wc -l);
if [ "$MYSQL_RUNNING" -lt 1 ]; then
	service mysql start
fi

# setup SeedDMS database and user 
mysql -u root -ps3cret < /DATA/tmp/create-database.sql \

# create needed tables and contents
mysql -u seeddms -ps3cret seeddms < /DATA/tmp/seeddms-5.1.8/install/create_tables-innodb.sql
  