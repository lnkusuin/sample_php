FROM php:8.5-cli
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
CMD [ "php", "./script.php" ]