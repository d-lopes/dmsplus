This is the **dms+** project - a lightweight web based document management solution for private homes

Features
========

Currently, the application has the following capabilities:

* automatic ingestion of new PDF files via `/inbox` folder
* automatic extraction of included texts via OCR scan
* automatic recognition of metioned dates in the documents textual contents
* automatic identification of potential duplicates (based on mimetype, file size and textual content)
* text and tag related document search on an index of the OCR'ed files
* status and date related filters in document search
* visualization of PDF file in embeded PDF viewer
* tagging functionality to organize documents
* correction of determined text after OCR scan
* upload of documents via web browser (due to failing ingestion of new PDF files from `/inbox` folder)
* creation of new documents via web browser
* deletion of documents (e. g. because they are corrupt or have been identified as duplicates)

Download
========

* [Version 1.2.0](https://github.com/d-lopes/dmsplus/releases/tag/1.2.0)
* [older versions](https://github.com/d-lopes/dmsplus/releases)

System Requirements
===================

* min. 2 CPU (2.00GHz)
* min. 2 GB RAM
* pre-installed docker and docker-compose

_Hint: I run this on my Synology DS218+ with 4GB extra RAM without any issues._

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
