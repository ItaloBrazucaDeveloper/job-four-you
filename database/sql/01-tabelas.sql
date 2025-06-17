DROP SCHEMA IF EXISTS JOB4YOU;

SET NAMES UTF8MB4;
SET time_zone = '+00:00';

CREATE SCHEMA JOB4YOU
CHARACTER SET UTF8MB4
COLLATE utf8mb4_0900_ai_ci;

USE JOB4YOU;

CREATE TABLE IF NOT EXISTS Endereco(
    ID                  INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    CEP                 CHAR(8) NOT NULL,
    Estado              CHAR(2) NOT NULL COLLATE utf8mb4_general_ci,
    Cidade              VARCHAR(255) NOT NULL,
    Bairro              VARCHAR(255) NOT NULL,
    Rua                 VARCHAR(255) NOT NULL,
    CONSTRAINT          CheckCEP CHECK (CEP REGEXP '[0-9]{8}'),
    CONSTRAINT          CheckEstado CHECK (Estado REGEXP '[A-Z]{2}'),
    CONSTRAINT          CheckCidade CHECK (Cidade REGEXP '^[a-zA-ZÀ-ÖØ-öø-ÿ .]+$'),
    CONSTRAINT          CheckBairro CHECK (Bairro REGEXP '^[a-zA-ZÀ-ÖØ-öø-ÿ .]+$'),
    CONSTRAINT          CheckRua CHECK (Rua REGEXP '^[a-zA-ZÀ-ÖØ-öø-ÿ .]+$')
);

CREATE TABLE IF NOT EXISTS NivelAcesso(
    ID                  TINYINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Grupo               VARCHAR(255) NOT NULL COLLATE utf8mb4_general_ci,
    CONSTRAINT          CheckGrupo CHECK (Grupo REGEXP '^[A-Z_]+$')
);

CREATE TABLE IF NOT EXISTS Credencial(
    ID                  INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Email               VARCHAR(100) UNIQUE NOT NULL COLLATE utf8mb4_general_ci,
    Senha               CHAR(60) NOT NULL COLLATE utf8mb4_bin,
    FKNivelAcesso       TINYINT UNSIGNED NOT NULL,
    FOREIGN KEY         (FKNivelAcesso) REFERENCES NivelAcesso(ID) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT          CheckEmail CHECK (Email REGEXP '(^[a-zA-Z0-9._%+-]+)@([a-zA-Z0-9-]*)(\.[a-zA-Z]{2,}){1,2}$'),
    CONSTRAINT          CheckSenhaBCrypt CHECK (Senha REGEXP '^\\$2[abxy]\\$[0-9]{2}\\$[A-Za-z0-9\./]{53}$')
);

CREATE TABLE IF NOT EXISTS Usuario(
    ID                  INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Nome                VARCHAR(50) NOT NULL,
    CPF                 CHAR(11) UNIQUE NOT NULL COLLATE utf8mb4_general_ci,
    Foto                VARCHAR(255) NULL COLLATE utf8mb4_bin,
    Celular             CHAR(11) NULL COLLATE utf8mb4_general_ci,
    DataNascimento      DATE NOT NULL,
    FKCredencial        INT UNSIGNED NOT NULL,
    FKEndereco          INT UNSIGNED NULL,
    DataCriacao         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UltimaAtualizacao   TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    StatusUsuario       ENUM('ATIVO', 'INATIVO', 'BLOQUEADO', 'BANIDO') DEFAULT 'ATIVO',
    FOREIGN KEY         (FKCredencial) REFERENCES Credencial(ID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY         (FKEndereco) REFERENCES Endereco(ID) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT          CheckNome CHECK (Nome REGEXP '^[a-zA-ZÀ-ÖØ-öø-ÿ]+[[:space:]][a-zA-ZÀ-ÖØ-öø-ÿ]+$'),
    CONSTRAINT          CheckCPF CHECK (CPF REGEXP '[0-9]{11}'),
    CONSTRAINT          CheckFoto CHECK (Foto IS NULL OR Foto REGEXP '\\.(jpg|jpeg|png|webp|bmp)$'),
    CONSTRAINT          CheckCelular CHECK (Celular REGEXP '[0-9]{11}')
);

CREATE TABLE IF NOT EXISTS CategoriaContato(
    ID                  TINYINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Nome                VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS InformacaoContato(
    ID                  INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Contato             VARCHAR(255) NULL COLLATE utf8mb4_general_ci,
    FKUsuario           INT UNSIGNED NOT NULL,
    FKCategoriaContato  TINYINT UNSIGNED NOT NULL,
    FOREIGN KEY         (FKUsuario) REFERENCES Usuario(ID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY         (FKCategoriaContato) REFERENCES CategoriaContato(ID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS CategoriaServico (
    ID                  SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Nome                VARCHAR(25) NOT NULL
);

CREATE TABLE IF NOT EXISTS PublicacaoServico(
    ID                  INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Titulo              VARCHAR(50) NOT NULL,
    Sobre               VARCHAR(255) NULL,
    Valor               DECIMAL(7,2) NOT NULL,
    QuantidadeFavorito  INTEGER UNSIGNED NOT NULL DEFAULT 0,
    FKCategoria         SMALLINT UNSIGNED NOT NULL,
    FKUsuario           INT UNSIGNED NOT NULL,
    DataCriacao         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UltimaAtualizacao   TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    StatusPublicacao    ENUM('ATIVO', 'EM_ANALISE', 'REJEITADO', 'BLOQUEADO') NOT NULL DEFAULT 'EM_ANALISE',
    FOREIGN KEY         (FKUsuario) REFERENCES Usuario(ID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY         (FKCategoria) REFERENCES CategoriaServico(ID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS ServicoFavorito(
    IDServico           INT UNSIGNED NOT NULL,
    IDUsuario           INT UNSIGNED NOT NULL,
    PRIMARY KEY         (IDServico, IDUsuario),
    FOREIGN KEY         (IDServico) REFERENCES PublicacaoServico(ID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY         (IDUsuario) REFERENCES Usuario(ID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS AvaliacaoServico (
    ID                  INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
    Nota                TINYINT UNSIGNED NOT NULL DEFAULT 0,
    Comentario          VARCHAR(255) NULL,
    FkPublicacao        INT UNSIGNED NOT NULL,
    FKUsuario           INT UNSIGNED NOT NULL,
    DataCriacao         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UltimaAtualizacao   TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT          CheckNota CHECK (Nota BETWEEN 0 AND 5),
    CONSTRAINT          CheckComentario CHECK (Comentario IS NULL OR length(Comentario) > 20),
    FOREIGN KEY         (FkPublicacao) REFERENCES PublicacaoServico(ID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY         (FKUsuario) REFERENCES Usuario(ID) ON DELETE CASCADE ON UPDATE CASCADE
);