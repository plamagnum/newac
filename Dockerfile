# Використовуємо офіційний образ PHP
FROM php:7.4-apache

# Встановлюємо розширення для роботи з MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Копіюємо файли вашого сайту в директорію, яку обслуговує Apache
COPY ./ac /var/www/html/

# Відкриваємо порт 80 для доступу до сайту
EXPOSE 80

# Запускаємо Apache в режимі foreground
CMD ["apache2-foreground"]
