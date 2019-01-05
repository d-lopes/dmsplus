FROM ubuntu:18.04

#################
# Version 0.9.8 #
#################

# set environment variables for language, locale, encoding, timezone and mysql_root pwd
ENV LANG=en_US.UTF-8
ENV LC_ALL=$LANG
ENV LANGUAGE=en_US:en
ENV TIME_ZONE=UTC

ENV MYSQL_ROOT_PASS=s3cret

# install time zone and locales support and make frontend non-interactive
RUN apt-get update \
	&& apt-get install -y --no-install-recommends apt-utils locales tzdata \
	&& locale-gen --purge $LANG \
	&& echo -e 'LANG="$LANG"\nLANGUAGE="$LANGUAGE"\n' > /etc/default/locale \
	&& echo '$TIME_ZONE' > /etc/timezone \
	&& export DEBIAN_FRONTEND=noninteractive \
	&& dpkg-reconfigure -f noninteractive tzdata 

# -------------------------------------------
# OCRMYPDF installation
# --------------------------------------

# get dependencies with APT package manager
RUN apt-get update && apt-get install -y --no-install-recommends \
  build-essential autoconf automake libtool debconf-utils \
  libleptonica-dev \
  zlib1g-dev \
  libexempi3 \
  ocrmypdf \
  pngquant \
  python3-pip \
  python3-venv \
  tesseract-ocr \
  tesseract-ocr-deu \
  tesseract-ocr-eng \
  unpaper \
  wget
  
# Compile and install jbig2
# Needs libleptonica-dev, zlib1g-dev
RUN mkdir jbig2 \
  && wget -q https://github.com/agl/jbig2enc/archive/0.29.tar.gz -O - | \
      tar xz -C jbig2 --strip-components=1 \
  && cd jbig2 \
  && ./autogen.sh && ./configure && make && make install \
  && cd .. \
  && rm -rf jbig2

# setup python virtual environment
RUN python3 -m venv --system-site-packages /appenv

# This installs the latest binary wheel instead of the code in the current
# folder. Installing from source will fail, apparently because cffi needs
# build-essentials (gcc) to do a source installation
# (i.e. "pip install ."). It's unclear to me why this is the case.
RUN . /appenv/bin/activate; \
  pip install --upgrade pip \
  && pip install --upgrade ocrmypdf \
  && pip install --upgrade doc2text
  
# -------------------------------------------
# SeedDMS installation
# --------------------------------------

# set working directory
WORKDIR /DATA

# setup directories for DMS and conversion of scanned (raw) PDFs to OCR'ed PFDs
RUN mkdir /DATA/seeddms \
	&& mkdir /DATA/seeddms/content \
	&& mkdir /DATA/seeddms/lucene \
	&& mkdir /DATA/seeddms/staging \
	&& mkdir /DATA/seeddms/drop \
	&& mkdir /DATA/seeddms/drop/admin \
	&& mkdir /DATA/seeddms/cache \
	&& mkdir /DATA/seeddms/bkp \
	&& mkdir /DATA/ocrmypdf \
	&& mkdir /DATA/ocrmypdf/input \
	&& mkdir /DATA/ocrmypdf/output \
	&& mkdir /DATA/ocrmypdf/bkp \
	&& mkdir /DATA/ocrmypdf/bkp/uploaded \
	&& mkdir /DATA/ocrmypdf/err \
	&& mkdir /DATA/logs \
	&& mkdir /DATA/tmp
	
# store 3rd-party resources and other stuff in temporary folder
COPY src/3rd-party/SeedDMS_Core-5.1.8.tgz /DATA/tmp/SeedDMS_Core-5.1.8.tgz
COPY src/3rd-party/SeedDMS_Lucene-1.1.13.tgz /DATA/tmp/SeedDMS_Lucene-1.1.13.tgz
COPY src/3rd-party/SeedDMS_Preview-1.2.9.tgz /DATA/tmp/SeedDMS_Preview-1.2.9.tgz
COPY src/3rd-party/SeedDMS_SQLiteFTS-1.0.10.tgz /DATA/tmp/SeedDMS_SQLiteFTS-1.0.10.tgz
COPY src/3rd-party/seeddms-5.1.8.tar.gz /DATA/tmp/seeddms-5.1.8.tar.gz
COPY src/3rd-party/vendor.tar.gz /DATA/tmp/vendor.tar.gz
COPY src/create-database.sql /DATA/tmp/create-database.sql
COPY src/setup-database.sh /DATA/tmp/setup-database.sh
COPY src/get-scans-folder-ID.sql /DATA/tmp/get-scans-folder-ID.sql
COPY src/add-env-vars.sh /DATA/tmp/add-env-vars.sh
RUN chmod +x /DATA/tmp/setup-database.sh \
	&& chmod +x /DATA/tmp/add-env-vars.sh

# extract webapp for SeedDMS
# ATTENTION: this must happen right here in order to allow mysql to use the included script to create the DB tables
RUN tar -xvzf /DATA/tmp/seeddms-5.1.8.tar.gz -C /DATA/tmp

# prepare mysql installation: set root passwort
RUN echo 'mysql-server mysql-server/root_password password ${MYSQL_ROOT_PASS}' | debconf-set-selections \
	&& echo 'mysql-server mysql-server/root_password_again password ${MYSQL_ROOT_PASS}' | debconf-set-selections
	
# install mysql for SeedDMS
RUN apt-get install -y --no-install-recommends mysql-server mysql-client

# setup SeedDMS database and create needed tables
RUN /DATA/tmp/setup-database.sh

# install Apache2, PHP and PEAR and other low-level dependencies for SeedDMS
RUN apt-get install -y --no-install-recommends \
  php libapache2-mod-php php-mysql \
  php-pear \
  apache2 \
  poppler-utils \
  catdoc \
  html2text \
  id3 \
  imagemagick \
  a2ps \
  cron
  
# Install required PEAR components:
RUN pear channel-discover pear.dotkernel.com/zf1/svn
RUN pear install /DATA/tmp/SeedDMS_Core-5.1.8.tgz \
	&& pear install /DATA/tmp/SeedDMS_Lucene-1.1.13.tgz \
	&& pear install /DATA/tmp/SeedDMS_Preview-1.2.9.tgz \
	&& pear install /DATA/tmp/SeedDMS_SQLiteFTS-1.0.10.tgz \
	&& pear install mail Net_SMTP Auth_SASL2 mail_mime \
	&& pear install Log \
	&& pear install zend/zend

# modify apache configuration to point to the SeedDMS webapp
# copy customized seedDMS configuration
# fix missing extensions file
COPY src/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY src/settings.xml /DATA/tmp/seeddms-5.1.8/conf.template/settings.xml
COPY src/extensions.php /DATA/seeddms/cache/extensions.php

# enable mod rewrite and restart apache
# prepare config for immediate use (remove installation file)
# move webapp for SeedDMS to the right place
# extract vendor folder into PHP folder in order to fix missing dependencies
RUN a2enmod rewrite \
	&& mv /DATA/tmp/seeddms-5.1.8/conf.template /DATA/tmp/seeddms-5.1.8/conf \
	&& mv /DATA/tmp/seeddms-5.1.8 /var/www/html \
	&& chmod 777 -R /var/www/html/seeddms-5.1.8 \
	&& chmod 777 -R /DATA \
	&& tar -xvzf /DATA/tmp/vendor.tar.gz -C /usr/share/php
	
# excute script to set necessary environment variables for PDF file ingestion
RUN /DATA/tmp/add-env-vars.sh

# Remove the junk, including the source version of application and all scripts since we have already installed everything
RUN rm -rf /tmp/* /var/tmp/* /DATA/tmp \
	&& apt-get remove -y build-essential autoconf automake libtool \
	&& apt-get autoremove -y \
	&& apt-get autoclean -y

# copy scripts for startup, automatic watching of input directory and cronjobs
COPY src/watcher.sh /root/watcher.sh
COPY src/startup.sh /root/startup.sh
COPY src/crontab /etc/crontab 

# make scripts executable
RUN chmod +x /root/watcher.sh \
	&& chmod +x /root/startup.sh
	
# -------------------------------------------
# setup environment
# --------------------------------------
		
# allow to mount the work directory from outside the container
VOLUME ["/DATA"]

# expose HTTP and HTTPS ports of the web server
EXPOSE 80 443
	
# ---------------
# start system 
# ---------------

# start web server, database etc. 
CMD ["/root/startup.sh"]
