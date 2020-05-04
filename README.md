This is the **dms+** project - a lightweight web based document management solution for private homes

Features
========

Currently, the application has the following capabilities:

* automatic ingestion of new PDF files via `/inbox` folder
* extraction of included texts via OCR scan
* text search on an index of the OCR'ed files
* visualization of PDF file in embeded PDF viewer
* manual correction of determined text after OCR scan

Download
========

* [Version 1.1.3](https://github.com/d-lopes/dmsplus/releases/tag/1.1.3)
* [older versions](https://github.com/d-lopes/dmsplus/releases)

Getting Started
===============

The easiest way to get started is to start the appliaction as docker containers. You can find a ready to use docker-compose.yml in the `_environments` subfolder. This folder contains all necessary sources for the startup of the **dms+** application and serves as a hub for its data (in case you want to do a backup). Simply navigate to the `_environments` folder and run the following script which handles the docker commands for you:

```
sh ./dms-restart.sh
```

> **HINT:** Before you start, please make sure to adjust the contents of dms.env.example to your needs and rename it to dms.env (since this file is referenced from the docker-compose.yml)

Please refer to the [wiki pages](https://github.com/d-lopes/dmsplus/wiki) for further information

Changelog
=========

see [CHANGELOG](https://github.com/d-lopes/dmsplus/blob/master/CHANGELOG.md) file

License
=======

see [LICENSE](https://github.com/d-lopes/dmsplus/blob/master/LICENSE) file