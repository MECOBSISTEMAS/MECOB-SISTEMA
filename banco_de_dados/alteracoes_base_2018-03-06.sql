ALTER TABLE `contrato_parcelas` 
ADD COLUMN `pessoas_id_atualizacao` BIGINT(20) NULL DEFAULT NULL AFTER `tratar_ted`,
ADD INDEX `fk_contrato_parcelas_pessoas1_idx` (`pessoas_id_atualizacao` ASC);

ALTER TABLE `contrato_parcelas` 
ADD CONSTRAINT `fk_contrato_parcelas_pessoas1`
  FOREIGN KEY (`pessoas_id_atualizacao`)
  REFERENCES `pessoas` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;