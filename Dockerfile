FROM ubuntu
MAINTAINER mwaeckerlin

VOLUME /usr/share/nginx/html
ENV PHP_PATH "/usr/share/nginx/html"

RUN apt-get update -y          
RUN apt-get install -y php5-fpm php5-ldap ca-certificates
RUN sed -i 's/^listen *=.*/listen = 9000/' /etc/php5/fpm/pool.d/www.conf
RUN sed -i 's,^.*access.log *=.*,access.log = /var/log/php5-fpm.log,' /etc/php5/fpm/pool.d/www.conf
RUN echo "catch_workers_output = yes" >>  /etc/php5/fpm/pool.d/www.conf
ADD index.php ${PHP_PATH}/index.php

EXPOSE 9000
CMD ( echo "[www]"; env | sed -n "s/\([^=]*\)=\(.*\)/env[\1]='\2'/p" ) > /etc/php5/fpm/pool.d/env.conf && php5-fpm -F
