FROM php:7.3-cli
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
CMD [ "php", "./script.php" ]