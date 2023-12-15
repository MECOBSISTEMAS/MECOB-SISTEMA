ALTER TABLE `contratos` 
ADD COLUMN `dt_acao_judicial` TIMESTAMP NULL DEFAULT NULL AFTER `observacao_zerado`;


ALTER TABLE `contrato_parcelas` 
ADD COLUMN `motivo_zerado` VARCHAR(150) NULL DEFAULT NULL AFTER `fl_negativada`,
ADD COLUMN `observacao_zerado` VARCHAR(2000) NULL DEFAULT NULL AFTER `motivo_zerado`,
ADD COLUMN `fl_acao_judicial` VARCHAR(45) NULL DEFAULT 'N' AFTER `observacao_zerado`;