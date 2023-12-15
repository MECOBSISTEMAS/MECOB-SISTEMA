ALTER TABLE `teds` 
ADD COLUMN `arquivos_id` BIGINT(20) NULL DEFAULT NULL AFTER `conta`;

ALTER TABLE `teds`
ADD INDEX `fk_teds_arquivos1_idx` (`arquivos_id` ASC);

ALTER TABLE `teds` 
ADD CONSTRAINT `fk_teds_arquivos1`
  FOREIGN KEY (`arquivos_id`)
  REFERENCES `arquivos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


ALTER TABLE `arquivos` 
ADD COLUMN `origem` VARCHAR(45) NULL DEFAULT NULL AFTER `dt_processamento`;


ALTER TABLE `teds` 
ADD COLUMN `dv_agencia` VARCHAR(45) NULL DEFAULT NULL AFTER `agencia`,
ADD COLUMN `dv_conta` VARCHAR(45) NULL DEFAULT NULL AFTER `conta`;

ALTER TABLE `contrato_parcelas` 
ADD COLUMN `vl_juros_pagto` DECIMAL(12,2) NULL DEFAULT NULL AFTER `vl_pagto`;


ALTER TABLE `teds` 
CHANGE COLUMN `arquivos_id` `arquivos_id_remessa` BIGINT(20) NULL DEFAULT NULL ,
ADD COLUMN `nu_linha_remessa` INT(11) NULL DEFAULT NULL AFTER `arquivos_id_remessa`,
ADD COLUMN `nu_linha_retorno` INT(11) NULL DEFAULT NULL AFTER `nu_linha_remessa`;


ALTER TABLE `teds` 
DROP FOREIGN KEY `fk_teds_arquivos1`;

ALTER TABLE `teds` ADD CONSTRAINT `fk_teds_arquivos1`
  FOREIGN KEY (`arquivos_id_remessa`)
  REFERENCES `arquivos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
  
ALTER TABLE `teds` 
ADD COLUMN `arquivos_id_retorno` BIGINT(20) NULL DEFAULT NULL AFTER `nu_linha_remessa`,
ADD INDEX `fk_teds_arquivos2_idx` (`arquivos_id_retorno` ASC);

ALTER TABLE `teds` 
ADD CONSTRAINT `fk_teds_arquivos2`
  FOREIGN KEY (`arquivos_id_retorno`)
  REFERENCES `arquivos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;