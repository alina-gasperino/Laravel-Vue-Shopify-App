FROM php:8-apache
RUN apt-get update -y && apt-get install -y openssl zip unzip git libpng-dev vim imagemagick libmagickwand-dev --no-install-recommends

RUN printf "\n" | pecl install imagick

RUN docker-php-ext-install gd pdo pdo_mysql ap imagick
COPY apache2.conf /etc/apache2/apache2.conf
RUN  rm /etc/apache2/sites-available/000-default.conf \
         && rm /etc/apache2/sites-enabled/000-default.conf

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini

# Enable rewrite module
RUN a2enmod rewrite

WORKDIR /var/www/html

# Update Config for ImageMagick
RUN sed 's/none" pattern="PDF"/read|write" pattern="PDF"/' /etc/ImageMagick-6/policy.xml > /etc/ImageMagick-6/policy.xml.changed && mv /etc/ImageMagick-6/policy.xml.changed /etc/ImageMagick-6/policy.xml


# Download and Install Composer
RUN curl -s http://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Add vendor binaries to PATH
ENV PATH=/var/www/html/vendor/bin:$PATH

COPY . /var/www/html

# Configure directory permissions for the web server
RUN chgrp -R www-data storage /var/www/html/bootstrap/cache
RUN chmod -R ug+rwx storage /var/www/html/bootstrap/cache

RUN chgrp -R www-data storage /var/www/html/storage
RUN chmod -R ug+rwx storage /var/www/html/storage

# Configure data volume
VOLUME /var/www/html/storage/app
VOLUME /var/www/html/storage/framework/sessions
VOLUME /var/www/html/storage/logs

#RUN php artisan migrate:fresh --seed
