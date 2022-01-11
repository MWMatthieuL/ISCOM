COMPOSE=docker-compose -f docker-compose.yml -f docker-compose-dev.yml
COMPOSE_MAC=$(COMPOSE) -f docker-compose-sync.yml
EXEC=$(COMPOSE) exec app
CONSOLE=$(EXEC) bin/console
ENVIRONMENT=$(shell bash ./scripts/get-env.sh)
SHELL := /bin/bash
MUTAGEN_NAME=$(shell bash ./scripts/get-mutagen-name.sh)

.PHONY: start up perm db cc ssh vendor stop rm

start: up perm vendor db cc perm

up:
	docker kill $$(docker ps -q) || true
ifeq ($(ENVIRONMENT),Mac)
	$(COMPOSE_MAC) build
	$(COMPOSE_MAC) up -d
	bash ./scripts/start-macos.sh
else
	$(COMPOSE) build
	$(COMPOSE) up -d
endif
	$(EXEC) mkdir -p var/log && $(EXEC) mkdir -p var/cache

stop:
ifeq ($(ENVIRONMENT),Mac)
	$(COMPOSE_MAC) stop
	$(COMPOSE_MAC) kill
	mutagen sync pause $(MUTAGEN_NAME)
else
	$(COMPOSE) stop
	$(COMPOSE) kill
endif

rm:
	make stop
ifeq ($(ENVIRONMENT),Mac)
	$(COMPOSE_MAC) rm
	mutagen sync terminate $(MUTAGEN_NAME)
else
	$(COMPOSE) rm
endif

vendor: wait-for-db
	$(EXEC) composer install -n
	$(EXEC) npm install
	make perm

ssh:
	$(EXEC) bash

run:
	$(EXEC) $(c)

sf:
	$(EXEC) bin/console $(c)

# Databases
db: wait-for-db
	$(EXEC) bin/console doctrine:database:drop --if-exists --force
	$(EXEC) bin/console doctrine:database:create --if-not-exists
	$(EXEC) bin/console doctrine:schema:update --force
	$(EXEC) rm -rf public/uploads/*

db-migrate:
	$(EXEC) bin/console doctrine:migration:migrate

# Assets
assets:
	$(EXEC) bin/console assets:install
	$(EXEC) npm run dev

assets-watch:
	$(EXEC) bin/console assets:install
	$(EXEC) npm run watch

# Permission
perm:
ifeq ($(ENVIRONMENT),Linux)
	sudo chown -R www-data:$(USER) .
	sudo chmod -R g+rwx .
else
	$(EXEC) chown -R www-data:root .
	$(EXEC) chown -R www-data:root public/
endif

# Cache
cc:
	$(EXEC) bin/console c:cl --no-warmup
	$(EXEC) bin/console c:warmup

# Wait commands
wait-for-db:
	$(EXEC) php -r "set_time_limit(60);for(;;){if(@fsockopen(\"db\",3306)){break;}echo \"Waiting for DB\n\";sleep(1);}"
