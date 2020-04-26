This is the dms+ project - a lightweight solution to manage your documents

This project consists of these components
* web application
* OCR scan utility

These components are best used together with Nginx and MySQL.

You can find a ready to use docker-compose.yml in the _environments subfolder. This folder is prepared to repesent all necessary data for the DMS+ application and it gives you access to your data (in case you want to do a backup)

Please make sure to adjust the contents of dms.env.example to your needs and rename it to dms.env (since this file is referenced from the docker-compose.yaml)