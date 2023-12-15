<?php
class boletos_avulsoDB{
			
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function insere_valores_boleto(&$conexao_BD_1, $boletos_avulso_id, $dt_vencimento, $vl_boleto){

        $insert_pagto = "insert into contrato_parcelas 
									( boletos_avulso_id, dt_vencimento, vl_parcela, vl_corrigido )
							values  ( ".$boletos_avulso_id." ,'".$dt_vencimento."', ".$vl_boleto.", ".$vl_boleto." )";

		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Inserir boleto avulso ' . json_encode($insert_pagto));

		$conexao_BD_1->query_inserir($insert_pagto);
    }

    function dados_boleto_avulso(&$conexao_BD_1, $boletos_avulso_id){

        $select = "select cp.id as nosso_numero, date_format(cp.dt_vencimento, '%d%m%y') dt_vencimento, date_format(b.dt_boleto, '%d%m%y') dt_contrato,
						  cp.vl_corrigido, p.cpf_cnpj, p.nome, p.rua, p.numero, p.bairro, p.cidade, p.estado, p.cep, p.email
					from boletos_avulso b
					join contrato_parcelas cp on b.id = cp.boletos_avulso_id
					join pessoas p on b.pessoas_id = p.id
					where b.id = ".$boletos_avulso_id." and 
					      (dt_pagto is null or dt_pagto = '0000-00-00' ) and
					      arquivos_id_remessa is null ";

        return $conexao_BD_1->query($select);
    }

    function dados_boleto_avulso_cancelar(&$conexao_BD_1, $boletos_avulso_id){

        $select = "select cp.id as nosso_numero, date_format(cp.dt_vencimento, '%d%m%y') dt_vencimento, date_format(b.dt_boleto, '%d%m%y') dt_contrato,
						  cp.vl_corrigido, p.cpf_cnpj, p.nome, p.rua, p.numero, p.bairro, p.cidade, p.estado, p.cep, p.email
					from boletos_avulso b
					join contrato_parcelas cp on b.id = cp.boletos_avulso_id
					join pessoas p on b.pessoas_id = p.id
					where b.id = ".$boletos_avulso_id." and 
					      (dt_pagto is null or dt_pagto = '0000-00-00' ) and
					      not arquivos_id_remessa is null ";

        return $conexao_BD_1->query($select);
    }


    function lista_boletos_avulso($conexao_BD_1, $boletos_avulso,  $filtros="", $order="",$inicial =0,$limit=30){

        $where 	 = " where 1 = 1 ";

		$select = "select b.id, cp.id as nosso_numero, cp.dt_vencimento, b.dt_boleto, 
					cp.vl_corrigido,vl_pagto, cp.dt_credito, p.nome, p.cpf_cnpj,
					p.rua as comprador_rua, p.numero as comprador_numero, 
					p.bairro as comprador_bairo, p.cidade as comprador_cidade,
					p.estado as comprador_estado, p.cep as comprador_cep,
					b.contratos_id as contratos_id, b.descricao as descricao
					from boletos_avulso b
					join contrato_parcelas cp on b.id = cp.boletos_avulso_id
					join pessoas p on b.pessoas_id = p.id";

        $where .= $this->retorna_where_lista_boletos($filtros, $boletos_avulso);

        $orderby = " ORDER BY ";
        if($order != ""){
            $orderby.=$order;
        }
        $orderby .= "  b.id asc  ";

        if ($limit == "N"){
            $limite = "";
        }
        else{
            if ($limit == ""){
                $limit = 30;
            }
            $limite = " LIMIT $inicial, $limit  ";
        }

        //echo $select.$where.$orderby.$limite;
        return $conexao_BD_1->query($select.$where.$orderby.$limite);
    }

    function lista_totais_boletos_avulso($conexao_BD_1, $filtros){

        $where 	 = " where 1 = 1 ";

        $select = " select count(b.id) total_boletos
                    from boletos_avulso b 
                    join contrato_parcelas cp on b.id = cp.boletos_avulso_id
					join pessoas p on b.pessoas_id = p.id ";

        $where .= $this->retorna_where_lista_boletos($filtros);
        //echo $select.$where;
        $conexao_BD_1->query($select.$where);
        $reg = $conexao_BD_1->leRegistro();
        return $reg["total_boletos"];
    }

    function retorna_where_lista_boletos($filtros, $boletos_avulso=''){

        $where = " ";

        if($boletos_avulso!=''){
            if($boletos_avulso->id !=""){ $where .= " AND b.id = ".$boletos_avulso->id ; }
        }

        if($filtros!=""){
			
			if(!empty($filtros["filtro_data"]) || !empty($filtros["filtro_data_fim"])){
                if(!empty($filtros["filtro_data"]) && !empty($filtros["filtro_data_fim"])){
                    $where .= " AND  cp.dt_vencimento BETWEEN '".ConverteData($filtros["filtro_data"])."' and '".ConverteData($filtros["filtro_data_fim"])."' ";
                }
                elseif(!empty($filtros["filtro_data"])){
                    $where .= " AND cp.dt_vencimento = '".ConverteData($filtros["filtro_data"])."' ";
                }
                elseif(!empty($filtros["filtro_data_fim"])){
                    $where .= " AND cp.dt_vencimento = '".ConverteData($filtros["filtro_data_fim"])."' ";
                }
            }
			
			if(!empty($filtros["filtro_status"])){
				if($filtros["filtro_status"]==1){
					$where .= "  AND (cp.dt_vencimento < '".date('Y-m-d')."') 
								 and ( cp.dt_pagto is null or cp.dt_pagto= '0000-00-00' )";	
				}
				elseif($filtros["filtro_status"]==2){
					$where .= "  AND (cp.dt_vencimento >= '".date('Y-m-d')."') 
								 and ( cp.dt_pagto is null or cp.dt_pagto= '0000-00-00' )";	
				}
				elseif($filtros["filtro_status"]==3){
					$where .= "  and ( cp.dt_pagto is not null and cp.dt_pagto <> '0000-00-00' )";
				} 
			}

            if(!empty($filtros["filtro_proprietario"])){
				if(is_numeric($filtros["filtro_proprietario"])){
					$where .= "  AND p.id = ".$filtros["filtro_proprietario"];
				}
				else{
					$busca = trata_busca_sql_score($filtros["filtro_proprietario"]);
					if(isset($busca['multi'])){
						$where .= "  AND MATCH (p.nome,p.apelido,p.email,p.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE) ";
					}
					else{
						$where .=" AND (
										   remove_acentos(p.nome) LIKE '%".$busca['simples']."%' 
											OR remove_acentos(p.apelido) LIKE '%".$busca['simples']."%' 
											OR p.email LIKE '%".$busca['simples']."%' 
											OR p.cpf_cnpj LIKE '%".$busca['simples']."%'
											   
									   )
								 ";
					}
				}

            }
        }
        return $where;
    }

    function remover_boletos_avulso(&$conexao_BD_1, $boletos_avulso){
		
		if(is_numeric($boletos_avulso->id)){
		
			//remove o registro da parcela do boleto
			$delete = "DELETE FROM contrato_parcelas WHERE boletos_avulso_id = ".$boletos_avulso->id;
			$conexao_BD_1->query_atualizacao($delete);
	
			//remove o arquivo fisico
			include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.class.php");
			$arquivos = new arquivos();
			$arquivos->boletos_avulso_id = $boletos_avulso->id;
			if ($arquivos->boletos_avulso_id != null) {
				$conexao_BD_1->select($arquivos);
				if ($conexao_BD_1->numeroDeRegistros() > 0) {
					$reg = $conexao_BD_1->leRegistro();
					$nm_arquivo = $reg["nm_arq"];
					unlink(getenv('CAMINHO_RAIZ') . '/boletos/remessa/' . $nm_arquivo);
				}
			}
			//remove o registro do arquivo
			$delete = "DELETE FROM arquivos WHERE boletos_avulso_id = ".$boletos_avulso->id;
			$conexao_BD_1->query_atualizacao($delete);
	
			//remove o registro do boleto avulso 
			$delete = "DELETE FROM boletos_avulso_id WHERE id = ".$boletos_avulso->id;
			$conexao_BD_1->query_atualizacao($delete);
	
			return 1;
		}
		else{
			return 0;
		}
    }

}

?>