FROM registry.dev.torche.id/torche/apache:php-8.1.19

MAINTAINER Asep Muhamad <asep.muhamad@torche.co.id>

WORKDIR /var/www/html
COPY --chown=www-data . /var/www/html

USER root
#RUN chown -R 1001 /run && \
#  chown -R 1001 /usr/lib/nginx && \
#  chown -R 1001 /var/log/nginx
