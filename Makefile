make:

composer:
	curl -sS https://getcomposer.org/installer | php

phpunit:
	curl -o phpunit.phar http://pear.phpunit.de/get/phpunit-3.7.19.phar 
	chmod +x phpunit.phar

test:
	./phpunit.phar
