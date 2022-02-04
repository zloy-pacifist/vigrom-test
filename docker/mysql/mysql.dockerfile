FROM mysql:8.0

ARG FILES_UID=1000
ARG FILES_GID=1000

RUN groupmod -g ${FILES_GID} mysql \
    && usermod -u ${FILES_UID} -g mysql -s /bin/bash mysql
