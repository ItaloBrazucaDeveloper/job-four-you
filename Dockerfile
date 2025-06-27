# --------> php-build : Instala as dependências php
FROM composer:lts as php-build

WORKDIR /build
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-dev
COPY . .
RUN composer dump-autoload --optimize

# --------> node-build stage : Instala as dependências do Node.js
FROM node:20-alpine as node-build

WORKDIR /build
COPY package.json ./
RUN npm install --production

# --------> final stage : Monta o projeto com as builds dos estágios anteriores
FROM php:8.4-alpine as final

WORKDIR /job4you
# Instala o dumb-init para melhor gerenciamento de processos no container
RUN apk add --no-cache dumb-init

COPY --from=php-build /build/app ./app
COPY --from=php-build /build/public ./public
COPY --from=php-build /build/vendor ./vendor
COPY --from=php-build /build/composer.json .
COPY --from=node-build /build/node_modules ./node_modules
COPY --from=node-build /build/package.json .

RUN docker-php-ext-install mysqli \
      && docker-php-ext-enable mysqli

EXPOSE 3000

ENTRYPOINT ["dumb-init", "--"]
CMD ["php", "-S", "0.0.0.0:3000", "-t", "./public"]