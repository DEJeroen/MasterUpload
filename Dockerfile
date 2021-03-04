FROM debian:stretch
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update
RUN apt-get install apache2 php -y
RUN apt-get install mariadb-server php-mysql -y
RUN apt-get install phpmyadmin -y
CMD phpenmod mysqli && ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin

CMD mysqld_safe\
& sleep 10 \
&& mysql -e "CREATE DATABASE muziek_project /*\!40100 DEFAULT CHARACTER SET utf8 */;"\
&& mysql -e "CREATE USER newuser@localhost IDENTIFIED BY 'password';"\
&& mysql -e "GRANT ALL PRIVILEGES ON muziek_project.* TO 'newuser'@'localhost';"\
&&  mysql -e "FLUSH PRIVILEGES;"\
&& mysql muziek_project < /var/www/html/muziek_project.sql \
&& echo "Done" \
& sleep 10 && apachectl -D FOREGROUND
