#!/bin/bash

# capture environment variables in order to allow provising them to child processes run by cronjobs
printenv | sed 's/^\(.*\)$/export \1/g' > /env.sh

# Setup a cron schedule
echo "* * * * * flock -xn /home/scanner/watch.lck -c '. /env.sh; /home/scanner/watch.sh'
# This extra line makes it a valid cron" > scheduler.txt

# Start the run once job.
echo "OCR scanner has been started"
crontab scheduler.txt
cron -f