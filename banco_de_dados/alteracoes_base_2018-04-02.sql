ALTER TABLE `contratos` 
ADD COLUMN `fl_parcelas_zerado` VARCHAR(45) NULL DEFAULT 'N' AFTER `desconto_total`,
ADD COLUMN `dt_parcelas_zerado` TIMESTAMP NULL DEFAULT NULL AFTER `fl_parcelas_zerado`,
ADD COLUMN `motivo_zerado` VARCHAR(150) NULL DEFAULT NULL AFTER `dt_parcelas_zerado`,
ADD COLUMN `observacao_zerado` VARCHAR(2000) NULL DEFAULT NULL AFTER `motivo_zerado`


ALTER TABLE `contrato_parcelas` 
ADD COLUMN `fl_negativada` VARCHAR(45) NULL DEFAULT 'N' AFTER `pessoas_id_atualizacao`;


ALTER TABLE `alertas` 
ADD COLUMN `concluido` VARCHAR(45) NULL DEFAULT 'N' AFTER `link`,
ADD COLUMN `dt_concluido` DATETIME NULL DEFAULT NULL AFTER `concluido`;
