#!/bin/bash

# 1. Obter o diretório atual do script
# Isso garante que o caminho seja relativo ao local do script, não ao CWD.
SCRIPT_DIR=$(dirname "$(readlink -f "$0")")

ENV_FILE="$SCRIPT_DIR/../.env"

if [ -f "$ENV_FILE" ]; then
    echo "Carregando variáveis do arquivo: $ENV_FILE"
    source "$ENV_FILE"

    # Renomeia a variável para compatibilidade
    DB_PASSWORD="$DB_PASSWD"
else
    echo "ERRO: O arquivo .env não foi encontrado em $ENV_FILE"
    echo "Certifique-se de que ele existe e está no diretório correto."
    exit 1
fi

echo "---------------------------------------------------"

# --- Diretório dos Scripts SQL ---
SQL_DIR="./sql"

# --- Lista de Arquivos SQL a Serem Executados ---
SQL_FILES=(
    "01-tabelas.sql"
    "02-valores-estaticos.sql"
    "03-indices.sql"
    "04-gatilhos.sql"
)

# --- Início da Execução ---
echo "Iniciando a execução de scripts SQL no banco de dados '$DB_NAME'..."
echo "---------------------------------------------------"

# Loop através de cada arquivo na lista SQL_FILES
for script_name in "${SQL_FILES[@]}"; do
    # Constrói o caminho completo para o arquivo SQL
    script_path="$SCRIPT_DIR/$SQL_DIR/$script_name"

    echo "Executando: $script_name..."

    # Verifica se o arquivo existe antes de tentar executá-lo
    if [ -f "$script_path" ]; then
        # Comando MySQL para executar o script
        mysql -u "$DB_USER" -p"$DB_PASSWD" "$DB_NAME" < "$script_path"

        # Verifica o status de saída do comando mysql
        if [ $? -ne 0 ]; then
            echo "ERRO: A execução de '$script_name' falhou! Abortando."
            exit 1 # Sai do script .sh em caso de erro
        else
            echo "SUCESSO: '$script_name' executado."
        fi
    else
        echo "AVISO: Arquivo '$script_name' não encontrado em '$SQL_DIR'. Pulando."
    fi
    echo "---------------------------------------------------"
done

echo "Todos os scripts SQL listados foram processados com sucesso!"