# Используйте официальный образ .NET SDK для сборки приложения
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build-env
WORKDIR /app

# Копируйте csproj и восстанавливайте зависимости
COPY *.csproj ./
RUN dotnet restore

# Копируйте остальные файлы проекта и собирайте приложение
COPY . ./
RUN dotnet publish -c Release -o out

# Используйте официальный образ .NET Runtime для запуска приложения
FROM mcr.microsoft.com/dotnet/aspnet:8.0
WORKDIR /app
COPY --from=build-env /app/out .

# Указываем контейнеру, что он может принимать трафик на порту 80
EXPOSE 80
ENTRYPOINT ["dotnet", "teams-service.dll"]
