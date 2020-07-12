FROM ubuntu:latest
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update -y
RUN apt-get upgrade -y
RUN apt-get install -y apache2 
RUN apt-get install -y php 
RUN apt-get install -y php-dev 
RUN apt-get install -y php-mysql 
RUN apt-get install -y libapache2-mod-php 
RUN apt-get install -y php-curl 
RUN apt-get install -y php-json 
RUN apt-get install -y php-common 
RUN apt-get install -y php-mbstring 
RUN apt-get install -y composer
RUN apt-get install -y software-properties-common
RUN apt-get install -y php7.4
RUN apt-get install -y php7.4-gd

COPY 000-default.conf /etc/apache2/sites-available/

CMD ["apachectl","-D","FOREGROUND"]
RUN a2enmod rewrite

EXPOSE 80
EXPOSE 443