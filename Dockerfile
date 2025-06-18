# Imagem Docker para desenvolvimento
FROM composer:lts as php-build
WORKDIR /build
# Copia os arquivos de dependências
COPY composer.json composer.lock ./
# Instala as dependências
RUN composer install --no-scripts --no-autoloader --no-dev
# Copia o resto do código fonte
COPY . .
# Gera o autoloader otimizado
RUN composer dump-autoload --optimize

# Instala as dependências do Node.js
FROM node:20-alpine as node-build
WORKDIR /build
COPY package.json ./
RUN npm install

FROM php:8.4-alpine as final
WORKDIR /job4you
# Instala o dumb-init para melhor gerenciamento de processos no container
RUN apk add --no-cache dumb-init
# Copia os arquivos dos outros estágios 
COPY --from=php-build /build/app ./app
COPY --from=php-build /build/public ./public
COPY --from=php-build /build/vendor ./vendor
COPY --from=php-build /build/composer.json .
COPY --from=node-build /build/node_modules ./node_modules
COPY --from=node-build /build/package.json .
# Instala as extensões do PHP necessárias para o projeto
RUN docker-php-ext-install mysqli \
      && docker-php-ext-enable mysqli
EXPOSE 3000
# Usa dumb-init para gerenciar o processo PHP
ENTRYPOINT ["dumb-init", "--"]
CMD ["php", "-S", "0.0.0.0:3000", "-t", "./public"]