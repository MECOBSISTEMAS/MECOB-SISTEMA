ALTER TABLE `teds` DROP FOREIGN KEY `fk_teds_arquivos2`;

ALTER TABLE `teds` 
	CHANGE COLUMN `arquivos_id_retorno` `arquivos_id_retorno_previa` BIGINT(20) NULL DEFAULT NULL ,
	CHANGE COLUMN `nu_linha_retorno` `nu_linha_retorno_previa` INT(11) NULL DEFAULT NULL; 
	
ALTER TABLE `teds` 
	ADD CONSTRAINT `fk_teds_arquivos2` 
		FOREIGN KEY (`arquivos_id_retorno_previa`)  
		REFERENCES `arquivos` (`id`) 
		ON DELETE NO ACTION 
		ON UPDATE NO ACTION;


ALTER TABLE `teds` 
ADD COLUMN `arquivos_id_retorno_processamento` BIGINT(20) NULL DEFAULT NULL AFTER `nu_linha_retorno_previa`,
ADD COLUMN `nu_linha_retorno_processamento` INT(11) NULL DEFAULT NULL AFTER `arquivos_id_retorno_processamento`,
ADD COLUMN `arquivos_id_retorno_consolidado` BIGINT(20) NULL DEFAULT NULL AFTER `nu_linha_retorno_processamento`,
ADD COLUMN `nu_linha_retorno_consolidado` INT(11) NULL DEFAULT NULL AFTER `arquivos_id_retorno_consolidado`,
ADD INDEX `fk_teds_arquivos3_idx` (`arquivos_id_retorno_processamento` ASC),
ADD INDEX `fk_teds_arquivos4_idx` (`arquivos_id_retorno_consolidado` ASC);

ALTER TABLE `teds` 
ADD CONSTRAINT `fk_teds_arquivos3`
  FOREIGN KEY (`arquivos_id_retorno_processamento`)
  REFERENCES `arquivos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_teds_arquivos4`
  FOREIGN KEY (`arquivos_id_retorno_consolidado`)
  REFERENCES `arquivos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
  
  
ALTER TABLE `teds` 
ADD COLUMN `del_domc_bancario` INT(11) NULL DEFAULT 0 AFTER `nu_linha_retorno_consolidado`;