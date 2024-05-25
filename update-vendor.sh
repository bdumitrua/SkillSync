#!/bin/bash

# Получаем ID контейнера
CONTAINER_ID=$(docker-compose ps -q main-service)

# Копируем папку vendor на локальную машину
docker cp $CONTAINER_ID:/app/vendor ./services/main-service/
