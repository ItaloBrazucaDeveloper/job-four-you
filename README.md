<h1 align="center"> - Job4You - </h1>

![Foto da página inicial](preview.png)

<div align="center">

  <h1> - Job4You - </h1>

  ![Foto da página inicial](preview.png)

  [![PHP Version](https://img.shields.io/badge/PHP-%38.4-blue.svg?style=for-the-badge&color=007BFF)](https://www.php.net/)
  [![Bootstrap Version](https://img.shields.io/badge/Bootstrap-%35.0-blueviolet.svg?style=for-the-badge&color=6F42C1)](https://getbootstrap.com/)
  [![MySQL Version](https://img.shields.io/badge/MySQL-%38.4-blue.svg?style=for-the-badge)](https://www.mysql.com/)
  [![Made in Brazil](https://img.shields.io/badge/Made%20in-Brazil-009933.svg?style=for-the-badge&color=28A745)](https://github.com/YanGabrielton/Etb-Tcc)
  [![Project Status](https://img.shields.io/badge/Status-Development-yellow.svg?style=for-the-badge&color=ffba00)](https://github.com/YanGabrielton/Etb-Tcc)

</div>

## 💡 Sobre o projeto
Já precisou de limpeza na sua casa, alguém para passear com seu pet, um dia de fotos, mas não sabia a quem solicitar esses serviços? A plataforma Job4You resolve esse problema conectando pessoas com prestadores de serviço informal, facilitando a busca e divulgação.

## 📦 Instalação
Siga o passo a passo de como instalar o projeto na sua máquina.

1. **Clone o projeto do github**

  ```bash
    $ git clone -b main https://github.com/YanGabrielton/Etb-Tcc.git
  ```

2. **Entre na pasta do projeto**
  
  ```bash
    $ cd ./Etb-Tcc
  ```

## 🚀 Como iniciar o projeto
Escolha uma das formas abaixo para executar o projeto na sua máquina.

### ⛔🐳 Sem Docker
Siga o passo a passo para executar o projeto localmente.

> **Atenção:** Certifique-se de ter o [Mysql](https://www.mysql.com/) e o [PHP](https://www.php.net/) instalados em sua máquina antes de usar este método.

1. **Copie e renomeie o arquivo `.example.env`**

  ```bash
    $ cp .example.env .env
  ```

2. **Defina as variáveis de ambiente no arquivo `.env`**

  ```textplain
    DB_HOST=SEU_HOST
    DB_USER=SEU_USUARIO
    DB_PASSWD=SUA_SENHA
    DB_NAME=NOME_DO_SEU_BANCO
    DB_PORT=PORTA_DO_SEU_BANCO
  ```

3. **Execute o script '.sh' para automatizar a criação do banco de dados**

  ```bash
    $ /banco/init-db.sh
  ```

4. **Inicie o servidor**

  ```bash
    $ php -S localhost:5173
  ```

5. **Acesse o projeto pelo navegador em: http://localhost:5173/**

### ✅🐳 Com Docker
Siga o passo a passo para executar o projeto usando Docker.

---

> **Atenção:** Certifique-se de ter o [Docker](https://www.docker.com/) e o [Docker Compose](https://docs.docker.com/compose/) instalados em sua máquina antes de usar este método.

1. **Copie e renomeie o arquivo `.example.env`**

  ```bash
    $ cp .example.env .env
  ```

2. **Defina as variáveis de ambiente no arquivo `.env`**
  ```bash
    DB_HOST=SEU_HOST
    DB_USER=SEU_USUARIO
    DB_PASSWORD=SUA_SENHA
    DB_NAME=NOME_DO_SEU_BANCO
    DB_PORT=PORTA_DO_SEU_BANCO
  ```

3. **Construa e inicie os containers**

  ```bash
    $ docker compose up --build
  ```

4. **Acesse o projeto pelo navegador em: http://localhost:5173/**

> **Observação:** Use a flag `--build`, apenas quando for a primeira vez executando o projeto. A flag `--build` faz a contrução das imagens dos arquivos _Dockerfile_. Uma vez já construídos, omita a flag `--build` e use:`docker compose up`.

## 🧠 Mentes por trás do projeto
- [✍️ Aline](https://github.com/alineop120)
- [🏅 Giovanna](https://github.com/giihzinha0L70)
- [🎲 Ítalo](https://github.com/ItaloBrazucaDeveloper)
