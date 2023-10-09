APP_DOCKER_TAG=commission_calculator

TEST_COMMAND=tests/ --colors --testdox --display-warnings

docker-build:
	docker build -t $(APP_DOCKER_TAG) .
docker-cold-start-test:
	make create_env && make docker-build && make docker-test && echo "tests are finished -----------------------------" && echo "application will be running in 3 seconds" && sleep 3 && make docker-start
docker-cold-start:
	make docker-build && make docker-start
docker-start:
	docker run -it $(APP_DOCKER_TAG)
docker-test-detail:
	docker run --rm $(APP_DOCKER_TAG) /usr/commission-calculator/vendor/bin/phpunit $(TEST_COMMAND)
docker-test:
	docker run --rm $(APP_DOCKER_TAG) /usr/commission-calculator/vendor/bin/phpunit tests/
create_env:
	[ -f ./.env ] || cp .env.example .env
php-cold-start-test:
	[ -d ./vendor ] || composer install && make create_env && make php-test && echo "tests are finished -----------------------------" && echo "application will be running in 3 seconds" && sleep 3 && php app.php input.txt
php-cold-start:
	[ -d ./vendor ] || composer install && make create_env && php app.php input.txt
php-test-detail:
	vendor/bin/phpunit $(TEST_COMMAND)
php-test:
	vendor/bin/phpunit tests/

