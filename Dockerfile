# Imagem Docker para desenvolvimento

FROM composer:lts as build

WORKDIR /build

# Copia os arquivos de dependências
COPY composer.json composer.lock ./
# Instala as dependências
RUN composer install --no-scripts --no-autoloader --no-dev

# Copia o resto do código fonte
COPY . .
# Gera o autoloader otimizado
RUN composer dump-autoload --optimize


FROM php:8.4-alpine as final

WORKDIR /job4you

# Instala o dumb-init
RUN apk add --no-cache dumb-init

RUN mkdir -p var/cache/doctrine/Proxies \
      && chmod -R 777 var/cache/doctrine

COPY --from=build /build/app ./app
COPY --from=build /build/public ./public
COPY --from=build /build/vendor ./vendor
COPY --from=build /build/composer.json .

# Instala as extensões do PHP necessárias para o projeto
RUN docker-php-ext-install mysqli \
      && docker-php-ext-enable mysqli

EXPOSE 5173
# Usa dumb-init para gerenciar o processo PHP
ENTRYPOINT ["dumb-init", "--"]
CMD ["php", "-S", "0.0.0.0:5173", "-t", "./public"]