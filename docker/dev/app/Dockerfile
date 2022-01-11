FROM ubuntu:18.04
#Version of ubuntu

#Create variable with
ENV LANG="en_US.UTF-8" \
    LC_ALL="en_US.UTF-8" \
    LANGUAGE="en_US.UTF-8" \
    TERM="xterm" \
    DEBIAN_FRONTEND="noninteractive" \
    NODE_VERSION=12.18.3 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    PHP_VERSION=7.4 \
    COMPOSER_VERSION=1.10.16

#Working directory
WORKDIR /app

#Docker run of different apt
RUN apt-get update -q && \
    apt-get install -qy software-properties-common language-pack-en-base && \
    export LC_ALL=en_US.UTF-8 && \
    export LANG=en_US.UTF-8 && \
    add-apt-repository -y ppa:ondrej/php && \
    apt-get update -q && \
    apt-get install --no-install-recommends -qy \
    wget \
    gnupg \
    build-essential \
    cron \
    curl \
    nano \
    nginx \
    git \
    php-pear \
    php${PHP_VERSION} \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-dom \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-amqp \
    supervisor \
    unzip \
    openssh-client

# Delete new version of PHP (not enable in project).
RUN apt-get purge php8.0.* -qy

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    cp /usr/share/zoneinfo/Europe/Paris /etc/localtime && echo "Europe/Paris" > /etc/timezone && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=${COMPOSER_VERSION} && \
    curl -L -o /tmp/nodejs.tar.gz https://nodejs.org/dist/v${NODE_VERSION}/node-v${NODE_VERSION}-linux-x64.tar.gz && \
    tar xfvz /tmp/nodejs.tar.gz -C /usr/local --strip-components=1 && \
    rm -f /tmp/nodejs.tar.gz

# Install Mysql utils
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    apt-get update && \
    apt-get install -qy mysql-client

# Configuration files
COPY php.ini /etc/php/${PHP_VERSION}/cli/conf.d/50-setting.ini
COPY php.ini /etc/php/${PHP_VERSION}/fpm/conf.d/50-setting.ini
COPY pool.conf /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY symfony.conf /etc/nginx/symfony.conf

#Command
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
