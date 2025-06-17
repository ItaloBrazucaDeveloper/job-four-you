INSERT INTO CategoriaServico (Nome) VALUES
    ('Babá'), -- 1
    ('Limpeza'), -- 2
    ('Jardinagem'), -- 3
    ('Mudança/Frete'), -- 4
    ('Manutenção'), -- 5
    ('Reforço Escolar'), -- 6
    ('Cuidador de Idoso'), -- 7
    ('Cuidado Pet'), -- 8
    ('Cozinha'), -- 9
    ('Costura'), -- 10
    ('Fotógrafo'), -- 11
    ('Marido de Aluguel'), -- 12
    ('Esteticista'); -- 13

INSERT INTO CategoriaContato (Nome) VALUES
    ('Whatsapp'),
    ('Instagram'),
    ('Celular'),
    ('Facebook'),
    ('Email'),
    ('Outros');

 INSERT INTO NivelAcesso (Grupo) VALUES
    -- Revisa e gerencia publicações, remove conteúdo impróprio, bane usuários, visualiza denúncias
    ('ADMINISTRADOR'),
    -- Cria/gerencia/atualiza suas próprias publicações de serviço
    ('PRESTADOR'),
    -- Procura por serviços, entra em contato com prestadores, avalia serviços
    ('CLIENTE');