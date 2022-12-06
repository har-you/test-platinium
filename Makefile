.PHONY: help
.DEFAULT_GOAL = help

dc = docker-compose
de = $(dc) exec
composer = $(de) php memory_limit=1 /usr/local/bin/composer

## —— Docker 🐳  ———————————————————————————————————————————————————————————————
.PHONY: tests
tests:
	$(dc) exec php bash -c 'vendor/bin/phpunit'

.PHONY: install
install:	## Installation du projet
	$(dc) up -d
	$(de) php bash -c 'vendor/bin/phpunit'

.PHONY: install
install:	## Installation du projet
	$(dc) up -d
	$(de) php bash -c 'composer install'

.PHONY: build
build:	## Lancer les containers docker au start du projet
	$(dc) up -d
	$(dc) exec php bash -c 'composer install'
	$(dc) exec php bash -c 'bin/console d:m:m && bin/console d:f:l'

.PHONY: dev
dev:	## start container
	$(dc) up -d

.PHONY: in-dc
in-dc:	## connexion container php
	$(de) php bash

.PHONY: delete
delete:	## delete container
	$(dc) down
	$(dc) kill
	$(dc) rm
