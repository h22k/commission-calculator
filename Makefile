APP_DOCKER_TAG=commission_calculator

cold-start:
	docker build -t $(APP_DOCKER_TAG) . && docker run $(APP_DOCKER_TAG)
start:
	docker run $(APP_DOCKER_TAG)