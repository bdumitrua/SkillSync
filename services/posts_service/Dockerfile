# Используем базовый образ Python
FROM python:3.11

ENV PYTHONUNBUFFERED 1

WORKDIR /app

RUN apt-get update
RUN apt-get install gcc default-libmysqlclient-dev -y

COPY requirements.txt /app/
RUN pip install --upgrade pip && pip install -r requirements.txt

COPY . /app/

EXPOSE 8000

CMD ["python", "manage.py", "runserver", "0.0.0.0:8000"]
