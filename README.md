DESCRIPTION
-------------------
<p>Приложение запускается как docker-контейнер</p>

<p>Приложение разделено на два модуля. Модули можно использовать независимо друг от друга. 
Каждый модуль имеет собственное подключение к базе данных, а так же отдельные базы sqlite для каждого модуля.
Каждый модуль имеет собственное подключение к серверу rabbitmq.</p>

<p>Один модуль atm генерирует дынные, сохраняет их в таблицу order и отправляет во второй модуль.
Передача возможна либо через http либо через сервер rabbitmq</p>

<p>Второй модуль wallet принимает данные, вычисляет сумму с учетом комиссии, и сохраняет в две таблицы.
В таблицу user_wallet сохраняет сумму из входных данных. 
В таблицу user_total записывает инкриментированную на пришедшее значение сумму.
</p>

<p>Подробнее о запуске каждого модуля смотрите в раздеде RUN</p>

<p>Так же реализован Веб-Сервер для вывода в html таблицах содержимого таблиц БД.</p>

<p>В папке bin находятся подготовленные скрипты для удобства запуска контейнеров с приложением, установки composer пакетов внутри контейнера, а так же для запуска модулей приложения внутри контейнера. Так же есть скрипт для запуска сервера rabbitmq в отдельном docker контейнере</p>

<p>Настройки параметров для запуска контейнера находятся в файле .envrc, который создается при первом запуске и который потом можно изменить. При первом запуске будет предложенно ввести ip адрес интерфейса, 
по коророму будут доступны api-server и web-site (так же будет предложен адрес найденный с помощью автоопределения).</p>

INSTALL
-------------------

<code>$ sudo apt install docker docker-compose</code> for Debian/Ubuntu or <code>$ sudo dnf install docker docker-compose</code> for Fedora/RedHat

<code>$ git clone https://github.com/matveevartem/ds.git <project_dir></code>

<code>$ cd <project_dir></code>

для Linux<br>
<code>$ chmod +x bin/*</code><br>
<code>$ sudo bin/run</code> <small>(First console)</small><br>
для Windows<br>
<code>> bin/run.bat</code> <small>(First console)</small><br>

<code>$ sudo docker exec -it atm_wallet bin/init</code> <small>(Second console)</small>

RUN
-------------------

<h3>RABBITMQ SERVER <small>(First console)</small></h3>

для Linux<br>
<code>$ sudo bin/rabbitmq -f</code>, <code>$ sudo bin/rabbitmq --foreground</code> - Запуск в интерактивном режиме 
<code>$ sudo bin/rabbitmq -b</code>, <code>$ sudo bin/rabbitmq --background</code> - Запуск в фоновом режиме 
для Windows<br>
<code>> bin/rabbitmq.bat -f</code> - Запуск в интерактивном режиме <br>
<code>> bin/rabbitmq.bat -b</code> - Запуск в фоновом режиме 

<h3>APPLICATION SERVERS MODULES <small>(Second console)</small></h3>

If still not runnig, then execute

для Linux<br>
<code>$ sudo bin/run</code> <small></small><br>
для Windows<br>
<code>> bin/run.bat</code> <small></small><br>

This command will run  apache2 server (in background mode) and wallet api server (in foreground mode with output to console) into docker container.

<h3>APPLICATION SENDERS MODULES <small>(Third console)</small></h3>

<strong>HTTP</strong>

default loop delay 20<br>
<code>$ sudo docker exec -it atm_wallet bin/send -h</code>,<br>
<code>$ sudo docker exec -it atm_wallet bin/send --http</code>
<br>or<br>
custom loop delay {seconds}<br>
<code>$ sudo docker exec -it atm_wallet bin/send -h {seconds}</code>,<br>
<code>$ sudo docker exec -it atm_wallet bin/send --http {seconds}</code>

<strong>AMQP</strong>

default loop delay 20)<br>
<code>$ sudo docker exec -it atm_wallet bin/send -q</code>,<br>
<code>$ sudo docker exec -it atm_wallet bin/send --queue</code> 
<br>or<br>
custom loop delay {seconds}<br>
<code>$ sudo docker exec -it atm_wallet bin/send -q {seconds}></code>,<br>
<code>$ sudo docker exec -it atm_wallet bin/send --queue {seconds}></code> 

VIEWING TABLES VIA WEB
-------------------

Open site in browser <link>http://localhost:8081</link>

For reset databases run<br>
<code>$ sudo docker exec -it atm_wallet bin/install -d</code>
<br>or<br>
<code>$ sudo docker exec -it atm_wallet bin/install --db</code>

REQUIREMENTS
------------

docker

docker-compose
