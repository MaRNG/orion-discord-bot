FROM dockette/php:8.1

COPY . /srv/orion

RUN composer install --prefer-dist --working-dir=/srv/orion

WORKDIR /srv/orion

CMD [ "php", "./run.php" ]
