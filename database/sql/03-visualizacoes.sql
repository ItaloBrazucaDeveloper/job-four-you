CREATE OR REPLACE VIEW ViewUsuarioLogin AS
SELECT
    u.ID,
    u.Nome,
    u.Foto,
    u.StatusUsuario,
    c.Email,
    c.Senha,
    na.Grupo
FROM Usuario u
    INNER JOIN Credencial c ON c.ID = u.FKCredencial
    INNER JOIN NivelAcesso na ON na.ID = c.FKNivelAcesso;

CREATE OR REPLACE VIEW ViewPublicacao AS
SELECT
    p.ID AS IDPublicacao,
    u.Nome AS NomeUsuario,
    u.Foto AS FotoUsuario,
    p.Titulo,
    p.Sobre,
    p.Valor,
    p.QuantidadeFavorito,
    p.DataCriacao AS PublicadoEm,
    p.UltimaAtualizacao AS EditadoEm,
    cs.Nome AS Categoria,
    MAX(CASE WHEN cc.Nome = 'Email' THEN ic.Contato END) AS Email,
    MAX(CASE WHEN cc.Nome = 'Facebook' THEN ic.Contato END) AS Facebook,
    MAX(CASE WHEN cc.Nome = 'Celular' THEN ic.Contato END) AS Celular,
    MAX(CASE WHEN cc.Nome = 'WhatsApp' THEN ic.Contato END) AS Whatsapp,
    MAX(CASE WHEN cc.Nome = 'Instagram' THEN ic.Contato END) AS Instagram,
    MAX(CASE WHEN cc.Nome = 'Outro' THEN ic.Contato END) AS OutroContato
FROM PublicacaoServico p
    INNER JOIN Usuario u ON u.ID = p.FKUsuario 
    INNER JOIN CategoriaServico cs ON cs.ID = p.FKCategoria
    LEFT JOIN InformacaoContato ic ON ic.FKUsuario = u.ID
    LEFT JOIN CategoriaContato cc ON cc.ID = ic.FKCategoriaContato
WHERE p.StatusPublicacao = 'ATIVO' AND u.StatusUsuario = 'ATIVO'
GROUP BY 
    p.ID,
    u.Nome,
    u.Foto,
    p.Titulo,
    p.Sobre,
    p.Valor,
    p.QuantidadeFavorito,
    p.DataCriacao,
    p.UltimaAtualizacao,
    cs.Nome;

CREATE OR REPLACE VIEW ViewAvaliacaoServico AS
SELECT
    a.ID AS IDAvaliacao,
    a.Nota,
    a.Comentario,
    a.FkPublicacao AS IDPublicacao,
    a.FKUsuario AS IDUsuario,
    u.Nome AS NomeUsuario,
    u.Foto AS FotoUsuario,
    a.DataCriacao AS PublicadoEm,
    a.UltimaAtualizacao as EditadoEm
FROM AvaliacaoServico a
    INNER JOIN Usuario u ON u.ID = a.FKUsuario
WHERE u.StatusUsuario = 'ATIVO';