FROM node:16-alpine as runtime

ARG FILES_UID=1000
ARG FILES_GID=1000

# Create non-privileged user
RUN apk add --no-cache --virtual .build-deps shadow  \
  && groupmod -g ${FILES_GID} node \
  && usermod -u ${FILES_UID} -g node node \
  && apk del .build-deps

WORKDIR /app
USER node

CMD sh /start.sh
