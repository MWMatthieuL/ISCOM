#!/usr/bin/env bash

PROJECT_PATH=$(pwd)
REMOTE_SRC=/app

ENVIRONMENT=$(bash ./scripts/get-env.sh)
MUTAGEN_NAME=$(bash ./scripts/get-mutagen-name.sh)
CONTAINER_NAME=$(docker inspect -f '{{.Name}}' $(docker-compose ps -q app) | cut -c2-)

if [[ ${ENVIRONMENT} = "Mac" ]]
then
    # check if there's already a session
    mutagen list | grep "${CONTAINER_NAME}${REMOTE_SRC}" &> /dev/null

    if [ $? != 0 ]; then
        mutagen sync create \
            -c ${PROJECT_PATH}/mutagen.yml \
            -n ${MUTAGEN_NAME} \
            ${PROJECT_PATH} docker://${CONTAINER_NAME}$REMOTE_SRC
    else
        mutagen sync resume ${MUTAGEN_NAME}
    fi
fi
