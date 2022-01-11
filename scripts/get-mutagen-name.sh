CONTAINER_NAME=$(docker inspect -f '{{.Name}}' $(docker-compose ps -q app) | cut -c2-)

# Need to replace _ by - for mutagen.
echo "$CONTAINER_NAME" | sed -E 's/_+/-/g'
