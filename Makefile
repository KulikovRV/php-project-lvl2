install:
		composer install

validate:
		composer validate

lint:
		composer exec --verbose phpcs -- --standard=PSR12 src bin
		composer exec --verbose phpstan -- --level=8 analyse src

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

