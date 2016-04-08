tests:
	./vendor/phpunit/phpunit/phpunit -c .

tests_coverage:
	./vendor/phpunit/phpunit/phpunit -c . --coverage-html coveragema