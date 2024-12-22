# Використовуємо офіційний образ PHP
FROM php:7.4-apache

# Встановлюємо розширення для роботи з MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# RUN apt-get update && \
#    apt-get install -y wget unzip && \
#    wget https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.zip && \
#    unzip phpMyAdmin-latest-all-languages.zip -d /var/www/html/ && \
#    mv /var/www/html/phpMyAdmin-*-all-languages /var/www/html/phpmyadmin && \
#    rm phpMyAdmin-latest-all-languages.zip


# Копіюємо файли вашого сайту в директорію, яку обслуговує Apache
COPY ./newac /var/www/html/

# Відкриваємо порт 80 для доступу до сайту
EXPOSE 80

# Запускаємо Apache в режимі foreground
CMD ["apache2-foreground"]
