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
    e.Cidade,
    e.Estado,
    COALESCE(AVG(av.Nota), 0) AS MediaAvaliacoes
FROM PublicacaoServico p
    INNER JOIN Usuario u ON u.ID = p.FKUsuario 
    INNER JOIN CategoriaServico cs ON cs.ID = p.FKCategoria
    LEFT JOIN Endereco e ON e.ID = u.FKEndereco
    LEFT JOIN AvaliacaoServico av ON av.FkPublicacao = p.ID
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
    cs.Nome,
    e.Cidade,
    e.Estado;

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