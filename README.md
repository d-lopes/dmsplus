This is the dms+ project - a lightweight document management solution

This project consists of these components
* web application
* OCR scan utility

These components are best used together with Nginx and MySQL.

Getting Started
===============

You can find a ready to use docker-compose.yml in the _environments subfolder. This folder contains all necessary data for the DMS+ application and gives you access to its data (in case you want to do a backup). Simply navigate to the _environment folder an run the following script which handles the docker commands for you:

```
sh ./dms-restart.sh
```

Hint: Before you start, please make sure to adjust the contents of dms.env.example to your needs and rename it to dms.env (since this file is referenced from the docker-compose.yml)