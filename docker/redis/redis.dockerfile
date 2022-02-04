FROM redis

ARG FILES_UID=1000
ARG FILES_GID=1000

RUN groupmod -g ${FILES_GID} redis  \
    && usermod -u ${FILES_UID} -g redis  -s /bin/bash redis
