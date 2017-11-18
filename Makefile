cs:
	php-cs-fixer fix --verbose

test:
	vendor/bin/phpunit -c phpunit.xml.dist
