ALTER TABLE `arquivos` 
ADD COLUMN `pessoas_id_envio` BIGINT(20) NULL DEFAULT NULL AFTER `origem`,
ADD INDEX `fk_arquivos_pessoas1_idx` (`pessoas_id_envio` ASC);

ALTER TABLE `arquivos` 
ADD CONSTRAINT `fk_arquivos_pessoas1`
  FOREIGN KEY (`pessoas_id_envio`)
  REFERENCES `pessoas` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;



ALTER TABLE `contratos` ADD COLUMN `gerar_boleto` VARCHAR(45) NULL DEFAULT 'S' AFTER `tp_contrato_boleto`;


