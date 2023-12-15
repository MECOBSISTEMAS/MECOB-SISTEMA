---arquivos --------------------------------------------------------------------------------------------
 
CREATE TABLE `arquivos` (
  `id` bigint(20) NOT NULL,
  `nm_arq` varchar(100) DEFAULT NULL,
  `dt_arq` datetime DEFAULT NULL,
  `tp_arq` varchar(45) DEFAULT NULL,
  `contratos_id` int(11) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `dt_envio_banco` timestamp NULL DEFAULT NULL,
  `log` longtext,
  `dt_processamento` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `arquivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_arquivos_contratos1_idx` (`contratos_id`);

ALTER TABLE `arquivos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
  
ALTER TABLE `arquivos`
  ADD CONSTRAINT `fk_arquivos_contratos1` FOREIGN KEY (`contratos_id`) REFERENCES `contratos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


CREATE TABLE `dados_arquivo_retorno` (
  `id` bigint(20) NOT NULL,
  `nosso_numero` varchar(45) DEFAULT NULL,
  `id_ocorrencia` varchar(45) DEFAULT NULL,
  `descricao` varchar(1000) DEFAULT NULL,
  `dt_banco` date DEFAULT NULL,
  `dt_vencimento` date DEFAULT NULL,
  `vl_boleto` decimal(13,2) DEFAULT NULL,
  `vl_pago` decimal(13,2) DEFAULT NULL,
  `vl_juros` decimal(13,2) DEFAULT NULL,
  `dt_credito` date DEFAULT NULL,
  `motivo_ocorrencia` varchar(45) DEFAULT NULL,
  `arquivos_id` bigint(20) NOT NULL,
  `nu_linha` int(11) DEFAULT NULL,
  `fl_processado` varchar(45) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `dados_arquivo_retorno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dados_arquivo_retorno_arquivos1_idx` (`arquivos_id`);

ALTER TABLE `dados_arquivo_retorno`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
  
ALTER TABLE `dados_arquivo_retorno`
  ADD CONSTRAINT `fk_dados_arquivo_retorno_arquivos1` FOREIGN KEY (`arquivos_id`) REFERENCES `arquivos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
 
 
ALTER TABLE `contrato_parcelas` 
ADD COLUMN `arquivos_id_remessa` BIGINT(20) NULL DEFAULT NULL AFTER `dt_vencimento_original`,
ADD INDEX `fk_contrato_parcelas_arquivos1_idx` (`arquivos_id_remessa` ASC);

ALTER TABLE `contrato_parcelas` 
ADD CONSTRAINT `fk_contrato_parcelas_arquivos1`
  FOREIGN KEY (`arquivos_id_remessa`)
  REFERENCES `arquivos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
ALTER TABLE `contrato_parcelas` 
ADD COLUMN `arquivos_id_retorno` BIGINT(20) NULL DEFAULT NULL AFTER `arquivos_id_remessa`,
ADD INDEX `fk_contrato_parcelas_arquivos2_idx` (`arquivos_id_retorno` ASC);

ALTER TABLE `contrato_parcelas` 
ADD CONSTRAINT `fk_contrato_parcelas_arquivos2`
  FOREIGN KEY (`arquivos_id_retorno`)
  REFERENCES `arquivos` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  
ALTER TABLE `contrato_parcelas` 
ADD COLUMN `nu_linha_remessa` INT(11) NULL DEFAULT NULL AFTER `arquivos_id_remessa`,
ADD COLUMN `nu_linha_retorno` VARCHAR(45) NULL DEFAULT NULL AFTER `arquivos_id_retorno`;

ALTER TABLE `contrato_parcelas` 
ADD COLUMN `dt_credito` DATE NULL DEFAULT NULL AFTER `nu_linha_retorno`;

ALTER TABLE `contrato_parcelas` 
ADD COLUMN `dt_processo_pagto` TIMESTAMP NULL DEFAULT NULL AFTER `dt_credito`


ALTER TABLE `contratos` 
ADD COLUMN `tp_contrato_boleto` VARCHAR(50) NULL DEFAULT NULL AFTER `termo_nomes_lote`;


ALTER TABLE `contrato_parcelas` 
ADD COLUMN `teds_id` BIGINT(20) NULL DEFAULT NULL AFTER `dt_processo_pagto`,
ADD INDEX `fk_contrato_parcelas_teds1_idx` (`teds_id` ASC);

CREATE TABLE IF NOT EXISTS `teds` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `pessoas_id_vendedor` BIGINT(20) NOT NULL,
  `pessoas_id_inclusao` BIGINT(20) NOT NULL,
  `dt_inclusao` DATETIME NULL DEFAULT NULL,
  `dt_ted` DATE NULL DEFAULT NULL,
  `vl_ted` DECIMAL(12,2) NULL DEFAULT NULL,
  `status_ted` INT(11) NULL DEFAULT NULL,
  `banco` INT(11) NULL DEFAULT NULL,
  `agencia` INT(11) NULL DEFAULT NULL,
  `conta` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_teds_pessoas1_idx` (`pessoas_id_vendedor` ASC),
  INDEX `fk_teds_pessoas2_idx` (`pessoas_id_inclusao` ASC),
  CONSTRAINT `fk_teds_pessoas1`
    FOREIGN KEY (`pessoas_id_vendedor`)
    REFERENCES `pessoas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_teds_pessoas2`
    FOREIGN KEY (`pessoas_id_inclusao`)
    REFERENCES `pessoas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `lancamentos_ted` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `teds_id` BIGINT(20) NOT NULL,
  `valor` DECIMAL(12,2) NULL DEFAULT NULL,
  `tipo` VARCHAR(200) NULL DEFAULT NULL,
  `obs` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_lancamentos_ted_teds1_idx` (`teds_id` ASC),
  CONSTRAINT `fk_lancamentos_ted_teds1`
    FOREIGN KEY (`teds_id`)
    REFERENCES `teds` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `contrato_parcelas` 
ADD CONSTRAINT `fk_contrato_parcelas_teds1`
  FOREIGN KEY (`teds_id`)
  REFERENCES `teds` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;