FROM php:8.1.2-alpine as runtime

ARG FILES_UID=1000
ARG FILES_GID=1000

# Create non-privileged user
RUN addgroup -g $FILES_GID appuser \
  && adduser -D -h /home/appuser -u $FILES_UID -G appuser appuser

# Permanent depencies
RUN apk add --no-cache \
    icu-libs postgresql-libs libzip

# Build-time dependencies
RUN apk add --no-cache --virtual .build-deps postgresql-dev libzip-dev autoconf make g++

# Install php extensions
RUN CFLAGS="$CFLAGS -D_GNU_SOURCE" docker-php-ext-install -j$(nproc) \
    pdo_mysql pdo_pgsql opcache zip pcntl sockets intl

# Install php PECL extensions
RUN pecl install -o -f xdebug redis \
    && docker-php-ext-enable xdebug redis

# Clear build cache
RUN rm -rf /tmp/pear && apk del .build-deps

COPY --from=composer:2.2.5 /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:2.7.4 /usr/bin/rr /usr/bin/rr

USER appuser
WORKDIR /app

CMD sh /start.sh
