-- Script para criar um usuário de teste com senha 'banana.nanica' criptografada
-- Senha: banana.nanica
-- Hash bcrypt: $2y$12$qETuJHB3UgThUVu/4ByaTut5xkSPvLugXJlfmxY5QZCzJic01X12K

USE JOB4YOU;

-- Inserir endereço
INSERT INTO Endereco (CEP, Estado, Cidade, Bairro, Rua) VALUES
('12345678', 'SP', 'São Paulo', 'Centro', 'Rua das Flores');

-- Inserir credencial com senha criptografada
INSERT INTO Credencial (Email, Senha, FKNivelAcesso) VALUES
('usuario.teste@email.com', '$2y$12$qETuJHB3UgThUVu/4ByaTut5xkSPvLugXJlfmxY5QZCzJic01X12K', 3);

-- Inserir usuário
INSERT INTO Usuario (Nome, CPF, Celular, DataNascimento, FKCredencial, FKEndereco) VALUES
('João Silva', '12345678901', '11987654321', '1990-05-15', LAST_INSERT_ID(), LAST_INSERT_ID());

-- Verificar se o usuário foi criado
SELECT 
    u.ID,
    u.Nome,
    u.CPF,
    u.Celular,
    u.DataNascimento,
    c.Email,
    na.Grupo as NivelAcesso,
    e.CEP,
    e.Estado,
    e.Cidade,
    e.Bairro,
    e.Rua
FROM Usuario u
JOIN Credencial c ON u.FKCredencial = c.ID
JOIN NivelAcesso na ON c.FKNivelAcesso = na.ID
LEFT JOIN Endereco e ON u.FKEndereco = e.ID
WHERE c.Email = 'usuario.teste@email.com'; 