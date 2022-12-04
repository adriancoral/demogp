FROM php:8.1.13-apache-buster

LABEL maintainer="Adrian Coral <adriancoral@gmail.com>"

ARG user=developer

LABEL so.debian="buster"
LABEL php="8.1.13"

# persistent dependencies
RUN set -eux; \
	apt-get update && apt-get install -y apt-transport-https && \
	apt-get install -y --no-install-recommends

# install the PHP extensions
RUN set -ex; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		libjpeg-dev \
		libxml2-dev \
		libmcrypt-dev \
		zlib1g-dev \
		libfreetype6-dev \
		libmemcached-dev \
		libmagickwand-dev \
		libpng-dev \
		libzip-dev \
		less \
		wget \
		unzip \
        supervisor

RUN docker-php-ext-install zip;

RUN docker-php-ext-install pcntl;

RUN	docker-php-ext-install -j "$(nproc)" \
		bcmath \
		iconv \
		opcache \
	;

#RUN apt install php8.0-mbstring

RUN	docker-php-ext-install mysqli pdo_mysql;

RUN mkdir -p /var/log/supervisor && \
    touch /var/log/supervisor/supervisor.log && \
    chmod 666 /var/log/supervisor/supervisor.log && \
    mkdir -p /etc/supervisor/conf.d

RUN a2enmod expires \
	&& a2enmod headers \
	&& a2enmod rewrite \
    && a2enmod ssl

# Add the user UID:1000, GID:1000 and add to sudo group
RUN groupadd -r $user -g 1004 && useradd -u 1004 -r -g $user -m  $user -s /bin/bash && \
    echo "%${user}   ALL=(ALL:ALL) NOPASSWD:ALL" >> /etc/sudoers

RUN groupadd -r acoral -g 1000 && useradd -u 1000 -r -g acoral -m  acoral -s /bin/bash;

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD ./supervisord.conf /etc/supervisord.conf

# default command
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
#ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]
