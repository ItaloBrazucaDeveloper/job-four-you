# Imagem docker para desenvolvimento

FROM mysql:8.4.0

# Copia os scripts SQL para o diretório de inicialização do MySQL
# Isso permite que o MySQL execute esses scripts quando o container for iniciado
COPY ./sql /docker-entrypoint-initdb.d

# Copia o arquivo de configuração personalizado para o diretório de configuração do MySQL
COPY ./custom.cnf /etc/mysql/conf.d/

# Expõe a porta 3306, que é a porta padrão do MySQL, no container
EXPOSE 3306