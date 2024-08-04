include .env
export

DIR_PATH := $(dir $(realpath $(lastword $(MAKEFILE_LIST))))

#####################
# Управление
#####################

build:
	$(info Сборка проекта)
	@docker-compose build

deps:
	$(info Установка зависимостей)
	@docker exec -it ${DOCKER_PHP_CONTAINER_NAME} composer install

start:
	$(info Запуск контейнеров)
	@docker-compose up -d

stop:
	$(info Остановка контейнеров)
	@docker-compose down

generate_key:
	docker exec -it ${DOCKER_PHP_CONTAINER_NAME} php artisan key:generate

init:
	$(info Make: Сборка проекта)
	@make -s build
	@make -s start
	@make -s deps
	@make -s generate_key
	@make -s migrating

#####################
# ssh подключения
#####################

ssh_php:
	$(info Подключение к командой строке контейнера php)
	@docker exec -it ${DOCKER_PHP_CONTAINER_NAME} sh

ssh_mysql:
	$(info Подключение к командой строке контейнера mysql)
	@docker exec -it ${DOCKER_DB_CONTAINER_NAME} bash

#####################
# Миграции
#####################

migration:
	$(info Создание файла миграции)
	@docker exec -it ${DOCKER_PHP_CONTAINER_NAME} php artisan make:migration

migrating:
	$(info Применение доступных миграций)
	@docker exec -it ${DOCKER_PHP_CONTAINER_NAME} php artisan migrate

migrating_and_seed:
	$(info Применение доступных миграций и заполнение таблиц)
	@docker exec -it ${DOCKER_PHP_CONTAINER_NAME} php artisan migrate --seed





