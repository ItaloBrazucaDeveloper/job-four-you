DELIMITER //

DROP TRIGGER IF EXISTS AumentaFavorito//

CREATE TRIGGER AumentaFavorito
AFTER INSERT ON ServicoFavorito
FOR EACH ROW
BEGIN
    UPDATE PublicacaoServico
    SET QuantidadeFavorito = QuantidadeFavorito + 1
    WHERE PublicacaoServico.ID = NEW.IDServico;
END//

DELIMITER ;

DELIMITER //

DROP TRIGGER IF EXISTS DiminuiFavorito//

CREATE TRIGGER DiminuiFavorito
AFTER DELETE ON ServicoFavorito
FOR EACH ROW
BEGIN
    UPDATE PublicacaoServico
    SET QuantidadeFavorito = QuantidadeFavorito - 1
    WHERE PublicacaoServico.ID = OLD.IDServico AND QuantidadeFavorito >= 0;
END//

DELIMITER ;