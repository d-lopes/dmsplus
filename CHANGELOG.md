# Change Log

This overview contains all changes done for the dms+ appliaction since its first release (April 2020).
 Changes are listed by release versions which follow [semantic versioning](https://semver.org).  

## unreleased changes

### Features

### Bugfixes and improvements

## 02.04.2021 - 2.0.1

### Features

### Bugfixes and improvements
- fix bug introduced with late refactoring of DocumentHelper class

## 02.04.2021 - 2.0.0

### Features
- introduce tagging functionality for documents  - see [issue #6](https://github.com/d-lopes/dmsplus/issues/6)
- document dates made available as filters in the document search - see [issue #14](https://github.com/d-lopes/dmsplus/issues/14)
- identification of duplicate files/documents - see [issue #24](https://github.com/d-lopes/dmsplus/issues/24)
- Redesign the UI to provide a better look an feel (came naturally with Larvacel 8 migration)

### Bugfixes and improvements
- Migration to Laravel 8.5
- Bump version to OCRmyPDF v11.7
- restart script optimizations

## 25.12.2020 - 1.2.0

### Features
- added high-level statistics for documents - see [issue #7](https://github.com/d-lopes/dmsplus/issues/7)
- added deletion functionality for documents - see [issue #10](https://github.com/d-lopes/dmsplus/issues/10)
- added web upload functionality for documents - see [issue #11](https://github.com/d-lopes/dmsplus/issues/11)

### Bugfixes and improvements
- read admin user credentials from env variables in order to fix [issue #4](https://github.com/d-lopes/dmsplus/issues/4)
- upgrade to OCRMyPDF v11.4.0 [issue #12](https://github.com/d-lopes/dmsplus/issues/12)
- fix upload of corrupt PDF files to DMS application [issue #22](https://github.com/d-lopes/dmsplus/issues/22)

## 02.05.2020 - 1.1.3

### Bugfixes and improvements
- automatic ingestion of new PDF files increased to every hour in order to mitigate [issue #12](https://github.com/d-lopes/dmsplus/issues/12)

## 02.05.2020 - 1.1.2

### Bugfixes and improvements
- fix document upload error due to pagebreaks in document content which have been reintroduced with fix for [issue #3](https://github.com/d-lopes/dmsplus/issues/3). 

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
