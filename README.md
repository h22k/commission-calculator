# Commission Calculator

## Running the application
There are two ways of doing this, it is completely up to you. I will be explaining both down below. 
**DON'T FORGET TO FILL `EXCHANGE_API_ACCESS_KEY` IN .ENV OR .ENV.EXAMPLE (.ENV FILE WILL BE CREATED IF YOU DECIDED TO FOLLOW MAKEFILE SETUP OPTION) FILE, OTHERWISE THE APPLICATION WON'T RUN.**
### Setup with Makefile
You also have two options for building & running the app using this option. But first let me clear it up for you, 
what will happen under the hood, if you choose this way.
#### Docker Option
1. Creating `.env` file if there is not.
2. Building docker image
3. Running the tests before application (if you choose the directive that contains `test`)
4. Waiting for 3 seconds.
5. Then running the application.

#### PHP Option
Important: This application is using **PHP 8.2**, so make sure that your PHP version is 8.2 or higher.
If you don't have, it's better to choose the docker way.
1. Installing packages with `composer` if there is no vendor directory.
2. Creating `.env` file if there is not.,
3. Running the tests before application (if you choose the directive that contains `test`)
4. Waiting for 3 seconds.
5. Then running the application.


#### 1.Makefile - Docker
If you are running the app for the first time you can choose one of two directives:

* If you want to run the tests with the application: `make docker-cold-start-test` 
* If you want to run just the application: `make docker-cold-start`

#### 2.Makefile - PHP
If you are running the app for the first time you can choose one of two directives:

* If you want to run the tests with the application: `make php-cold-start-test`
* If you want to run just the application: `make php-cold-start`

### Setup manually
Important: This application is using **PHP 8.2**, so make sure that your PHP version is 8.2 or higher. 
If you don't have, it's better to choose the docker way.
Please follow these directions:
1. DOCKER WAY
    1. `docker build -t commission-calculator-app .` - building docker image from Dockerfile.
    2. `docker run -it commission-calculator-app` - running the container from image you just built.
    3. `docker run --rm commission-calculator-app /usr/commission-calculator/vendor/bin/phpunit tests/` - running for the tests.
2. PHP WAY
   1. `composer install` - installing packages
   2. `cp .env.example .env` - creating .env file based from .env.example
   3. Fill `EXCHANGE_API_ACCESS_KEY` key in `.env` file - the program won't run if you don't.
   4. `php app.php input.txt` - filename is optional, it's set to `input.txt` as default. But of course you can use whatever your heart desires.
   5. `vendor/bin/phpunit tests/` - for running the tests.


