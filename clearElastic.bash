# Остановка всех контейнеров
docker-compose down

# Удаление всех контейнеров и образов, связанных с Elasticsearch
docker rm -f $(docker ps -a -q --filter "ancestor=elasticsearch:8.10.1")

# Удаление всех томов Docker, связанных с Elasticsearch
docker volume rm $(docker volume ls -q --filter "name=elasticsearch")

# Удаление всех неиспользуемых томов Docker
docker volume prune -f

# Пересборка и перезапуск контейнеров
docker-compose up --build
