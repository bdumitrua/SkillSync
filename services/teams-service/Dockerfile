# Используйте базовый образ SDK для использования dotnet watch
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS base
WORKDIR /app

# Настройка окружения для разработки
ENV DOTNET_WATCH_SUPPRESS_MSBUILD_INCREMENTALISM=true \
    DOTNET_USE_POLLING_FILE_WATCHER=true \
    NUGET_XMLDOC_MODE=skip \
    DOTNET_WATCH_AUTO_RELOAD=true 

# Копирование csproj и восстановление зависимостей
COPY *.csproj ./
RUN dotnet restore

# Копирование остальных файлов проекта
COPY . ./

EXPOSE 80

ENTRYPOINT ["dotnet", "watch", "run", "--no-launch-profile"]
