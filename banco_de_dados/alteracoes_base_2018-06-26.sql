ALTER TABLE `contrato_parcelas` 
CHANGE COLUMN `contratos_id` `contratos_id` INT(11) NULL DEFAULT NULL ,
ADD COLUMN `boletos_avulso_id` BIGINT(20) NULL DEFAULT NULL AFTER `fl_acao_judicial`,
ADD INDEX `fk_contrato_parcelas_boletos_avulso1_idx` (`boletos_avulso_id` ASC);

ALTER TABLE `arquivos` 
ADD COLUMN `boletos_avulso_id` BIGINT(20) NULL DEFAULT NULL AFTER `pessoas_id_envio`,
ADD INDEX `fk_arquivos_boletos_avulso1_idx` (`boletos_avulso_id` ASC);

CREATE TABLE IF NOT EXISTS `boletos_avulso` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `dt_boleto` DATE NOT NULL,
  `pessoas_id` BIGINT(20) NOT NULL,
  `pessoas_id_inclusao` BIGINT(20) NOT NULL,
  `contratos_id` INT(11) NOT NULL,
  `descricao` VARCHAR(1000) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_pessoa_boletos_pessoas1_idx` (`pessoas_id` ASC),
  INDEX `fk_boletos_avulso_pessoas1_idx` (`pessoas_id_inclusao` ASC),
  INDEX `fk_boletos_avulso_contratos1_idx` (`contratos_id` ASC),
  CONSTRAINT `fk_pessoa_boletos_pessoas1`
    FOREIGN KEY (`pessoas_id`)
    REFERENCES `pessoas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_boletos_avulso_pessoas1`
    FOREIGN KEY (`pessoas_id_inclusao`)
    REFERENCES `pessoas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_boletos_avulso_contratos1`
    FOREIGN KEY (`contratos_id`)
    REFERENCES `contratos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `contrato_parcelas` 
DROP FOREIGN KEY `fk_contrato_parcelas_contratos1`;

ALTER TABLE `contrato_parcelas` ADD CONSTRAINT `fk_contrato_parcelas_contratos1`
  FOREIGN KEY (`contratos_id`)
  REFERENCES `contratos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_contrato_parcelas_boletos_avulso1`
  FOREIGN KEY (`boletos_avulso_id`)
  REFERENCES `boletos_avulso` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `arquivos` 
ADD CONSTRAINT `fk_arquivos_boletos_avulso1`
  FOREIGN KEY (`boletos_avulso_id`)
  REFERENCES `boletos_avulso` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
ALTER TABLE `boletos_avulso` 
CHANGE COLUMN `contratos_id` `contratos_id` INT(11) NULL DEFAULT NULL ;

ALTER TABLE `boletos_avulso` 
ADD CONSTRAINT `fk_boletos_avulso_contratos1`
  FOREIGN KEY (`contratos_id`)
  REFERENCES `contratos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;  
