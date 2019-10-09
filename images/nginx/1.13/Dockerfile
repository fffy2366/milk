FROM nginx:1.13
MAINTAINER Frank <fengxuting@gmail.com>

RUN groupadd -g 1000 app \
 && useradd -g 1000 -u 1000 -d /var/www -s /bin/bash app
RUN touch /var/run/nginx.pid
RUN mkdir /sock

RUN apt-get update && apt-get install -y openssl apt-utils
RUN mkdir /etc/nginx/certs

RUN chown -R app:app /etc/nginx/certs /sock /var/cache/nginx/ /var/run/nginx.pid /sock

COPY ./conf/nginx.conf /etc/nginx/
COPY ./conf/default.conf /etc/nginx/conf.d/

EXPOSE 8000

USER app:app

VOLUME /var/www

WORKDIR /var/www/html