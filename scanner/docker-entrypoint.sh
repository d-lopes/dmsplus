#!/bin/bash

# capture environment variables in order to allow provising them to child processes run by cronjobs
if [ -f "/env.sh" ]; then
    rm /env.sh 
fi
printenv | sed 's/^\(.*\)$/export \1/g' > /env.sh

# Setup a cron schedule: see https://crontab.guru/ for help
if [ ! -f "scheduler.txt" ]; then
    echo "* * * * * flock -xn /home/scanner/watch-inbox.lck -c '. /env.sh; /home/scanner/watch.sh inbox'
* * * * * flock -xn /home/scanner/watch-uploads.lck -c '. /env.sh; /home/scanner/watch.sh uploads/raw-files'
# This extra line makes it a valid cron" > scheduler.txt
fi

# Start the run once job.
echo "OCR scanner has been started"
crontab scheduler.txt
cron -f