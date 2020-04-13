FROM jbarlow83/ocrmypdf:v9.7.0

LABEL maintainer="tech@dlopes.de"

# install needed dependencies
RUN apt-get update && apt-get install -y cron curl jq

# setup directories for conversion of scanned (raw) PDFs to OCR'ed PFDs
RUN mkdir /var/run/inbox \
	&& mkdir /var/run/tmp \
	&& mkdir /var/run/err \
	&& mkdir /var/run/logs

# copy scripts for automatic watching of inbox directory and make them executable
COPY src/upload.sh src/watch.sh docker-entrypoint.sh /home/scanner/
RUN chmod +x /home/scanner/watch.sh /home/scanner/upload.sh /home/scanner/docker-entrypoint.sh

# set home directory
WORKDIR "/home/scanner"

# allow to mount the directory structure from outside the container
VOLUME ["/var/run/inbox", "/var/run/err", "/var/run/logs"]

# overwrite entry point
ENTRYPOINT ["/home/scanner/docker-entrypoint.sh"]