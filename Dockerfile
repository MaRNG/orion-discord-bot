FROM dockette/php:8.1

COPY . /srv/orion
WORKDIR /srv/orion

CMD [ "php", "./run.php" ]
