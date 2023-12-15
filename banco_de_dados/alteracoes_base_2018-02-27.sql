ALTER TABLE `contratos` 
ADD COLUMN `desconto_total` DECIMAL(12,2) NULL DEFAULT NULL AFTER `gerar_boleto`;