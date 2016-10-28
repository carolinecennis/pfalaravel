all: composer-install vendor-install

composer-install:
	curl -s http://getcomposer.org/installer | php -- --install-dir=bin

vendor-install:
	php bin/composer.phar install

clean-env:
	php -r "copy('.env.example', '.env');"

migrate:
	php artisan migrate

assets:
	bin/gulp

assets-watch:
	bin/gulp watch
