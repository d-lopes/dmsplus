Change Log
==========

This overview contains all changes done for the dms+ appliaction since its first release (April 2020).
 Changes are listed by release versions which follow [semantic versioning](https://semver.org).  

## 02.05.2020 - 1.1.1

### Bugfixes and improvements
- Nginx CLIENT_MAX_BODY_SIZE increased to 10 MB in order to fix [issue #2](https://github.com/d-lopes/dmsplus/issues/2). 
- file contents reformatting prior to document creation optimized in order to fix [issue #3](https://github.com/d-lopes/dmsplus/issues/3). 
- sample files for OCR scan added/reworked
- introduced test script to preview OCR ouput

## 01.05.2020 - 1.1.0

### Features
- introduction of rudimental document workflow / state machine 
- single document view reworked in order to allow status changes and file uploads 
- bump versions of JQuery and SASS 

### Bugfixes and improvements
- fix: document creation via API for meta data containing hyphens

## 26.04.2020 - 1.0.4

### Bugfixes and improvements
- fix: access issues on to static files

## 14.04.2020 - 1.0.3

### Bugfixes and improvements
- fix: startup issues on vanialla web_data volume

## 13.04.2020 - 1.0.2

### Bugfixes and improvements
- further adjustmenst to docker-compose.yml and README.md files

## 13.04.2020 - 1.0.1

### Bugfixes and improvements
- fix: local developement not working smoothly

## 13.04.2020 - 1.0.0 (MVP)

### Features
- automatically ingesting PDF files OCR scan utility  
- automatic upload of OCR'ed files to REST API
- Webapplication to allow viewing scan and OCR results