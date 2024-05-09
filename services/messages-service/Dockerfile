FROM node:20.8

WORKDIR /app

COPY package.json /app/

RUN npm install

COPY . .

EXPOSE 3000

# Команда для запуска вашего приложения
CMD ["npm", "run", "dev"]
