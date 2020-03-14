#!/bin/bash

name=$(basename `pwd`)

if [ -z $1 ]; then
	echo "Параметр сборки контейнера не задан";
elif [ -z $name ]; then
	echo "Имя проекта не задано"
elif [ $1 = "run" ]; then
	echo "==> Остановка контейнера $name <=="
	sudo docker stop $name;
	echo "==> Удаление контейнера $name <=="
	sudo docker rm $name;
	echo "==> Сборка образа $name <=="
	sudo docker build --no-cache -t $name .
	sudo docker run -d --name $name -P -it $name /usr/sbin/apache2ctl -D FOREGROUND
	sudo docker ps;
	sudo docker logs $name;
elif [ $1 = "server" ]; then
	echo "==> Создание сервера $name <=="
	 [ $2 = "" ] && server="html" || server="$2"
#	 [ $3 = "" ] && path="/var/www/html" || path="$3" 
	sudo docker run -d --restart=always --name $server -v /var/www/:/var/www/ -p 80:80 -p 8080:8080 -p 1022:22 -p 443:443 -p 3306:3306 $name /usr/sbin/apache2ctl -D FOREGROUND
elif [ $1 = "start" ]; then
	sudo docker start $name;
	sudo docker ps;
elif [ $1 = "bash" ]; then
	sudo docker exec -it $name /bin/bash
elif [ $1 = "ps" ]; then
	echo "==> Список запущенных контейнеров <==";
	sudo docker ps
elif [ $1 = "psa" ]; then
	echo "==> Список всех контейнеров $name <==";
	sudo docker ps -a
elif [ $1 = "images" ]; then
	echo "==> Список образов <==";
	sudo docker images
elif [ $1 = "stop" ]; then
	echo "==> Остановка контейнера $name <==";
	sudo docker stop $name;
	sudo docker ps;
elif [ $1 = "rm" ]; then
	echo "==> Удаление образа $name <==";
	sudo docker stop $name;
	sudo docker rm $name;
elif [ $1 = "rmi" ]; then
	echo "==> Удаление образа $name <==";
	sudo docker stop $name;
	sudo docker rm $name;
	sudo docker rmi $name;
elif [ $1 = "logs" ]; then
	echo "==> Лог докер <==";
	sudo docker logs $name;
elif [ $1 = "rma" ]; then
	echo "==> Удаление всех контейнеров <=="
	sudo docker stop $(sudo docker ps -q);
	sudo docker rm $(sudo docker ps -a -q);
	sudo docker rmi $(sudo docker images -q);
else
	echo "Параметр контейнера не задана";
fi

