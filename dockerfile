# ใช้ PHP 8.2 พร้อม Apache เป็น base image
FROM php:8.2-apache

# เปิดใช้งาน mod_rewrite ของ Apache เพื่อให้ Laravel ใช้ Pretty URLs ได้
RUN a2enmod rewrite

# ติดตั้ง dependencies ที่จำเป็น
RUN apt update && apt install -y --no-install-recommends \
    git \
    unzip \
    zip \
    && apt clean && rm -rf /var/lib/apt/lists/*

# ติดตั้ง PHP extensions โดยตรง
RUN docker-php-ext-install pdo pdo_mysql

# คัดลอกโค้ด Laravel ไปยังโฟลเดอร์ /var/www/html ภายใน container
COPY . /var/www/html

# ติดตั้ง Composer โดยใช้คำสั่ง PHP แทน multi-stage build
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

# ตั้งค่า permission ให้ Laravel สามารถทำงานได้
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ตั้งค่าตำแหน่งทำงานเป็น /var/www/html
WORKDIR /var/www/html

# ติดตั้ง dependencies ของ Laravel โดยไม่รวมแพ็กเกจ development
RUN composer install --no-dev --optimize-autoloader

# เปิดพอร์ต 80 เพื่อให้ container รับคำขอ HTTP ได้
EXPOSE 80 

# รัน Apache เมื่อตัว container เริ่มทำงาน
CMD ["apache2-foreground"]