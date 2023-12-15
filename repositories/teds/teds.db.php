<?php
class tedsDB{
			
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function lista_parcelas_teds($ted, &$conexao_BD_1,  $vendedor , $order = "" , $inicial = 0,$limit=30){
		$select = " Select parc.* , ct.id as c_id,
					ct.tp_contrato, ct.honor_adimp, ct.honor_inadimp,
					
					case
					  
					  when tp_contrato_boleto = 'adimplencia' and contratos_id_pai is null
					  then ((honor_adimp / 100) *  vl_pagto  )  
					  else vl_pagto-( vl_pagto / (1+ (honor_adimp / 100)  ))
					end as vl_honorarios
					
					
					from contrato_parcelas parc  ";
		
		$join = "   join contratos ct on ct.id = parc.contratos_id";
		
		$where = " where vl_pagto is not null and vl_pagto > 0 and vl_pagto >= vl_parcela and
						 dt_pagto is  not null and 
						 dt_pagto <> '0000-00-00' and 
						 teds_id is null 
						 and ct.vendedor_id = ".$vendedor."
						 ";
		$order = " order by  dt_credito asc";
		
		//echo $select.$join.$where.$order;
		
		return $conexao_BD_1->query($select.$join.$where.$order);
	
	}
	
	
	function parcelas_para_ted_cliente($conexao_BD_1, $vendedor_id=""){
		
		$select = " Select 
					
					p.nome, p.id, p.cpf_cnpj,
					
					min(dt_credito) as dt_credito,
					COUNT(parc.id) AS total_parcelas,
					
					sum(vl_pagto) 
					- 
					sum(
						case
						  when tp_contrato_boleto = 'adimplencia' and contratos_id_pai is null
						  then ((ct.honor_adimp / 100) *  vl_pagto  )  
						  else vl_pagto-( vl_pagto / (1+ (ct.honor_adimp / 100)  ))
						end
						
						
						) as vl_transferir
					
					
					from contrato_parcelas parc  ";
		
		$join = "   join contratos ct on ct.id = parc.contratos_id 
					join pessoas p on p.id = ct.vendedor_id
		
		";
		
		$where = " where vl_pagto is not null and vl_pagto > 0 and vl_pagto >= vl_parcela and
						 dt_pagto is  not null and 
						 dt_pagto <> '0000-00-00' and 
						 teds_id is null 
						 ";
		if(!empty($vendedor_id)){
			$where .= "	 and ct.vendedor_id = ".$vendedor_id."";
		}
		
		$order = " group by p.nome, p.id, p.cpf_cnpj
				   order by  dt_credito asc
		";
		
		//echo $select.$join.$where.$order;
		
		return $conexao_BD_1->query($select.$join.$where.$order);
		
	}
	
	
	function lista_teds($ted, &$conexao_BD_1,  $filtros = "" , $order = "" , $inicial = 0,$limit=30){
		
		
		$select =" select t.* , arq.pessoas_id_envio , 
					pv.nome, pv.cpf_cnpj , pv.id as p_id ,  
					pi.nome as nome_incluiu , pi.cpf_cnpj as doc_incluiu ,
				   (select sum(valor) from  lancamentos_ted where teds_id = t.id ) as tt_lancamentos,
				   (select count(*) from  contrato_parcelas where teds_id = t.id ) as tt_parcelas
				   from teds t ";
		$join = "  join pessoas pv on pv.id = t.pessoas_id_vendedor   
				   join pessoas pi on pi.id = t.pessoas_id_inclusao 
				   left join arquivos arq on arq.id = t. arquivos_id_remessa 
				";
				 
		$where  = " where 1 = 1 ";
		$where .= $this->retorna_where_lista_teds( $filtros, $ted); 	
		
		$orderby = " ORDER BY ";
		if($order != ""){
			$orderby.=$order;
		}
		$orderby .= "  t.id asc  ";
		
		if ($limit == "N"){
			$limite = "";
		}
		else{	
			if ($limit == ""){
				$limit = 30;
			}	
			$limite = " LIMIT $inicial, $limit  ";
		}
		
		//echo $select.$join.$where.$orderby.$limite;
		return $conexao_BD_1->query($select.$join.$where.$orderby.$limite);	
	}
	
	function lista_totais_teds($filtros, &$conexao_BD_1, $teds=""){
		$where 	 = " where 1 = 1 ";
				
		$select = " select count(t.id) total_teds ,
					sum(vl_ted) vl_ted
					from teds t					
					";
		
		$join = "  join pessoas pv on pv.id = t.pessoas_id_vendedor   ";
					
		$where .= $this->retorna_where_lista_teds($filtros, $teds); 
		
			
		$conexao_BD_1->query($select.$join.$where);	
		
		$reg = $conexao_BD_1->leRegistro();
		$retorno['total_teds']  = $reg["total_teds"];
		$retorno['vl_ted']  = $reg["vl_ted"];
		

		$select_lancs = " select sum(valor) valor , tipo
					from lancamentos_ted lc
					where teds_id in (	select t.id from teds t  
										".$join ." 
										".$where." 
									 )
				    group by tipo
		 ";
		 
		$retorno['lancamentos'] =  $conexao_BD_1->query($select_lancs);	
		return $retorno;
		
	}
	
	function retorna_where_lista_teds($filtros, $teds=""){	
		$where = " "; 
		
		if($teds!=''){
			if($teds->id !=""){ $where .= " AND t.id = ".$teds->id ; }
			if($teds->pessoas_id_vendedor !=""){ $where .= " AND t.pessoas_id_vendedor = ".$teds->pessoas_id_vendedor ; }
		}
				
		if($filtros!=""){ 

			if(!empty($filtros["filtro_id"])){
				$where .= "  AND t.id =".$filtros["filtro_id"]." ";	
			}
			if(!empty($filtros["filtro_per_ini"]) || !empty($filtros["filtro_per_fim"])){
				if(!empty($filtros["filtro_per_ini"]) && !empty($filtros["filtro_per_fim"])){
					$where .= " AND t.dt_ted BETWEEN '".ConverteData($filtros["filtro_per_ini"])."' and '".ConverteData($filtros["filtro_per_fim"])."' ";
				}
				elseif(!empty($filtros["filtro_per_ini"])){
					$where .= " AND t.dt_ted = '".ConverteData($filtros["filtro_per_ini"])."' ";
				}
				elseif(!empty($filtros["filtro_per_fim"])){
					$where .= " AND t.dt_ted = '".ConverteData($filtros["filtro_per_fim"])."' ";
				}
			}
			
			if(!empty($filtros["filtro_dt_inclusao"])){
				$where .= "  AND t.dt_inclusao between '".ConverteData($filtros["filtro_dt_inclusao"])." 00:00:00' 
				and '".ConverteData($filtros["filtro_dt_inclusao"])." 23:59:59' ";	
			}
			if(!empty($filtros["filtro_status"])){
				$where .= "  AND t.status_ted  = ".$filtros["filtro_status"]."  ";	
			}

			if(!empty($filtros["filtro_vendedor"])){
				if(is_numeric($filtros["filtro_vendedor"])){
					$where .= "  AND  pv.id = ".$filtros["filtro_vendedor"];
				}
				elseif($filtros["filtro_vendedor"][0] == '*'){
						$filtro_vendedor = str_replace('*','',$filtros["filtro_vendedor"]);
						$busca_array = explode(' ',$filtro_vendedor);
						foreach($busca_array as $busca_item){
							if(!empty(trim($busca_item)))
								$where .="  AND  (       remove_acentos(pv.nome) LIKE '%".$busca_item."%' 
											  OR remove_acentos(pv.apelido) LIKE '%".$busca_item."%' 
											  OR pv.email LIKE '%".$busca_item."%' 
											  OR pv.cpf_cnpj LIKE '%".$busca_item."%'
											   )  ";
						}
				}
				else{
					$busca = trata_busca_sql_score($filtros["filtro_vendedor"]);
					if(isset($busca['multi'])){
						$where .= "  AND MATCH (pv.nome,pv.apelido,pv.email,pv.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";			
					}
					else{
						$where .="  AND  (       remove_acentos(pv.nome) LIKE '%".$busca['simples']."%' 
											  OR remove_acentos(pv.apelido) LIKE '%".$busca['simples']."%' 
											  OR pv.email LIKE '%".$busca['simples']."%' 
											  OR pv.cpf_cnpj LIKE '%".$busca['simples']."%'
											   )  ";	
					}
				}
			}
		}
		
		return $where;	
	}
	
	function atualiza_parcelas_teds($ted_id, $ids_parcelas, &$conexao_BD_1){
		$update = " update contrato_parcelas set teds_id = ".$ted_id."  where id in ( ";
		$ct_pcs =0;
		foreach($ids_parcelas as $id_pd){
			if($ct_pcs){$update .= ",";}
			$update .= $id_pd;
			$ct_pcs++;
		}
		$update .= ")";
		
		//echo $update;
		if($ct_pcs){
			return $conexao_BD_1->query_atualizacao($update);
		}else{
			return 1;
		}
	}
	
	function insert_lancamentos_teds($ted_id, $arr_lancamentos, &$conexao_BD_1){
		$insert = " insert into lancamentos_ted (teds_id, valor, tipo, obs) values ";
		$ct_lnc =0;
		foreach($arr_lancamentos as $lancamento){
			if(!is_numeric($lancamento['inputLcValor'])) continue;
			if($ct_lnc){$insert .= ",";}
			$insert .= " (  ".$ted_id.", 
							".$lancamento['inputLcValor'].", 
							'".$lancamento['inputLcTipo']."', 
							'".$lancamento['inputLcObs']."' 
						  ) ";
			$ct_lnc++;
		}
		if($ct_lnc){
			return $conexao_BD_1->query_inserir($insert);
		}else{
			return 1;
		}
	}
	
	function desrelaciona_ted($ted_id,  &$conexao_BD_1){
		$update = "update contrato_parcelas set teds_id = null where teds_id = ".$ted_id;
		$conexao_BD_1->query_atualizacao($update);
		
		$delete = "delete from lancamentos_ted where teds_id = ".$ted_id;
		$conexao_BD_1->query_atualizacao($delete);
		
		$update = "update teds set status_ted = 4  where id = ".$ted_id;
		$conexao_BD_1->query_atualizacao($update);
		
	}
	
	function remove_ted($ted_id,  &$conexao_BD_1){

	    $update = "update contrato_parcelas set teds_id = null where teds_id = ".$ted_id;
		$conexao_BD_1->query_atualizacao($update);
		
		$delete = "delete from lancamentos_ted where teds_id = ".$ted_id;
		$conexao_BD_1->query_atualizacao($delete);
        //selecionar a TED, para saber o nome do arquivo a ser deletado
        include_once(getenv('CAMINHO_RAIZ')."/repositories/teds/teds.class.php");
        $ted = new teds();
        $ted->id = $ted_id;
        $filtros['filtro_id'] = $ted_id;
        $regTed = $this->lista_teds($ted, $conexao_BD_1,  $filtros , "");
        $id_arquivo = $regTed[0]["arquivos_id_remessa"];
        include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.class.php");
	    include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.db.php");
        $arquivo    = new arquivos();
        $arquivos_db = new arquivosDB();
        $arquivo->id = $id_arquivo;
        $regArq = $arquivos_db->lista_arquivos($arquivo, $conexao_BD_1);
        $delete = "delete from  teds where id = ".$ted_id;
		$conexao_BD_1->query_atualizacao($delete);
		if(file_exists(getenv('CAMINHO_RAIZ').'/teds/remessa/'.$regArq[0]["nm_arq"])){
			unlink(getenv('CAMINHO_RAIZ').'/teds/remessa/'.$regArq[0]["nm_arq"]);
		}
		$delete = "delete from  arquivos where id = ".$id_arquivo;
		$conexao_BD_1->query_atualizacao($delete);
        return 1;
	}
	
	function lista_domicilios_teds($id , &$conexao_BD_1 ){
		$select = "select distinct banco, agencia, conta, dv_agencia, dv_conta from teds where pessoas_id_vendedor = ".$id." and ( del_domc_bancario is null or del_domc_bancario = 0)";
		return $conexao_BD_1->query($select);	
	}
	
	function lista_lancamentos_ted($ted_id, &$conexao_BD_1){
		$select = "select * from lancamentos_ted where teds_id = ".$ted_id;
		return $conexao_BD_1->query($select);	
	}
	function lista_parcelas_ted($ted_id, &$conexao_BD_1){
		$select = "select * from contrato_parcelas p
				   join contratos c on c.id = p.contratos_id where teds_id = ".$ted_id;
		return $conexao_BD_1->query($select);	
	}

    function busca_dados_arquivos_remessa(&$conexao_BD_1, $ted_id){

        $select = " select t.id, t.banco, t.agencia, t.dv_agencia, t.conta, t.dv_conta, date_format(t.dt_ted, '%d%m%Y') as dt_pagto_ted, t.vl_ted, 
                           p.nome, p.cpf_cnpj, p.rua, p.numero, p.complemento, p.bairro, p.cidade, p.cep, p.estado
                    from teds t
                    join pessoas p on p.id = t.pessoas_id_vendedor
                    where t.id = ".$ted_id;
        return $conexao_BD_1->query($select);

    }

    function atualiza_ted_arquivo($conexao_BD_1, $ted_id, $tp_arq, $arquivo_id, $linha_arq, $status_ted=''){

        if ($tp_arq == "REMESSA"){
            $up = " arquivos_id_remessa = ".$arquivo_id.
                  ",nu_linha_remessa    = ".$linha_arq.
                  ", status_ted = 1";
        }
        elseif ($tp_arq == "PREVIA"){
            $up = " arquivos_id_retorno_previa = ".$arquivo_id.
                  ",nu_linha_retorno_previa    = ".$linha_arq.
                  ", status_ted = 2";
        }
        elseif ($tp_arq == "PROCESSAMEN"){
            $up = " arquivos_id_retorno_processamento = ".$arquivo_id.
                  ",nu_linha_retorno_processamento    = ".$linha_arq.
                  ", status_ted = 2";
        }
        elseif ($tp_arq == "CONSOLIDADO"){
            $up = " arquivos_id_retorno_consolidado = ".$arquivo_id.
                  ",nu_linha_retorno_consolidado    = ".$linha_arq.
                  ", status_ted = 3";
        }
        $update = " update teds 
					set ".$up."					
					where id = ".$ted_id;
        $retorno = $conexao_BD_1->query($update);
		
		if( $status_ted == 4){
			$this->desrelaciona_ted($ted_id , $conexao_BD_1);
		}
		
		return $retorno ;
    }
	
	function del_domc($domicilio,  &$conexao_BD_1){
		$array_domc = explode('-',$domicilio);
		$update = " update teds set del_domc_bancario  = 1 where 
					banco = '".$array_domc[0]."' and
					agencia = '".$array_domc[1]."' and
					dv_agencia = '".$array_domc[2]."' and
					conta = '".$array_domc[3]."' and
					dv_conta = '".$array_domc[4]."' 
		";
		$conexao_BD_1->query_atualizacao($update);
	}
	
}