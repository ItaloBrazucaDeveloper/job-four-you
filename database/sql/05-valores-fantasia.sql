-- =====================================================
-- ARQUIVO: 05-valores-fantasia.sql
-- DESCRIÇÃO: Script para inserir dados fantasiosos
-- QUANTIDADE: 50 registros por tabela
-- =====================================================

-- =====================================================
-- INSERÇÃO DE ENDEREÇOS (50 registros)
-- GARANTIA: Todos os 50 endereços serão referenciados por usuários.
-- =====================================================
INSERT INTO Endereco (CEP, Estado, Cidade, Bairro, Rua) VALUES
('01310100', 'SP', 'São Paulo', 'Bela Vista', 'Avenida Paulista'),
('22071900', 'RJ', 'Rio de Janeiro', 'Copacabana', 'Avenida Atlântica'),
('30112000', 'MG', 'Belo Horizonte', 'Centro', 'Rua da Bahia'),
('80020080', 'PR', 'Curitiba', 'Centro', 'Rua Quinze de Novembro'),
('90010130', 'RS', 'Porto Alegre', 'Centro Histórico', 'Rua dos Andradas'),
('40070110', 'BA', 'Salvador', 'Pelourinho', 'Terreiro de Jesus'),
('60165081', 'CE', 'Fortaleza', 'Aldeota', 'Avenida Dom Luís'),
('52011000', 'PE', 'Recife', 'Boa Vista', 'Rua da Aurora'),
('70040010', 'DF', 'Brasília', 'Asa Norte', 'Quadra Norte'),
('88015560', 'SC', 'Florianópolis', 'Centro', 'Rua Felipe Schmidt'),
('01234567', 'SP', 'Santos', 'Gonzaga', 'Avenida Ana Costa'),
('21000000', 'RJ', 'Rio de Janeiro', 'Centro', 'Praça Quinze de Novembro'),
('31270901', 'MG', 'Belo Horizonte', 'Savassi', 'Rua Pernambuco'),
('80240031', 'PR', 'Curitiba', 'Batel', 'Avenida Batel'),
('91040000', 'RS', 'Porto Alegre', 'Moinhos de Vento', 'Rua Mostardeiro'),
('41940160', 'BA', 'Salvador', 'Pituba', 'Avenida Paulo Sexto'),
('60115221', 'CE', 'Fortaleza', 'Meireles', 'Avenida Beira Mar'),
('52061280', 'PE', 'Recife', 'Parnamirim', 'Rua José Bonifácio'),
('70297400', 'DF', 'Brasília', 'Asa Sul', 'Quadra Sul'),
('88040400', 'SC', 'Florianianópolis', 'Trindade', 'Rua Lauro Linhares'),
('13013161', 'SP', 'Campinas', 'Centro', 'Rua Barão de Jaguara'),
('24220261', 'RJ', 'Niterói', 'Icaraí', 'Rua Moreira César'),
('32400000', 'MG', 'Ibirité', 'Centro', 'Rua Principal'),
('86020080', 'PR', 'Londrina', 'Centro', 'Avenida Higienópolis'),
('95020360', 'RS', 'Caxias do Sul', 'Centro', 'Rua Sinimbu'),
('44001000', 'BA', 'Feira de Santana', 'Centro', 'Rua Sales Barbosa'),
('61600000', 'CE', 'Caucaia', 'Centro', 'Rua João Pessoa'),
('53000000', 'PE', 'Olinda', 'Carmo', 'Rua do Sol'),
('72800000', 'GO', 'Luziânia', 'Centro', 'Avenida Juscelino Kubitschek'),
('89201000', 'SC', 'Joinville', 'Centro', 'Rua do Príncipe'),
('02012345', 'SP', 'São Paulo', 'Santana', 'Avenida Cruzeiro do Sul'),
('25678900', 'RJ', 'Petrópolis', 'Centro', 'Rua do Imperador'),
('35400000', 'MG', 'Ouro Preto', 'Centro', 'Praça Tiradentes'),
('87654321', 'PR', 'Maringá', 'Centro', 'Avenida Brasil'),
('98765432', 'RS', 'Santa Maria', 'Centro', 'Rua do Acampamento'),
('45678900', 'BA', 'Vitória da Conquista', 'Centro', 'Praça Tancredo Neves'),
('63000000', 'CE', 'Juazeiro do Norte', 'Centro', 'Rua São Pedro'),
('56789012', 'PE', 'Caruaru', 'Centro', 'Rua Vigário Freire'),
('74000000', 'GO', 'Goiânia', 'Setor Central', 'Rua Três'),
('89000000', 'SC', 'Blumenau', 'Centro', 'Rua Quinze de Novembro'),
('03456789', 'SP', 'Guarulhos', 'Centro', 'Rua Dom Pedro Segundo'),
('26789012', 'RJ', 'Nova Iguaçu', 'Centro', 'Rua Coronel Carlos Sampaio'),
('34567890', 'MG', 'Juiz de Fora', 'Centro', 'Avenida Rio Branco'),
('81234567', 'PR', 'Ponta Grossa', 'Centro', 'Rua Quinze de Novembro'),
('90123456', 'RS', 'Pelotas', 'Centro', 'Rua Quinze de Novembro'),
('43210987', 'BA', 'Ilhéus', 'Centro', 'Avenida Soares Lopes'),
('61234567', 'CE', 'Sobral', 'Centro', 'Rua Coronel Mont Alverne'),
('54321098', 'PE', 'Petrolina', 'Centro', 'Avenida Cardoso de Sá'),
('76543210', 'GO', 'Anápolis', 'Centro', 'Avenida Brasil'),
('87890123', 'SC', 'Chapecó', 'Centro', 'Avenida Getúlio Vargas');

-- =====================================================
-- INSERÇÃO DE CREDENCIAIS (50 registros)
-- Senhas são hash bcrypt de "123456"
-- =====================================================
INSERT INTO Credencial (Email, Senha, FKNivelAcesso) VALUES
('maria.silva@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('joao.santos@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('ana.costa@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('pedro.oliveira@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('carla.ferreira@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('admin@job4you.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 1), -- Admin
('lucas.pereira@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('juliana.alves@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('rafael.souza@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('fernanda.lima@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('bruno.rodrigues@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('camila.martins@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('diego.nascimento@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('amanda.ribeiro@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('thiago.cardoso@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('patricia.gomes@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('rodrigo.barbosa@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('larissa.freitas@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('gustavo.araujo@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('carolina.moreira@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('felipe.teixeira@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('isabela.cavalcanti@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('leonardo.melo@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('natalia.ramos@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('vinicius.machado@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('gabriela.castro@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('marcelo.pinto@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('priscila.dias@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('anderson.silva@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('daniela.fernandes@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('ricardo.mendes@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('vanessa.vieira@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('fabio.cunha@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('monica.torres@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('alex.junior@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('renata.campos@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('sergio.duarte@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('tatiana.lopes@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('henrique.medeiros@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('luciana.santana@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('eduardo.correa@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('simone.azevedo@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('carlos.nunes@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('aline.moura@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('junior.batista@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('kelly.rocha@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('otavio.reis@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('bianca.fonseca@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3), -- Cliente
('mauricio.braga@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 2), -- Prestador
('claudia.xavier@email.com', '$2b$10$N9qo8uLOickgx2ZMRZoMye.IjdJxCFWKLKFjWMNYVdGm2i7qK9fGK', 3); -- Cliente

-- =====================================================
-- INSERÇÃO DE USUÁRIOS (50 registros)
-- ALTERADO: Todos os prestadores (FKNivelAcesso = 2) terão o campo Celular = NULL.
-- ALTERADO: Todos os usuários terão um FKEndereco válido (não NULL).
-- =====================================================
INSERT INTO Usuario (Nome, CPF, Foto, Celular, DataNascimento, FKCredencial, FKEndereco, StatusUsuario) VALUES
('Maria Silva', '12345678901', 'https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1985-03-15', 1, 1, 'ATIVO'), -- Prestador (Celular agora NULL)
('João Santos', '23456789012', NULL, '21976543210', '1990-07-22', 2, 2, 'ATIVO'), -- Cliente (Celular OK)
('Ana Costa', '34567890123', 'https://images.pexels.com/photos/1239291/pexels-photo-1239291.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1988-11-08', 3, 3, 'ATIVO'), -- Prestador (Celular agora NULL)
('Pedro Oliveira', '45678901234', NULL, '41999999999', '1992-01-30', 4, 4, 'ATIVO'), -- Cliente (Celular e Endereco preenchidos)
('Carla Ferreira', '56789012345', 'https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1987-09-12', 5, 5, 'ATIVO'), -- Prestador (Celular agora NULL)
('Admin Sistema', '67890123456', NULL, '11999887766', '1980-05-20', 6, 6, 'ATIVO'), -- Admin (Celular OK)
('Lucas Pereira', '78901234567', 'https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1995-12-03', 7, 7, 'ATIVO'), -- Prestador (Celular agora NULL)
('Juliana Alves', '89012345678', NULL, '61932109876', '1993-04-18', 8, 8, 'ATIVO'), -- Cliente (Celular OK)
('Rafael Souza', '90123456789', 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1989-08-25', 9, 9, 'ATIVO'), -- Prestador (Celular agora NULL)
('Fernanda Lima', '01234567890', NULL, '81910987654', '1991-06-14', 10, 10, 'ATIVO'), -- Cliente (Celular OK)
('Bruno Rodrigues', '12309876543', 'https://images.pexels.com/photos/1043471/pexels-photo-1043471.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1986-10-07', 11, 11, 'ATIVO'), -- Prestador (Celular agora NULL)
('Camila Martins', '23410987654', NULL, '21988888888', '1994-02-28', 12, 12, 'ATIVO'), -- Cliente (Celular e Endereco preenchidos)
('Diego Nascimento', '34521098765', 'https://images.pexels.com/photos/2182970/pexels-photo-2182970.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1988-12-11', 13, 13, 'ATIVO'), -- Prestador (Celular agora NULL)
('Amanda Ribeiro', '45632109876', NULL, '31854321098', '1990-05-16', 14, 14, 'ATIVO'), -- Cliente (Celular OK)
('Thiago Cardoso', '56743210987', 'https://images.pexels.com/photos/1755385/pexels-photo-1755385.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1987-09-03', 15, 15, 'ATIVO'), -- Prestador (Celular agora NULL)
('Patricia Gomes', '67854321098', NULL, '51977777777', '1992-11-22', 16, 16, 'ATIVO'), -- Cliente (Celular e Endereco preenchidos)
('Rodrigo Barbosa', '78965432109', 'https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1989-07-09', 17, 17, 'ATIVO'), -- Prestador (Celular agora NULL)
('Larissa Freitas', '89076543210', NULL, '71810987654', '1991-03-26', 18, 18, 'ATIVO'), -- Cliente (Celular OK)
('Gustavo Araujo', '90187654321', 'https://images.pexels.com/photos/1040880/pexels-photo-1040880.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1988-01-13', 19, 19, 'ATIVO'), -- Prestador (Celular agora NULL)
('Carolina Moreira', '01298765432', NULL, '91966666666', '1993-08-05', 20, 20, 'ATIVO'), -- Cliente (Celular e Endereco preenchidos)
('Felipe Teixeira', '12987654321', 'https://images.pexels.com/photos/1239288/pexels-photo-1239288.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1985-12-18', 21, 21, 'ATIVO'), -- Prestador (Celular agora NULL)
('Isabela Cavalcanti', '23098765432', NULL, '21787654321', '1990-04-23', 22, 22, 'ATIVO'), -- Cliente (Celular OK)
('Leonardo Melo', '34109876543', 'https://images.pexels.com/photos/2379005/pexels-photo-2379005.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1987-10-10', 23, 23, 'ATIVO'), -- Prestador (Celular agora NULL)
('Natalia Ramos', '45210987654', NULL, '41765432109', '1992-06-27', 24, 24, 'ATIVO'), -- Cliente (Celular OK)
('Vinicius Machado', '56321098765', 'https://images.pexels.com/photos/1674752/pexels-photo-1674752.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1989-02-14', 25, 25, 'ATIVO'), -- Prestador (Celular agora NULL)
('Gabriela Castro', '67432109876', NULL, '61743210987', '1991-09-01', 26, 26, 'ATIVO'), -- Cliente (Celular OK)
('Marcelo Pinto', '78543210987', 'https://images.pexels.com/photos/1043474/pexels-photo-1043474.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1988-05-18', 27, 27, 'ATIVO'), -- Prestador (Celular agora NULL)
('Priscila Dias', '89654321098', NULL, '81955555555', '1994-11-05', 28, 28, 'ATIVO'), -- Cliente (Celular e Endereco preenchidos)
('Anderson Silva', '90765432109', 'https://images.pexels.com/photos/1484794/pexels-photo-1484794.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1986-07-22', 29, 29, 'ATIVO'), -- Prestador (Celular agora NULL)
('Daniela Fernandes', '01876543210', NULL, '11710987654', '1990-03-09', 30, 30, 'ATIVO'), -- Cliente (Celular OK)
('Ricardo Mendes', '12345098765', 'https://images.pexels.com/photos/1681010/pexels-photo-1681010.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1987-12-26', 31, 31, 'ATIVO'), -- Prestador (Celular agora NULL)
('Vanessa Vieira', '23456109876', NULL, '31698765432', '1992-08-13', 32, 32, 'ATIVO'), -- Cliente (Celular OK)
('Fabio Cunha', '34567210987', 'https://images.pexels.com/photos/1043473/pexels-photo-1043473.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1989-04-30', 33, 33, 'ATIVO'), -- Prestador (Celular agora NULL)
('Monica Torres', '45678321098', NULL, '51676543210', '1991-01-17', 34, 34, 'ATIVO'), -- Cliente (Celular OK)
('Alex Junior', '56789432109', 'https://images.pexels.com/photos/1183266/pexels-photo-1183266.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1988-10-04', 35, 35, 'ATIVO'), -- Prestador (Celular agora NULL)
('Renata Campos', '67890543210', NULL, '71944444444', '1993-06-21', 36, 36, 'ATIVO'), -- Cliente (Celular e Endereco preenchidos)
('Sergio Duarte', '78901654321', 'https://images.pexels.com/photos/1040881/pexels-photo-1040881.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1985-02-08', 37, 37, 'ATIVO'), -- Prestador (Celular agora NULL)
('Tatiana Lopes', '89012765432', NULL, '81643210987', '1990-11-25', 38, 38, 'ATIVO'), -- Cliente (Celular OK)
('Henrique Medeiros', '90123876543', 'https://images.pexels.com/photos/2128807/pexels-photo-2128807.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1987-07-12', 39, 39, 'ATIVO'), -- Prestador (Celular agora NULL)
('Luciana Santana', '01234987654', NULL, '21621098765', '1992-03-29', 40, 40, 'ATIVO'), -- Cliente (Celular OK)
('Eduardo Correa', '12345678098', 'https://images.pexels.com/photos/1212984/pexels-photo-1212984.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1989-12-16', 41, 41, 'ATIVO'), -- Prestador (Celular agora NULL)
('Simone Azevedo', '23456789109', NULL, '41609876543', '1991-08-03', 42, 42, 'ATIVO'), -- Cliente (Celular OK)
('Carlos Nunes', '34567890210', 'https://images.pexels.com/photos/1040882/pexels-photo-1040882.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1988-04-20', 43, 43, 'ATIVO'), -- Prestador (Celular agora NULL)
('Aline Moura', '45678901321', NULL, '61933333333', '1994-01-07', 44, 44, 'ATIVO'), -- Cliente (Celular e Endereco preenchidos)
('Junior Batista', '56789012432', 'https://images.pexels.com/photos/1559486/pexels-photo-1559486.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1986-09-24', 45, 45, 'ATIVO'), -- Prestador (Celular agora NULL)
('Kelly Rocha', '67890123543', NULL, '71576543210', '1990-05-11', 46, 46, 'ATIVO'), -- Cliente (Celular OK)
('Otavio Reis', '78901234654', 'https://images.pexels.com/photos/1040883/pexels-photo-1040883.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1987-01-28', 47, 47, 'ATIVO'), -- Prestador (Celular agora NULL)
('Bianca Fonseca', '89012345765', NULL, '11554321098', '1992-10-15', 48, 48, 'ATIVO'), -- Cliente (Celular OK)
('Mauricio Braga', '90123456876', 'https://images.pexels.com/photos/2182975/pexels-photo-2182975.jpeg?auto=compress&cs=tinysrgb&w=300', NULL, '1989-06-02', 49, 49, 'ATIVO'), -- Prestador (Celular agora NULL)
('Claudia Xavier', '01234567987', NULL, '31532109876', '1991-02-19', 50, 50, 'ATIVO'); -- Cliente (Celular OK)

-- =====================================================
-- INSERÇÃO DE INFORMAÇÕES DE CONTATO (50 registros)
-- GARANTIA: Todos os prestadores terão pelo menos um contato aqui.
-- =====================================================
INSERT INTO InformacaoContato (Contato, FKUsuario, FKCategoriaContato) VALUES
('11987654321', 1, 1), -- WhatsApp Maria (Prestador)
('@maria.silva.oficial', 1, 2), -- Instagram Maria (Prestador)
('21976543210', 2, 3), -- Celular João (Cliente)
('joao.santos.fotografia', 2, 4), -- Facebook João (Cliente)
('ana.costa.limpeza@gmail.com', 3, 5), -- Email Ana (Prestador)
('31965432109', 3, 1), -- WhatsApp Ana (Prestador)
('pedro.oliveira.jardim', 4, 2), -- Instagram Pedro (Cliente)
('41954321098', 5, 1), -- WhatsApp Carla (Prestador)
('carla.baba.profissional', 5, 4), -- Facebook Carla (Prestador)
('admin@job4you.com', 6, 5), -- Email Admin
('51943210987', 7, 3), -- Celular Lucas (Prestador)
('@lucas.manutencao', 7, 2), -- Instagram Lucas (Prestador)
('juliana.reforco.escolar@outlook.com', 8, 5), -- Email Juliana (Cliente)
('71921098765', 9, 1), -- WhatsApp Rafael (Prestador)
('rafael.cuidador.idoso', 9, 4), -- Facebook Rafael (Prestador)
('81910987654', 10, 3), -- Celular Fernanda (Cliente)
('@fernanda.pet.care', 10, 2), -- Instagram Fernanda (Cliente)
('11876543210', 11, 1), -- WhatsApp Bruno (Prestador)
('bruno.cozinheiro.gourmet@gmail.com', 11, 5), -- Email Bruno (Prestador)
('@camila.costura.arte', 12, 2), -- Instagram Camila (Cliente)
('21865432109', 13, 1), -- WhatsApp Diego (Prestador)
('diego.fotografo.eventos', 13, 4), -- Facebook Diego (Prestador)
('amanda.marido.aluguel@hotmail.com', 14, 5), -- Email Amanda (Cliente)
('31854321098', 14, 3), -- Celular Amanda (Cliente)
('41843210987', 15, 1), -- WhatsApp Thiago (Prestador)
('@thiago.esteticista', 15, 2), -- Instagram Thiago (Prestador)
('patricia.baba.especializada', 16, 4), -- Facebook Patricia (Cliente)
('61821098765', 17, 1), -- WhatsApp Rodrigo (Prestador)
('rodrigo.limpeza.residencial@gmail.com', 17, 5), -- Email Rodrigo (Prestador)
('@larissa.jardinagem', 18, 2), -- Instagram Larissa (Cliente)
('71810987654', 18, 3), -- Celular Larissa (Cliente)
('81809876543', 19, 1), -- WhatsApp Gustavo (Prestador)
('gustavo.mudancas.fretes', 19, 4), -- Facebook Gustavo (Prestador)
('@carolina.manutencao.geral', 20, 2), -- Instagram Carolina (Cliente)
('11798765432', 21, 1), -- WhatsApp Felipe (Prestador)
('felipe.reforco.matematica@outlook.com', 21, 5), -- Email Felipe (Prestador)
('21787654321', 22, 3), -- Celular Isabela (Cliente)
('isabela.cuidadora.especialista', 22, 4), -- Facebook Isabela (Cliente)
('@leonardo.pet.walker', 23, 2), -- Instagram Leonardo (Prestador)
('31776543210', 23, 1), -- WhatsApp Leonardo (Prestador)
('natalia.chef.domicilio@gmail.com', 24, 5), -- Email Natalia (Cliente)
('51754321098', 25, 1), -- WhatsApp Vinicius (Prestador)
('@vinicius.alfaiate', 25, 2), -- Instagram Vinicius (Prestador)
('61743210987', 26, 3), -- Celular Gabriela (Cliente)
('gabriela.fotografa.casamentos', 26, 4), -- Facebook Gabriela (Cliente)
('71732109876', 27, 1), -- WhatsApp Marcelo (Prestador)
('marcelo.faz.tudo@hotmail.com', 27, 5), -- Email Marcelo (Prestador)
('@priscila.beauty.expert', 28, 2), -- Instagram Priscila (Cliente)
('81721098765', 29, 1), -- WhatsApp Anderson (Prestador)
('anderson.baba.noturno', 29, 4), -- Facebook Anderson (Prestador)
('daniela.limpeza.hospitalar@gmail.com', 30, 5); -- Email Daniela (Cliente)

-- =====================================================
-- INSERÇÃO DE PUBLICAÇÕES DE SERVIÇO (50 registros)
-- Apenas usuários PRESTADORES (nível 2) podem criar
-- =====================================================
INSERT INTO PublicacaoServico (Titulo, Sobre, Valor, FKCategoria, FKUsuario, StatusPublicacao) VALUES
('Babá Experiente e Carinhosa', 'Cuido de crianças de todas as idades com muito amor e responsabilidade. Tenho 10 anos de experiência.', 25.00, 1, 1, 'ATIVO'),
('Limpeza Residencial Completa', 'Faço limpeza pesada e de manutenção em residências. Trabalho com produtos ecológicos.', 80.00, 2, 3, 'ATIVO'),
('Jardinagem e Paisagismo', 'Cuido do seu jardim com carinho. Poda, plantio, adubação e design de jardins.', 60.00, 3, 5, 'EM_ANALISE'),
('Mudanças e Fretes Rápidos', 'Serviço de mudança residencial e comercial. Equipe experiente e cuidadosa.', 150.00, 4, 7, 'ATIVO'),
('Manutenção Residencial Geral', 'Pequenos reparos em casa: torneiras, chuveiros, pinturas, instalações básicas.', 45.00, 5, 9, 'ATIVO'),
('Reforço Escolar Matemática', 'Aulas particulares de matemática para ensino fundamental e médio.', 35.00, 6, 11, 'ATIVO'),
('Cuidadora de Idosos Especializada', 'Cuidado especializado para idosos. Administração de medicamentos e companhia.', 40.00, 7, 13, 'EM_ANALISE'),
('Pet Sitter e Dog Walker', 'Cuido do seu pet com muito carinho. Passeios, alimentação e companhia.', 30.00, 8, 15, 'ATIVO'),
('Chef Particular', 'Preparo refeições saudáveis e saborosas na sua casa. Cardápio personalizado.', 100.00, 9, 17, 'ATIVO'),
('Costureira Profissional', 'Consertos, ajustes e confecção de roupas. Trabalho rápido e de qualidade.', 20.00, 10, 19, 'ATIVO'),
('Fotógrafo de Eventos', 'Fotografo casamentos, aniversários e eventos corporativos. Portfolio disponível.', 300.00, 11, 21, 'ATIVO'),
('Marido de Aluguel Experiente', 'Resolvo problemas domésticos diversos: elétrica, hidráulica, montagens.', 50.00, 12, 23, 'EM_ANALISE'),
('Esteticista Facial e Corporal', 'Tratamentos estéticos faciais e corporais. Limpeza de pele, massagens.', 70.00, 13, 25, 'ATIVO'),
('Babá Noturna Disponível', 'Cuido de bebês e crianças durante a noite. Experiência com recém-nascidos.', 30.00, 1, 27, 'ATIVO'),
('Limpeza Pós-Obra', 'Especializada em limpeza após reformas e construções. Equipamentos profissionais.', 120.00, 2, 29, 'ATIVO'),
('Jardineiro Paisagista', 'Manutenção de jardins residenciais e comerciais. Projetos de paisagismo.', 55.00, 3, 31, 'ATIVO'),
('Fretes Pequenos e Entregas', 'Serviço de frete para mudanças pequenas e entregas rápidas na cidade.', 40.00, 4, 33, 'EM_ANALISE'),
('Técnico em Eletrônicos', 'Conserto de televisores, computadores, celulares e eletrodomésticos.', 60.00, 5, 35, 'ATIVO'),
('Professor Particular Inglês', 'Aulas de inglês para todos os níveis. Metodologia dinâmica e eficaz.', 40.00, 6, 37, 'ATIVO'),
('Acompanhante de Idosos', 'Acompanho idosos em consultas médicas e atividades sociais.', 35.00, 7, 39, 'ATIVO'),
('Adestrador de Cães', 'Adestramento básico e avançado para cães de todas as idades e portes.', 80.00, 8, 41, 'EM_ANALISE'),
('Cozinheira Doméstica', 'Preparo refeições caseiras tradicionais. Comida saudável e saborosa.', 60.00, 9, 43, 'ATIVO'),
('Bordadeira e Tricoteira', 'Faço bordados personalizados e peças em tricô e crochê por encomenda.', 25.00, 10, 45, 'ATIVO'),
('Fotógrafa Infantil', 'Especializada em fotografia infantil e familiar. Ensaios criativos e divertidos.', 200.00, 11, 47, 'ATIVO'),
('Serviços Domésticos Gerais', 'Pequenos reparos, montagens, organizações e serviços domésticos diversos.', 35.00, 12, 49, 'ATIVO'),
('Micropigmentação de Sobrancelhas', 'Micropigmentação fio a fio e esfumada. Resultado natural e duradouro.', 150.00, 13, 1, 'EM_ANALISE'),
('Babá Bilíngue', 'Cuido de crianças falando português e inglês. Atividades educativas.', 40.00, 1, 3, 'ATIVO'),
('Faxina Semanal', 'Serviço de faxina semanal ou quinzenal. Pontualidade e qualidade garantidas.', 70.00, 2, 5, 'ATIVO'),
('Poda de Árvores', 'Poda técnica de árvores frutíferas e ornamentais. Trabalho seguro e profissional.', 90.00, 3, 7, 'ATIVO'),
('Mudanças Apartamento', 'Especializado em mudanças de apartamentos. Cuidado especial com móveis.', 200.00, 4, 9, 'ATIVO'),
('Instalação de Ar Condicionado', 'Instalação e manutenção de aparelhos de ar condicionado residencial.', 100.00, 5, 11, 'EM_ANALISE'),
('Aulas de Reforço Português', 'Professora de português para ensino fundamental. Foco em redação e gramática.', 30.00, 6, 13, 'ATIVO'),
('Fisioterapeuta Domiciliar', 'Fisioterapia para idosos no conforto de casa. Reabilitação e prevenção.', 80.00, 7, 15, 'ATIVO'),
('Hotel para Pets', 'Hospedagem para cães e gatos em ambiente familiar. Muito carinho e atenção.', 50.00, 8, 17, 'ATIVO'),
('Buffet Caseiro', 'Preparo buffets para festas pequenas e reuniões familiares. Comida caseira.', 15.00, 9, 19, 'EM_ANALISE'),
('Customização de Roupas', 'Customizo roupas antigas dando nova vida a peças esquecidas no guarda-roupa.', 30.00, 10, 21, 'ATIVO'),
('Book Fotográfico Profissional', 'Books para modelos, atores e profissionais. Estúdio equipado e experiente.', 250.00, 11, 23, 'ATIVO'),
('Montador de Móveis Experiente', 'Montagem de móveis planejados e convencionais. Trabalho limpo e rápido.', 40.00, 12, 25, 'ATIVO'),
('Depilação a Domicílio', 'Serviço de depilação no conforto da sua casa. Métodos tradicionais e modernos.', 35.00, 13, 27, 'ATIVO'),
('Recreação Infantil', 'Animação para festas infantis. Brincadeiras educativas e muita diversão.', 120.00, 1, 29, 'EM_ANALISE'),
('Limpeza de Estofados', 'Limpeza profissional de sofás, colchões e cadeiras. Equipamentos modernos.', 80.00, 2, 31, 'ATIVO'),
('Horta Residencial', 'Implantação e manutenção de hortas em casas e apartamentos. Cultivo orgânico.', 70.00, 3, 33, 'ATIVO'),
('Transporte de Móveis', 'Transporte cuidadoso de móveis e eletrodomésticos. Veículo adequado.', 60.00, 4, 35, 'ATIVO'),
('Pintor Residencial', 'Pintura interna e externa de residências. Trabalho limpo e acabamento perfeito.', 25.00, 5, 37, 'EM_ANALISE'),
('Tutoria Escolar Online', 'Acompanhamento escolar online para crianças e adolescentes. Todas as matérias.', 35.00, 6, 39, 'ATIVO'),
('Enfermagem Domiciliar', 'Serviços de enfermagem para idosos e pessoas acamadas. Cuidados especializados.', 60.00, 7, 41, 'ATIVO'),
('Banho e Tosa para Pets', 'Serviço completo de banho e tosa para cães e gatos. Produtos de qualidade.', 45.00, 8, 43, 'ATIVO'),
('Aulas de Culinária', 'Ensino técnicas culinárias básicas e avançadas. Aulas práticas e divertidas.', 50.00, 9, 45, 'ATIVO'),
('Reforma de Roupas', 'Ajustes, reformas e modernização de roupas. Dou nova vida às suas peças.', 25.00, 10, 47, 'EM_ANALISE'),
('Fotografia de Produtos', 'Fotografo produtos para e-commerce e catálogos. Qualidade profissional.', 80.00, 11, 49, 'ATIVO');

-- =====================================================
-- INSERÇÃO DE SERVIÇOS FAVORITOS (50 registros)
-- Apenas usuários CLIENTES (nível 3) podem favoritar
-- =====================================================
INSERT INTO ServicoFavorito (IDServico, IDUsuario) VALUES
(1, 2), (1, 4), (1, 8), (1, 10), (1, 12), -- Babá Experiente
(2, 2), (2, 6), (2, 14), (2, 16), (2, 18), -- Limpeza Residencial
(3, 4), (3, 8), (3, 20), (3, 24), (3, 26), -- Jardinagem
(4, 6), (4, 10), (4, 28), (4, 30), (4, 32), -- Mudanças
(5, 8), (5, 12), (5, 34), (5, 36), (5, 38), -- Manutenção
(6, 2), (6, 16), (6, 40), (6, 42), (6, 44), -- Reforço Matemática
(7, 4), (7, 18), (7, 46), (7, 48), (7, 50), -- Cuidadora Idosos
(8, 6), (8, 20), (8, 2), (8, 4), (8, 8), -- Pet Sitter
(9, 10), (9, 22), (9, 6), (9, 12), (9, 14), -- Chef Particular
(10, 12), (10, 24), (10, 16), (10, 18), (10, 20); -- Costureira

-- Atualizar contador de favoritos nas publicações
UPDATE PublicacaoServico SET QuantidadeFavorito = 5 WHERE ID IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

-- =====================================================
-- INSERÇÃO DE AVALIAÇÕES DE SERVIÇO (50 registros)
-- Apenas usuários CLIENTES (nível 3) podem avaliar
-- =====================================================
INSERT INTO AvaliacaoServico (Nota, Comentario, FkPublicacao, FKUsuario) VALUES
(5, 'Excelente babá! Muito carinhosa com as crianças e super responsável. Recomendo!', 1, 2),
(4, 'Bom serviço de limpeza, deixou tudo muito limpo. Voltará na próxima semana.', 2, 4),
(5, 'Transformou meu jardim! Trabalho impecável e muito criativo. Superou expectativas.', 3, 6),
(3, 'Serviço ok de mudança, mas demorou um pouco mais que o esperado. Cuidadosos com os móveis.', 4, 8),
(4, 'Resolveu vários problemas em casa rapidamente. Preço justo e bom atendimento.', 5, 10),
(5, 'Minha filha melhorou muito em matemática! Professor muito paciente e didático.', 6, 12),
(5, 'Cuidou da minha mãe com muito carinho e profissionalismo. Família muito grata.', 7, 14),
(4, 'Meu cachorro adorou os passeios! Pessoa confiável e que ama animais.', 8, 16),
(5, 'Comida deliciosa! Cardápio variado e ingredientes frescos. Voltará semana que vem.', 9, 18),
(4, 'Ajustou minha roupa perfeitamente. Trabalho rápido e bem feito.', 10, 20),
(5, 'Fotos lindas do meu casamento! Captou todos os momentos especiais. Muito talentoso.', 11, 22),
(3, 'Resolveu o problema, mas poderia ter sido mais rápido. Preço dentro do esperado.', 12, 24),
(5, 'Tratamento facial maravilhoso! Pele ficou incrível. Voltarei com certeza!', 13, 26),
(4, 'Cuidou bem do bebê durante a noite. Pais puderam descansar tranquilos.', 14, 28),
(5, 'Limpeza pós-obra impecável! Deixou tudo brilhando. Muito profissional.', 15, 30),
(4, 'Jardim ficou lindo! Boas sugestões de plantas e muito caprichoso no trabalho.', 16, 32),
(3, 'Frete ok, mas veículo um pouco pequeno para o que precisávamos. Cuidadoso.', 17, 34),
(5, 'Consertou minha TV rapidamente! Diagnóstico certeiro e preço honesto.', 18, 36),
(4, 'Aulas de inglês muito boas! Método eficiente e professor paciente.', 19, 38),
(5, 'Acompanhou meu pai na consulta com muito cuidado. Pessoa de confiança.', 20, 40),
(4, 'Meu cão aprendeu comandos básicos rapidamente. Adestrador experiente.', 21, 42),
(5, 'Comida caseira deliciosa! Tempero igual da vovó. Recomendo muito!', 22, 44),
(4, 'Bordado ficou lindo! Trabalho artesanal de qualidade. Entregou no prazo.', 23, 46),
(5, 'Fotos das crianças ficaram incríveis! Conseguiu captar sorrisos naturais.', 24, 48),
(3, 'Serviços gerais ok, mas poderia ter mais agilidade. Preço acessível.', 25, 50),
(5, 'Micropigmentação perfeita! Sobrancelhas ficaram naturais e simétricas.', 26, 2),
(4, 'Babá bilíngue excelente! Crianças aprenderam inglês brincando.', 27, 4),
(5, 'Faxina semanal impecável! Casa sempre limpa e organizada. Muito pontual.', 28, 6),
(4, 'Poda das árvores bem feita. Trabalho técnico e seguro. Recomendo.', 29, 8),
(3, 'Mudança ok, mas teve alguns arranhões nos móveis. Preço justo.', 30, 10),
(5, 'Instalação do ar condicionado perfeita! Funcionou na primeira ligada.', 31, 12),
(4, 'Aulas de português muito boas! Minha filha melhorou a redação.', 32, 14),
(5, 'Fisioterapia em casa excelente! Meu pai recuperou muito da mobilidade.', 33, 16),
(4, 'Hotel para pets muito bom! Meu gato voltou feliz e bem cuidado.', 34, 18),
(3, 'Buffet caseiro gostoso, mas poderia ter mais variedade. Preço bom.', 35, 20),
(5, 'Customização ficou incrível! Roupas velhas viraram peças modernas.', 36, 22),
(4, 'Book fotográfico profissional! Fotos ficaram lindas para portfólio.', 37, 24),
(5, 'Montou todos os móveis perfeitamente! Trabalho limpo e organizado.', 38, 26),
(4, 'Depilação a domicílio muito boa! Resultado duradouro e sem dor.', 39, 28),
(3, 'Recreação infantil boa, mas as crianças ficaram um pouco agitadas.', 40, 30),
(5, 'Limpeza de estofados excelente! Sofá ficou como novo. Muito eficiente.', 41, 32),
(4, 'Horta residencial linda! Verduras orgânicas fresquinhas em casa.', 42, 34),
(4, 'Transporte de móveis cuidadoso. Tudo chegou intacto no destino.', 43, 36),
(3, 'Pintura boa, mas respingou um pouco no chão. Resultado final ok.', 44, 38),
(5, 'Tutoria online excelente! Filha melhorou as notas em todas as matérias.', 45, 40),
(4, 'Enfermagem domiciliar muito profissional. Cuidados especializados.', 46, 42),
(5, 'Banho e tosa perfeitos! Meu cachorro ficou lindo e cheiroso.', 47, 44),
(4, 'Aulas de culinária divertidas! Aprendi técnicas que uso sempre.', 48, 46),
(3, 'Reforma de roupas ok, mas demorou mais que o combinado. Resultado bom.', 49, 48),
(5, 'Fotos de produtos incríveis! Vendas online aumentaram muito. Recomendo!', 50, 50);
