<?php
class contratosDB{
    
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }
    
    public function __get($atrib){
        return $this->$atrib;
    }
    
    function lista_contratos($contratos, &$conexao_BD_1,  $filtros = "" , $order = "" , $inicial = 0,$limit=30,$ocorrencia=0){
        
        $data_atual = date("Y-m-d");
        $data_prox = new DateTime($data_atual);
        $data_prox->sub(new DateInterval("P5D"));
        $dtm5 = $data_prox->format('Y-m-d');
        
        $select_ocorrencia="";
        if($ocorrencia)
        $select_ocorrencia = "  oc.status as oc_status, oc.mensagem as oc_mensagem, oc.data_ocorrencia,   ";
        
        $select =" select (select max(arq.id) from arquivos as arq where arq.contratos_id = c.id and arq.status <> 'CORROMPIDO') arquivo_id, c.*,
        ".$select_ocorrencia."
        pc.id as comprador_id, pc.nome as comprador_nome, pc.email as comprador_email, pc.foto as comprador_foto, pc.cpf_cnpj as comprador_cpf_cnpj,  pc.telefone as comprador_telefone,  pc.celular as comprador_celular, 
        
        pc.rua as comprador_rua, pc.numero as comprador_numero, pc.complemento as comprador_complemento,
        pc.bairro as comprador_bairro, pc.cidade as comprador_cidade, pc.estado as comprador_estado, pc.cep as comprador_cep,  pc.nacionalidade as comprador_nacionalidade,
        
        pv.id as vendedor_id, pv.nome as vendedor_nome, pv.email as vendedor_email, pv.foto as vendedor_foto, pv.cpf_cnpj as vendedor_cpf_cnpj, pv.nacionalidade as vendedor_nacionalidade,
        
        pv.rua as vendedor_rua, pv.numero as vendedor_numero, pv.complemento as vendedor_complemento,
        pv.bairro as vendedor_bairro, pv.cidade as vendedor_cidade, pv.estado as vendedor_estado, pv.cep as vendedor_cep,   pv.telefone as vendedor_telefone,  pv.celular as vendedor_celular, 
        
        e.id as eventos_id, e.nome as evento_nome, e.tipo as evento_tipo, pl.nome as leiloeiro_nome,
        
        (SELECT max(id) FROM contratos cf WHERE cf.contratos_id_pai = c.id and cf.status <> 'excluido') AS contrato_filho ,
        
        (select count(*) from contrato_parcelas cp where cp.contratos_id = c.id ) pc_total,
        (select count(*) from contrato_parcelas cp 
        where cp.contratos_id = c.id  and 
        (dt_pagto is not null and 
        dt_pagto <> '0000-00-00' )) pc_liqd,
        (select count(*) from contrato_parcelas cp where cp.contratos_id = c.id and 
        dt_vencimento < '".date('Y-m-d')."' and
        (dt_pagto is null or 
        dt_pagto = '0000-00-00' ) ) pc_atrasada,
        
        c.tp_contrato_boleto ,
        
        c.suspenso, c.dt_suspensao, c.dt_retorno_suspensao,
        
        (select count(*) from contrato_parcelas cp where cp.contratos_id = c.id and 
        (dt_pagto is null or  dt_pagto = '0000-00-00' ) and dt_vencimento < '".$dtm5."'   ) tt_inadp,
        (select max(dt_atualizacao_monetaria) from contrato_parcelas cp where cp.contratos_id = c.id) dt_atualizacao_monetaria,
        (select eh_vendedor from pessoas pcv where pcv.id = c.comprador_id) comprador_eh_vendedor

        from contratos c ";
        
        $join = "   left join pessoas pc on pc.id = c.comprador_id
        left join pessoas pv on pv.id = c.vendedor_id
        left join eventos  e on  e.id = c.eventos_id
        left join pessoas pl on pl.id = e.leiloeiro_id
        
        left JOIN ocorrencias oc ON
        oc.id = (
            SELECT oc1.id FROM ocorrencias AS oc1
            WHERE c.id = oc1.contratos_id 
            order by oc1.id desc
            limit 1
            )            
            ";

            if (!empty($filtros["filtro_status"]) && $filtros['filtro_status'] != 'excluido'){
                $filtro_excluido = " where c.status <> 'excluido'";
            } else {
                $filtro_excluido = " where 1 = 1 ";
            }
            
            $where  = " $filtro_excluido ";
            $where .= $this->retorna_where_lista_contratos( $filtros, $contratos);
            
            $orderby = " ORDER BY ";
            if($order != ""){
                $orderby.=$order;
            }
            $orderby .= "  c.id asc  ";
            
            if ($limit == "N"){
                $limite = "";
            }
            else{
                if ($limit == ""){
                    $limit = 30;
                }
                $limite = " LIMIT $inicial, $limit  ";
            }
            
            //  echo $select.$join.$where.$orderby.$limite;
            return $conexao_BD_1->query($select.$join.$where.$orderby.$limite);
        }
        
        function lista_totais_contratos($filtros, &$conexao_BD_1, $contratos=""){
            $where 	 = " where 1 = 1 ";
            
            $select = " select count(c.id) total_contratos, sum(c.vl_contrato) valor_total_contratos 	
            from contratos c 					
            ";
            
            $join = "   left join pessoas pc on pc.id = c.comprador_id
            left join pessoas pv on pv.id = c.vendedor_id
            left join eventos  e on  e.id = c.eventos_id
            left join pessoas pl on pl.id = e.leiloeiro_id
            ";
            
            $where .= $this->retorna_where_lista_contratos($filtros, $contratos);
            //echo $select.$where;
            // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Contratos total ' . json_encode($select.$join.$where));

            $conexao_BD_1->query($select.$join.$where);
            $reg = $conexao_BD_1->leRegistro();
            $retorno = ['qtd' => $reg['total_contratos'],'valor' => $reg['valor_total_contratos']];

            // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Contratos Totais ' . json_encode($retorno));

            return $retorno;
        }
        
        function retorna_where_lista_contratos($filtros, $contratos=""){
            $where = " ";
            
            if($contratos!=''){
                if($contratos->id !=""){ $where .= " AND c.id = ".$contratos->id ; }
                if($contratos->comprador_id !=""){ $where .= " AND c.comprador_id = ".$contratos->comprador_id ; }
                if($contratos->vendedor_id !=""){ $where .= " AND c.vendedor_id = ".$contratos->vendedor_id ; }
            }
            
            if($filtros!=""){
                
                
                if(!empty($filtros["filtro_id"])){
                    $where .= "  AND c.id =".$filtros["filtro_id"]." ";
                }
                
                if(!empty($filtros["filtro_contrato"])){
                    $where .= "  AND c.descricao like '%".$filtros["filtro_contrato"]."%'  ";
                }
                
                if(!empty($filtros["filtro_data"]) || !empty($filtros["filtro_data_fim"])){
                    if(!empty($filtros["filtro_data"]) && !empty($filtros["filtro_data_fim"])){
                        $where .= " AND  c.dt_contrato BETWEEN '".ConverteData($filtros["filtro_data"])."' and '".ConverteData($filtros["filtro_data_fim"])."' ";
                    }
                    elseif(!empty($filtros["filtro_data"])){
                        $where .= " AND c.dt_contrato = '".ConverteData($filtros["filtro_data"])."' ";
                    }
                    elseif(!empty($filtros["filtro_data_fim"])){
                        $where .= " AND c.dt_contrato = '".ConverteData($filtros["filtro_data_fim"])."' ";
                    }
                }
                
                
                if(!empty($filtros["filtro_status"])){
                    if($filtros["filtro_status"] == 'em_acordo_vigente'){
                        $where .= "  and c.contratos_id_pai is not null  
                        and c.status <> 'pendente' 
                        and c.id in
                        (  select contratos_id from contrato_parcelas 
                        where contratos_id = c.id and  dt_pagto = '0000-00-00'
                        )
                        ";
                    } else if($filtros["filtro_status"] == 'suspenso'){
                        $where .= "  AND c.suspenso = 'S'  ";
                    } else if($filtros["filtro_status"] == 'repasse'){
                        $where .= "  AND c.repasse = 'S'  ";                        
                    } else {
                        $where .= "  AND c.status like '%".$filtros["filtro_status"]."%'";
                    }
                }
                else {
                    $where .= " and c.status <> 'excluido'";
                }
                if(!empty($filtros["filtro_ativo"])){
                    if($filtros["filtro_ativo"] == 'pendentes')
                    $where .= "  AND c.status like '%pendente%'  ";
                    elseif($filtros["filtro_ativo"] == 'not_pendente')
                    $where .= "  AND c.status not like '%pendente%'  ";
                }
                
                if(!empty($filtros["filtro_pagto"])){
                    if($filtros["filtro_pagto"] == 'atraso'){
                        $where .= "  AND c.id in (  select cp.contratos_id from contrato_parcelas cp 
                        where cp.contratos_id = c.id and 
                        dt_vencimento < '".date('Y-m-d')."' and
                        (dt_pagto is null or 
                        dt_pagto = '0000-00-00' ) ) ";
                    }
                    elseif($filtros["filtro_pagto"] == 'aberto'){
                        $where .= "  AND c.id not in (  select cp.contratos_id from contrato_parcelas cp 
                        where cp.contratos_id = c.id and 
                        dt_vencimento < '".date('Y-m-d')."' and
                        (dt_pagto is null or 
                        dt_pagto = '0000-00-00' ) ) 
                        AND c.id  in (  select cp.contratos_id from contrato_parcelas cp 
                        where cp.contratos_id = c.id and 
                        dt_pagto is  null or dt_pagto = '0000-00-00'  )
                        
                        ";
                    }
                    elseif($filtros["filtro_pagto"] == 'liquidado'){
                        $where .= "  AND c.id not in (  select cp.contratos_id from contrato_parcelas cp 
                        where cp.contratos_id = c.id and 
                        (dt_pagto is null or 
                        dt_pagto = '0000-00-00' ) )  ";
                    }
                    elseif($filtros["filtro_pagto"] == 'negativada'){
                        $where .= "  AND c.id in (  select cp.contratos_id 
                        from contrato_parcelas cp 
                        where cp.contratos_id = c.id and fl_negativada='S' )  ";
                    }
                    
                }
                
                
                //			if(!empty($filtros["filtro_lote"])){
                    //				$busca = trata_busca_sql_score($filtros["filtro_lote"]);
                    //				if(isset($busca['multi'])){
                        //					$where .= "  AND MATCH (l.nome, l.num_registro)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";
                        //				}
                        
                        //				else{
                            //					$where .="  AND  (    remove_acentos(l.nome) LIKE '%".$busca['simples']."%'
                            //										  OR l.num_registro LIKE '%".$busca['simples']."%'
                            //										   )  ";
                            //				}
                            //
                            //			}
                            
                            
                            if(!empty($filtros["filtro_vendedor"])){
                                // Se filtro_pagina nulo seta nada
                                if(!isset($filtros["filtro_pagina"])) {$filtros["filtro_pagina"] = 'nada';}
                                if($filtros["filtro_vendedor"][0] == '*'){
                                    $filtro_vendedor = str_replace('*','',$filtros["filtro_vendedor"]);
                                    $busca_array = explode(' ',$filtro_vendedor);
                                    foreach($busca_array as $busca_item){
                                        if(!empty(trim($busca_item))){
                                            if (!is_null($_SESSION['perfil_id']) and $_SESSION['usuario'] != 'carlosmotta@mecob.com.br' and $filtros["filtro_pagina"] == 'contratos') {
                                                // $where .="  AND  (       remove_acentos(pv.nome) LIKE '%".$busca_item."%' 
                                                // OR remove_acentos(pv.apelido) LIKE '%".$busca_item."%' 
                                                // OR pv.email LIKE '%".$busca_item."%' 
                                                // OR pv.cpf_cnpj LIKE '%".$busca_item."%'
                                                // )  ";
                                                $where .="  AND  (       remove_acentos(pv.nome) LIKE '%".$busca_item."%' 
                                                OR pv.cpf_cnpj LIKE '%".$busca_item."%' )  ";
                                            } else {
                                                $where .="  AND  (       remove_acentos(pv.nome) LIKE '%".$busca_item."%' 
                                                OR remove_acentos(pv.apelido) LIKE '%".$busca_item."%' 
                                                OR pv.email LIKE '%".$busca_item."%' 
                                                OR pv.cpf_cnpj LIKE '%".$busca_item."%'
                                                OR remove_acentos(c.descricao) LIKE '%".$busca_item."%'
                                                )  ";
                                            }
                                        }
                                    }
                                }
                                else{
                                    
                                    $busca = trata_busca_sql_score(rtrim($filtros["filtro_vendedor"], ' '));
                                    if(isset($busca['multi'])){

                                        if (!is_null($_SESSION['perfil_id']) and $_SESSION['usuario'] != 'carlosmotta@mecob.com.br' and $filtros["filtro_pagina"] == 'contratos') {
                                            $filtro_vendedor = rtrim(str_replace('*','',$busca['multi']));
                                            $busca_array = explode(' ',$filtro_vendedor);
                                            foreach($busca_array as $key => $busca_item){
                                                if($key == 0 ) { 
                                                    $where .=" AND ( remove_acentos(pv.nome) LIKE '%".$busca_item."%' ";
                                                    $where .=" OR pv.cpf_cnpj LIKE '%".$busca_item."%' ";
                                                } else {
                                                    $where .=" OR remove_acentos(pv.nome) LIKE '%".$busca_item."%' ";
                                                    $where .=" OR pv.cpf_cnpj LIKE '%".$busca_item."%' ";
                                                }
                                            }
                                            $where .= " ) ";                                                
                                        } else {
                                            $where .= "  AND MATCH (pv.nome,pv.apelido,pv.email,pv.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";
                                        }

                                    }
                                    else{
                                        if (!is_null($_SESSION['perfil_id']) and $_SESSION['usuario'] != 'carlosmotta@mecob.com.br' and $filtros["filtro_pagina"] == 'contratos') {
                                            // $where .="  AND  (       remove_acentos(pv.nome) LIKE '%".$busca['simples']."%' 
                                            // OR remove_acentos(pv.apelido) LIKE '%".$busca['simples']."%' 
                                            // OR pv.email LIKE '%".$busca['simples']."%' 
                                            // OR pv.cpf_cnpj LIKE '%".$busca['simples']."%'
                                            // )  ";
                                            $where .="  AND  (       remove_acentos(pv.nome) LIKE '%".$busca['simples']."%' 
                                            OR pv.cpf_cnpj LIKE '%".$busca['simples']."%' )  ";
                                        } else {
                                            $where .="  AND  (       remove_acentos(pv.nome) LIKE '%".$busca['simples']."%' 
                                            OR remove_acentos(pv.apelido) LIKE '%".$busca['simples']."%' 
                                            OR pv.email LIKE '%".$busca['simples']."%' 
                                            OR pv.cpf_cnpj LIKE '%".$busca['simples']."%'
                                            OR remove_acentos(c.descricao) LIKE '%".$busca['simples']."%'
                                            )  ";
                                        }
                                    }
                                }
                                
                            }
                            
                            if(!empty($filtros["filtro_comprador"])){
                                // Se filtro_pagina nulo seta nada
                                if(!isset($filtros["filtro_pagina"])) {$filtros["filtro_pagina"] = 'nada';}
                                if($filtros["filtro_comprador"][0] == '*'){
                                    $filtro_comprador = str_replace('*','',$filtros["filtro_comprador"]);
                                    $busca_array = explode(' ',$filtro_comprador);
                                    foreach($busca_array as $busca_item){
                                        if(!empty(trim($busca_item)))
                                        if (!is_null($_SESSION['perfil_id']) and $_SESSION['usuario'] != 'carlosmotta@mecob.com.br' and $filtros["filtro_pagina"] == 'contratos') {
                                            // $where .="  AND  (       remove_acentos(pc.nome) LIKE '%".$busca_item."%' 
                                            // OR remove_acentos(pc.apelido) LIKE '%".$busca_item."%' 
                                            // OR pc.email LIKE '%".$busca_item."%' 
                                            // OR pc.cpf_cnpj LIKE '%".$busca_item."%'
                                            // )  ";
                                            $where .="  AND  (       remove_acentos(pc.nome) LIKE '%".$busca_item."%' 
                                            OR pc.cpf_cnpj LIKE '%".$busca_item."%' )  ";
                                        } else {
                                            $where .="  AND  (       remove_acentos(pc.nome) LIKE '%".$busca_item."%' 
                                            OR remove_acentos(pc.apelido) LIKE '%".$busca_item."%' 
                                            OR pc.email LIKE '%".$busca_item."%' 
                                            OR pc.cpf_cnpj LIKE '%".$busca_item."%'
                                            OR remove_acentos(c.descricao) LIKE '%".$busca_item."%'
                                            )  ";
                                        }
                                    }
                                }
                                else{
                                    
                                    $busca = trata_busca_sql_score($filtros["filtro_comprador"]);
                                    if(isset($busca['multi'])){

                                        if (!is_null($_SESSION['perfil_id']) and $_SESSION['usuario'] != 'carlosmotta@mecob.com.br' and $filtros["filtro_pagina"] == 'contratos') {
                                            $filtro_comprador = rtrim(str_replace('*','',$busca['multi']));
                                            $busca_array = explode(' ',$filtro_comprador);
                                            foreach($busca_array as $key => $busca_item){
                                                if($key == 0 ) { 
                                                    $where .=" AND ( remove_acentos(pc.nome) LIKE '%".$busca_item."%' ";
                                                    $where .=" OR pc.cpf_cnpj LIKE '%".$busca_item."%' ";
                                                } else {
                                                    $where .=" OR  remove_acentos(pc.nome) LIKE '%".$busca_item."%' ";
                                                    $where .=" OR  pc.cpf_cnpj LIKE '%".$busca_item."%' ";
                                                }
                                            }
                                            $where .= " ) ";
                                        } else {
                                            $where .= "  AND MATCH (pc.nome,pc.apelido,pc.email,pc.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";                                            
                                        }

                                    }
                                    else{
                                        if (!is_null($_SESSION['perfil_id']) and $_SESSION['usuario'] != 'carlosmotta@mecob.com.br' and $filtros["filtro_pagina"] == 'contratos') {
                                            // $where .="  AND  (       remove_acentos(pc.nome) LIKE '%".$busca['simples']."%' 
                                            // OR remove_acentos(pc.apelido) LIKE '%".$busca['simples']."%' 
                                            // OR pc.email LIKE '%".$busca['simples']."%' 
                                            // OR pc.cpf_cnpj LIKE '%".$busca['simples']."%'
                                            // )  ";
                                            $where .="  AND  (       remove_acentos(pc.nome) LIKE '%".$busca['simples']."%' 
                                            OR pc.cpf_cnpj LIKE '%".$busca['simples']."%' )  ";
                                        } else {
                                            $where .="  AND  (       remove_acentos(pc.nome) LIKE '%".$busca['simples']."%' 
                                            OR remove_acentos(pc.apelido) LIKE '%".$busca['simples']."%' 
                                            OR pc.email LIKE '%".$busca['simples']."%' 
                                            OR pc.cpf_cnpj LIKE '%".$busca['simples']."%'
                                            OR remove_acentos(c.descricao) LIKE '%".$busca['simples']."%'
                                            )  ";
                                        }
                                    }
                                }
                                
                            }
                        }
                        
                        if(!empty($filtros["filtro_evento"])){
                            $busca_array = explode(' ',$filtros["filtro_evento"]);
                            foreach($busca_array as $busca_item){
                                if(!empty(trim($busca_item)))
                                $where .="  AND  (remove_acentos(e.nome) LIKE '%".$busca_item."%'  )  ";
                            }
                        }
                        
                        if(!empty($filtros["filtro_zerado"])){
                            $where .= "  AND c.motivo_zerado like '".$filtros["filtro_zerado"]."' ";
                        }
                        
                        if(!empty($filtros["filtro_dia"])){
                            $where .= "  AND c.id in (select distinct(contratos_id) from contrato_parcelas where day(dt_vencimento) = " . $filtros["filtro_dia"] . ") ";
                        }

                        if (is_null($_SESSION['perfil_id'])) {
                            $where .= " AND (c.motivo_zerado <> 'Cancelamento' OR c.motivo_zerado is null) ";
                        }

                        return $where;
                        
                    }
                    
                    function remover_boletos_contrato($contrato_id, &$conexao_BD_1){
                        
                        $delete = "UPDATE contrato_parcelas SET arquivos_id_remessa = NULL, nu_linha_remessa = NULL  WHERE contratos_id = ".$contrato_id;
                        $conexao_BD_1->query_atualizacao($delete);
                        
                        $delete = "UPDATE contratos SET gerar_boleto = 'N' WHERE id =  ".$contrato_id;
                        $conexao_BD_1->query_atualizacao($delete);
                        
                        include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.class.php");
                        $arquivos    = new arquivos();
                        $arquivos->contratos_id = $contrato_id;
                        $conexao_BD_1->select($arquivos);
                        if ($conexao_BD_1->numeroDeRegistros() > 0){
                            $reg = $conexao_BD_1->leRegistro();
                            $nm_arquivo = $reg["nm_arq"];
                            unlink(getenv('CAMINHO_RAIZ') . '/boletos/remessa/' . $nm_arquivo);
                        }
                        
                        $delete = "DELETE FROM arquivos WHERE contratos_id  =  ".$contrato_id;
                        return  $conexao_BD_1->query_atualizacao($delete);
                    }
                    
                    
                    function remover_contratos(&$conexao_BD_1, $contratos_id){
                        
                        //verifica se já existe parcelas liquidadas em teds
                        $select = " select count(*) total from contrato_parcelas where contratos_id = ".$contratos_id."  and teds_id is not null ";
                        $conexao_BD_1->query($select);
                        if ($conexao_BD_1->numeroDeRegistros()){
                            $reg = $conexao_BD_1->leRegistro();
                            if(!empty($reg["total"]) && is_numeric($reg["total"])  && $reg["total"]>0){
                                return 3;
                            }
                        }
                        
                        //remover documentos
                        $delete = "delete from documentos  where contratos_id =  ".$contratos_id;
                        $conexao_BD_1->query_atualizacao($delete);
                        
                        //remover ocorrencias
                        $delete = "delete from ocorrencias  where contratos_id =  ".$contratos_id;
                        $conexao_BD_1->query_atualizacao($delete);
                        
                        //remover as parcelas
                        $this->delete_parcelas($conexao_BD_1, $contratos_id);
                        
                        try {
                            $delete = "delete from arquivos  where contratos_id =  ".$contratos_id;
                            $conexao_BD_1->query_atualizacao($delete);
                        } catch (\Throwable $th) {
                        }
                        
                        //antes de deletar verifica se possui um filho para remover também
                        $select = " select contratos_id_pai from  contratos where id =  ".$contratos_id;
                        $conexao_BD_1->query($select);
                        $id_contrato_filho =0;
                        if ($conexao_BD_1->numeroDeRegistros()){
                            $reg = $conexao_BD_1->leRegistro();
                            if(!empty($reg["contratos_id_pai"]) && is_numeric($reg["contratos_id_pai"])){
                                $id_contrato_filho = $reg["contratos_id_pai"];
                            }
                        }

                        $delete = "delete from contrato_lote  where contratos_id =  ".$contratos_id;
                        $conexao_BD_1->query_atualizacao($delete);
                        
                        $delete = "update contratos set status = 'excluido' where id =  ".$contratos_id;
                        $conexao_BD_1->query_atualizacao($delete);

                        
                        if(!empty($id_contrato_filho) && $id_contrato_filho != 0){
                            
                            $retorno = $this->remover_contratos($conexao_BD_1, $id_contrato_filho);
                            return $retorno ;
                        }
                        else{
                            
                            //verifica se pode excluir
                            $select = " select count(*) total from  contratos where status <> 'excluido' and id =  ".$contratos_id;
                            $conexao_BD_1->query($select);
                            if ($conexao_BD_1->numeroDeRegistros()){
                                $reg = $conexao_BD_1->leRegistro();
                                $total = $reg["total"];
                                if(is_numeric($total) && $total>0){
                                    return 2;
                                }
                                else{
                                    return 1;
                                }
                            }
                            else{
                                return 1;
                            }
                        }
                        
                    }
                    
                        function lista_documentos($contrato_id, &$conexao_BD_1){
                            $select = "select * from documentos
                            where contratos_id = ".$contrato_id;
                            return $conexao_BD_1->query($select);
                            
                        }
                        
                        function inserir_documento($contrato_id,  $descricao,$arquivo,  &$conexao_BD_1){
                            
                            
                            $insert = " INSERT into documentos (
                                contratos_id,
                                file,
                                descricao
                                )
                                VALUES
                                (	".$contrato_id.",
                                '".$arquivo."',
                                '".$descricao."'
                                ) 
                                ";
                                
                                
                                $conexao_BD_1->query_inserir($insert);
                            }
                            
                            function remover_documento($documento_id, &$conexao_BD_1){
                                $delete = "delete from documentos  where id =  ".$documento_id;
                                $retorno = $conexao_BD_1->query_atualizacao($delete);
                                return $retorno;
                                
                            }
                            
                            function  gera_parcelas(&$conexao_BD_1, $contrato){
                                
                                
                                if ($contrato->tp_contrato == "adimplencia"){
                                    $this->gera_parcelas_adimplente($conexao_BD_1, $contrato);
                                }
                                elseif ($contrato->tp_contrato == "inadimplencia"){
                                    $this->gera_parcelas_inadimplente($conexao_BD_1, $contrato);
                                }
                                
                            }
                            
                            function gera_parcelas_adimplente(&$conexao_BD_1, $contrato){
                                
                                $data_aux = new DateTime($contrato->dt_contrato);
                                if (($contrato->dt_primeira_parcela != "")&&($contrato->dt_primeira_parcela != '0000-00-00')){
                                    $data_aux = new DateTime($contrato->dt_primeira_parcela);
                                }
                                $quantidade_parcelas = $contrato->nu_parcelas;
                                
                                if ($contrato->contratos_id_pai > 0){
                                    //calcula o valor da parcela
                                    $valor = str_replace(",", ".", $contrato->vl_contrato);
                                    $valor_divisao = $valor / $quantidade_parcelas;
                                    $v1 = explode(".", $valor_divisao);
                                    
                                    if (!isset($v1[0])) {
                                        $v1[0] = $valor_divisao;
                                        $v1[1] = 00;
                                    }
                                    if (!isset($v1[1])) {
                                        $v1[1] = 00;
                                    }
                                    $valor_parcela = $v1[0] . "." . substr($v1[1], 0, 2);
                                    $valor_primeira_parcela = $valor - ($valor_parcela * ($quantidade_parcelas - 1));
                                }
                                else{
                                    $valor_primeira_parcela = $contrato->vl_entrada;
                                    $valor_parcela			= $contrato->vl_entrada;
                                }
                                
                                $nu_dias_pagto		 = 30;
                                $dia_prestacao = $data_aux->format( 'd' );
                                $mes_prestacao = $data_aux->format( 'n' );
                                $ano_prestacao = $data_aux->format( "Y" );
                                
                                for ($i = 1 ; $i <= $quantidade_parcelas; $i++){
                                    
                                    $dt_vencimento = $data_aux->format('Y-m-d');
                                    $nu_parcela = $i;
                                    
                                    if ($i == 1) {
                                        $vl_parcela = $valor_primeira_parcela;
                                    } else {
                                        $vl_parcela = $valor_parcela;
                                    }
                                    
                                    $dt_pagto = 'null';
                                    $liquidada_no_cadastro = "N";
                                    if ($nu_parcela < $contrato->parcela_primeiro_pagto){
                                        $dt_pagto = "'$dt_vencimento'";
                                        $liquidada_no_cadastro = "S";
                                    }
                                    
                                    $vl_corrigido = $vl_parcela;
                                    
                                    $insert_pagto = "insert into contrato_parcelas ( contratos_id, nu_parcela, dt_vencimento, dt_pagto, vl_parcela, liquidada_no_cadastro, vl_corrigido   )
                                    values  ( ".$contrato->id." ,".$nu_parcela.", '".$dt_vencimento."', ".$dt_pagto.", ".$vl_parcela.", '".$liquidada_no_cadastro."', ".$vl_corrigido."  )";
                                    $conexao_BD_1->query_inserir($insert_pagto);
                                    
                                    $mes_prestacao++;
                                    
                                    //$data_aux->add(new DateInterval("P".$nu_dias_pagto."D"));
                                    if ( $mes_prestacao > 12 ) {
                                        $mes_prestacao = 1;
                                        $ano_prestacao++;
                                    }
                                    
                                    if ( checkdate( $mes_prestacao, $dia_prestacao, $ano_prestacao ) ) {
                                        $data_aux = new DateTime( $ano_prestacao . "/" . $mes_prestacao . "/" . $dia_prestacao );
                                    } else {
                                        $data_aux = new DateTime( $ano_prestacao . "/" . $mes_prestacao . "/01" );
                                        $qt_dias_mes = $data_aux->format( 't' );
                                        $data_aux = new DateTime( $ano_prestacao . "/" . $mes_prestacao . "/" . $qt_dias_mes );
                                    }
                                    
                                }
                            }
                            
                            function gera_parcelas_inadimplente(&$conexao_BD_1, $contrato ){
                                
                                $data_aux 			 = new DateTime($contrato->dt_contrato);
                                $quantidade_parcelas = $contrato->nu_parcelas;
                                $vl_parcela			 = $contrato->vl_entrada;
                                $nu_dias_pagto		 = 30;
                                $dt_atual			 = date('Y-m-d');
                                
                                $dia_prestacao = $data_aux->format( 'd' );
                                $mes_prestacao = $data_aux->format( 'n' );
                                $ano_prestacao = $data_aux->format( "Y" );
                                
                                for ($i = 1 ; $i <= $quantidade_parcelas; $i++){
                                    
                                    $dt_vencimento = $data_aux->format('Y-m-d');
                                    $nu_parcela = $i;
                                    
                                    $dt_pagto = 'null';
                                    $liquidada_no_cadastro = "N";
                                    if ($nu_parcela < $contrato->parcela_primeiro_pagto){
                                        $dt_pagto = "'$dt_vencimento'";
                                        $liquidada_no_cadastro = "S";
                                    }
                                    
                                    $vl_juros 			   = 0.00;
                                    $vl_correcao_monetaria = 0.00;
                                    $vl_honorarios		   = 0.00;
                                    
                                    $parametro_juros = $contrato->juros;
                                    
                                    $vl_corrigido = $vl_parcela;
                                    if (($dt_vencimento < $dt_atual)&&($nu_parcela >= $contrato->parcela_primeiro_pagto)){
                                        
                                        $vl_correcao_monetaria = $this->calcula_correcao_monetaria($vl_parcela, $dt_vencimento, $dt_atual, $conexao_BD_1);
                                        
                                        $vl_calculo_juros = $vl_parcela+$vl_correcao_monetaria;
                                        $vl_juros = $this->calcula_juros($vl_calculo_juros, $parametro_juros, $dt_vencimento, $dt_atual);
                                        
                                        $vl_calculo_honorarios = $vl_calculo_juros+$vl_juros;
                                        $vl_honorarios = $this->calcula_honorarios($vl_calculo_honorarios, $contrato->honor_inadimp);
                                        
                                        $vl_corrigido = $vl_calculo_honorarios + $vl_honorarios;
                                    }
                                    
                                    
                                    $insert_pagto = "insert into contrato_parcelas 
									( contratos_id, nu_parcela, dt_vencimento, dt_pagto, vl_parcela, vl_juros, vl_correcao_monetaria, vl_honorarios, vl_corrigido, liquidada_no_cadastro )
                                    values  ( ".$contrato->id." ,".$nu_parcela.", '".$dt_vencimento."', ".$dt_pagto.", ".$vl_parcela.", ".$vl_juros.", ".$vl_correcao_monetaria.", ".$vl_honorarios.", ".$vl_corrigido.", '".$liquidada_no_cadastro."'  )";
                                    $conexao_BD_1->query_inserir($insert_pagto);
                                    
                                    $mes_prestacao++;
                                    
                                    //$data_aux->add(new DateInterval("P".$nu_dias_pagto."D"));
                                    if ( $mes_prestacao > 12 ) {
                                        $mes_prestacao = 1;
                                        $ano_prestacao++;
                                    }
                                    
                                    if ( checkdate( $mes_prestacao, $dia_prestacao, $ano_prestacao ) ) {
                                        $data_aux = new DateTime( $ano_prestacao . "/" . $mes_prestacao . "/" . $dia_prestacao );
                                    } else {
                                        $data_aux = new DateTime( $ano_prestacao . "/" . $mes_prestacao . "/01" );
                                        $qt_dias_mes = $data_aux->format( 't' );
                                        $data_aux = new DateTime( $ano_prestacao . "/" . $mes_prestacao . "/" . $qt_dias_mes );
                                    }
                                    
                                }
                            }
                            
                            function calcula_juros($vl_parcela, $parametro_juros, $dt_vencimento, $dt_atual){
                                
                                //dias proporcionais do primeiro mes
                                $dt_inicio = new DateTime( $dt_vencimento );
                                $dia 	 = $dt_inicio->format("d");
                                $qt_dias = $dt_inicio->format("t");
                                $periodo1 = ($qt_dias - $dia + 1)/$qt_dias;
                                
                                //dias proporcionais do segundo mes
                                
                                $dt_final = new DateTime( $dt_atual );
                                $dia 	 = $dt_final->format("d");
                                $qt_dias = $dt_final->format("t");
                                $periodo2 = ($dia - 1)/$qt_dias;
                                
                                $dt_inicio = new DateTime($dt_inicio->format("Y-m")."-01");
                                $dt_inicio->add(new DateInterval("P1M")); //pula 1 mes pq o primeiro entra como proporcional no periodo 1
                                
                                $dt_final = new DateTime($dt_final->format("Y-m")."-01");
                                
                                $periodo_meio = 0;
                                while ($dt_inicio->format("Ymd") < $dt_final->format("Ymd")){
                                    $periodo_meio++;
                                    $dt_inicio->add(new DateInterval("P1M"));
                                }
                                $periodo = truncate($periodo1+$periodo_meio+$periodo2,4);
                                
                                $vl_juros = ($parametro_juros/100) * $periodo;
                                
                                return truncate($vl_parcela * $vl_juros,2);
                                
                            }
                            
                            function calcula_honorarios($vl_calculo_honorarios, $parametro_honorarios){
                                return truncate((($vl_calculo_honorarios) * $parametro_honorarios) / 100,2);
                            }
                            
                            function calcula_correcao_monetaria($vl_parcela, $dt_parcela, $dt_correcao, &$conexao_BD_1){
                                
                                $indice_vencimento = $this->recupera_indice($dt_parcela, $conexao_BD_1);
                                $indice_correcao   = $this->recupera_indice($dt_correcao, $conexao_BD_1);
                                
                                return round((($vl_parcela / $indice_vencimento) * $indice_correcao ) - $vl_parcela,2);
                            }
                            
                            function recupera_indice($dt_indice, &$conexao_BD_1){
                                
                                $select = " select * from indice_cgj where dt_indice = '".$dt_indice."'";
                                $conexao_BD_1->query($select);
                                if ($conexao_BD_1->numeroDeRegistros()){
                                    $reg = $conexao_BD_1->leRegistro();
                                    $retorno = $reg["vl_indice"];
                                }
                                else{
                                    $select = " select max(dt_indice) dt_indice from indice_cgj ";
                                    $conexao_BD_1->query($select);
                                    if ($conexao_BD_1->numeroDeRegistros()){
                                        $reg = $conexao_BD_1->leRegistro();
                                        $retorno = $this->recupera_indice($reg["dt_indice"], $conexao_BD_1);
                                    }
                                }
                                return $retorno;
                            }
                            
                            function lista_parcelas_contratos($id_contrato, &$conexao_BD_1, $id_parcela=""){
                                
                                $data_atual = date("Y-m-d");
                                $data_prox = new DateTime($data_atual);
                                $data_prox->sub(new DateInterval("P5D"));
                                $dtm5 = $data_prox->format('Y-m-d');
                                
                                $where_parcela = " ";
                                if( $id_parcela != "" && is_numeric( $id_parcela) && $id_parcela>0){
                                    $where_parcela = " and cp.id = ".$id_parcela;
                                }
                                
                                $select = " select cp.*, c.gerar_boleto, p.nome, 
                                case when dt_vencimento < '".$dtm5."' and (dt_pagto is null || dt_pagto = '0000-00-00')  then 1 else 0 end as vencida_5_dias
                                
                                from contrato_parcelas cp
                                join contratos c on c.id = cp.contratos_id 
                                left join pessoas p on p.id = cp.pessoas_id_atualizacao   
                                where contratos_id = ".$id_contrato."  
                                ".$where_parcela." 
                                order by cp.nu_parcela";
                                //echo $select;
                                return $conexao_BD_1->query($select);
                                
                            }
                            
                            function parcelas_liquidadas_no_cadastro($conexao_BD_1 , $contrato_id = ""){
                                $update = "  update contrato_parcelas set vl_corrigido = vl_parcela 
                                where  liquidada_no_cadastro = 'S' and vl_corrigido <> vl_parcela  
                                
                                ";
                                if(is_numeric($contrato_id)){
                                    $update .= "  and contratos_id =  ".$contrato_id;
                                    
                                }
                                
                                //echo $select;
                                return $conexao_BD_1->query_atualizacao($update);
                            }
                            
                            
                            function atualiza_parcelas($parcelas, $conexao_BD_1, $pessoas_id_atualizacao = null){
                                $parcelas_update_id = array();
                                
                                //			echo "<pre>";
                                //			print_r($parcelas);
                                //			echo "</pre>";
                                foreach($parcelas as $parcela){
                                    
                                    if(empty($parcela['value'])){continue;}
                                    $name = $parcela['name'];
                                    $campo = substr($name,-5);
                                    $id = substr($name,7,-5);
                                    //echo "<br> parcela id $id  - $campo: ".$parcela['value'];
                                    
                                    if($campo=='check'){
                                        $parcelas_update_id[] = $id;
                                        continue;
                                    }
                                    elseif(!in_array($id,$parcelas_update_id)){
                                        if($campo=='valor'){
                                            $update_parcelas = " update contrato_parcelas set  
											vl_parcela = ".$parcela['value']."
                                            where id =  ".$id;
                                            $conexao_BD_1->query_atualizacao($update_parcelas);
                                        }
                                        continue;
                                    }
                                    
                                    $update_parcelas = " update contrato_parcelas set  ";
                                    $pula_update=0;
                                    switch($campo){
                                        case 'valor':
                                        $update_parcelas .= " vl_parcela = ".$parcela['value'];
                                        $vl_pago = $vl_parcela = $parcela['value'];
                                        $update_dtpagto = $update_vlpagto =  0;
                                        break;
                                        case 'corre':
                                        $update_parcelas .= " vl_correcao_monetaria = ".$parcela['value'];
                                        break;
                                        case 'juros':
                                        $update_parcelas .= " vl_juros = ".$parcela['value'];
                                        break;
                                        case 'honor':
                                        $update_parcelas .= " vl_honorarios = ".$parcela['value'];
                                        break;
                                        case 'corgd':
                                        $vl_corrigido = $parcela['value'];
                                        if ($vl_corrigido != $vl_parcela){
                                            $vl_pago = $vl_corrigido = $vl_parcela;
                                        }
                                        $update_parcelas .= " vl_corrigido = ".$vl_corrigido;
                                        break;
                                        case 'venci':
                                        $update_parcelas .= " dt_vencimento = '".ConverteData($parcela['value'])."'";
                                        break;
                                        case 'pagto':
                                        $update_dtpagto = 1;
                                        $update_parcelas .= " dt_pagto = '".ConverteData($parcela['value'])."'";
                                        $update_parcelas .= " , dt_credito = '".ConverteData($parcela['value'])."'";
                                        $update_parcelas .= " , pessoas_id_atualizacao = ".$pessoas_id_atualizacao."";
                                        $update_parcelas .= " , dt_processo_pagto='".date('Y-m-d G:i:s')."'";
                                        break;
                                        case 'vpago':
                                        if($update_dtpagto || $parcela['value']>0){
                                            if($parcela['value'] > $vl_pago){
                                                $vl_pago =$parcela['value'];
                                            }
                                            $update_vlpagto = 1;
                                            $update_parcelas .= " vl_pagto = ".$vl_pago."";
                                        }
                                        else{
                                            $pula_update=1;
                                        }
                                        break;
                                        case 'trted':
                                        $update_parcelas .= " tratar_ted = 1 ";
                                        break;
                                        case 'negat':
                                        $update_parcelas .= " fl_negativada = 'S' ";
                                        break;
                                    }
                                    
                                    if($pula_update){
                                        $pula_update=0;
                                        continue;
                                    }
                                    
                                    if($update_dtpagto && !$update_vlpagto){
                                        $update_parcelas .= " , vl_pagto = ".$vl_pago."";
                                    }
                                    elseif(!$update_dtpagto && $update_vlpagto){
                                        $update_parcelas .= " , dt_pagto = '".date('Y-m-d')."'";
                                        $update_parcelas .= " , dt_credito = '".date('Y-m-d')."'";
                                        
                                    }
                                    
                                    $update_parcelas .= " where id =  ".$id;
                                    //echo "<br /> ".$update_parcelas;
                                    if(!$conexao_BD_1->query_atualizacao($update_parcelas)){
                                        return 0;
                                    }
                                    
                                }
                                return 1;
                            }
                            
                            
                            function delete_parcelas($conexao_BD_1, $id_contrato){
                                
                                $delete = " delete from contrato_parcelas where contratos_id = ".$id_contrato." ";
                                return $conexao_BD_1->query($delete);
                                
                            }
                            
                            function atualiza_dt_pagto_parcelas_acordo($conexao_BD_1, $id_contrato, $dt_pagto){
                                
                                $update = " update contrato_parcelas 
                                set dt_pagto = '".$dt_pagto."'
                                where contratos_id = ".$id_contrato." and simulada = 'S' ";
                                return $conexao_BD_1->query($update);
                                
                            }

                            function desfazer_acordo($conexao_BD_1, $id_contrato, $id_contrato_pai){
                                try {
                                    $update = " update contrato_parcelas 
                                    set dt_pagto = NULL
                                    where contratos_id = ".$id_contrato_pai." and simulada = 'S' ";
                                    $conexao_BD_1->query($update);
                                    
                                    $update = " update contratos 
                                    set status = 'excluido'
                                    where id = ".$id_contrato;
                                    $conexao_BD_1->query($update);

                                    $update = " update contratos 
                                    set `status` = `status_antes_acordo`
                                    where id = ".$id_contrato_pai;
                                    $conexao_BD_1->query($update);

                                    $update = " update ocorrencias 
                                    set contratos_id_original = $id_contrato, contratos_id = $id_contrato_pai
                                    where contratos_id = ".$id_contrato;
                                    $conexao_BD_1->query($update);

                                    return 'Ok';
                                } catch (\Throwable $th) {
                                    return 'Erro';
                                }
                            }
                            
                            
                            function atualiza_parcelas_simulacao(&$conexao_BD_1, $contrato, $parcelas_atualizar ){
                                
                                $parcelas_update_id = array();
                                $dt_atual			= date('Y-m-d');
                                $atualiza_parcela	= false;
                                foreach($parcelas_atualizar as $parcela){
                                    
                                    if(empty($parcela['value'])){
                                        continue;
                                    }
                                    $name = $parcela['name'];
                                    $campo = substr($name,-5);
                                    $id = substr($name,7,-5);
                                    //echo "<br> parcela id $id  - $campo: ".$parcela['value'];
                                    
                                    if($campo=='check'){
                                        $parcelas_update_id[] = $id;
                                        continue;
                                    }
                                    elseif(!in_array($id,$parcelas_update_id)){
                                        
                                        if($campo=='valor'){
                                            $update_parcelas = " update contrato_parcelas set  
                                            vl_parcela = ".$parcela['value']."
                                            where id =  ".$id;
                                            $conexao_BD_1->query_atualizacao($update_parcelas);
                                        }
                                        continue;
                                    }
                                    
                                    switch($campo){
                                        case 'valor':
                                        $vl_parcela = $parcela['value'];
                                        $atualiza_parcela = false;
                                        break;
                                        case 'corre':
                                        case 'juros':
                                        case 'honor':
                                        case 'corgd':
                                        case 'pagto':
                                        $atualiza_parcela = false;
                                        break;
                                        case 'venci':
                                        $dt_vencimento = ConverteData($parcela['value']);
                                        $atualiza_parcela = true;
                                        break;
                                    }
                                    
                                    if ($atualiza_parcela){
                                        $vl_juros 			   = 0.00;
                                        $vl_correcao_monetaria = 0.00;
                                        $vl_honorarios		   = 0.00;
                                        
                                        $parametro_juros = $contrato->juros;
                                        
                                        $vl_corrigido = $vl_parcela;
                                        
                                        if ($dt_vencimento < $dt_atual) {
                                            $vl_correcao_monetaria = $this->calcula_correcao_monetaria($vl_parcela, $dt_vencimento, $dt_atual, $conexao_BD_1);
                                            
                                            $vl_calculo_juros = $vl_parcela+$vl_correcao_monetaria;
                                            $vl_juros = $this->calcula_juros($vl_calculo_juros, $parametro_juros, $dt_vencimento, $dt_atual);
                                            
                                            $vl_calculo_honorarios = $vl_calculo_juros+$vl_juros;
                                            $vl_honorarios = $this->calcula_honorarios($vl_calculo_honorarios, $contrato->honor_inadimp);
                                            
                                            $vl_corrigido = $vl_calculo_honorarios + $vl_honorarios;
                                        }
                                        else{
                                            $vl_honorarios = $this->calcula_honorarios($vl_parcela, $contrato->honor_inadimp);
                                            $vl_corrigido = $vl_parcela + $vl_honorarios;
                                        }
                                        
                                        $update_parcelas = " update contrato_parcelas set  
										vl_parcela = ".$vl_parcela.
                                        ", vl_correcao_monetaria = ".$vl_correcao_monetaria.
                                        ", vl_juros = ".$vl_juros.
                                        ", vl_honorarios = ".$vl_honorarios.
                                        ", vl_corrigido = ".$vl_corrigido.
                                        ", simulada = 'S',
                                        dt_atualizacao_monetaria = DATE(NOW())
                                        where id =".$id;
                                        $conexao_BD_1->query_atualizacao($update_parcelas);
                                     //   echo $update_parcelas;
                                       // exit;
                                    }
                                }
                                
                                //corrige parcelas que não entraram na simulação
                                $update_parcelas = " update contrato_parcelas set  
                                vl_correcao_monetaria = 0,
                                vl_juros = 0,
                                vl_honorarios = 0,
                                vl_corrigido = vl_parcela,
                                simulada = 'N',
                                dt_atualizacao_monetaria = NULL
                                where dt_vencimento >= '".$dt_atual."' and id not in (".implode(',', $parcelas_update_id )." )";
                                $conexao_BD_1->query_atualizacao($update_parcelas);
                                
                            }
                            
                            function parcelas_tem_em_aberto(&$conexao_BD_1, $contratos){
                                
                                $select = " SELECT * 
                                FROM contrato_parcelas
                                WHERE (liquidada_no_cadastro <> 'S' and simulada = 'N') and contratos_id = ".$contratos->id."  ";
                                $conexao_BD_1->query($select);
                                if ($conexao_BD_1->numeroDeRegistros()){
                                    return true;
                                }
                                return false;
                                
                                
                            }
                            
                            function atualiza_instrucao($contrato_id, $instrucao , &$conexao_BD_1){
                                if($instrucao == 'null'){
                                    $update = " update contratos  set  instrucao = null where  id = ".$contrato_id;
                                    return $conexao_BD_1->query_atualizacao($update);
                                }
                                else{
                                    $update = " update contratos  set  instrucao = '".$instrucao."' where  id = ".$contrato_id;
                                    return $conexao_BD_1->query_atualizacao($update);
                                }
                                
                            }
                            
                            function calcula_juros_parcela(&$conexao_BD_1, $parcela_id, $dt_atualizacao = ""){
                                
                                $select = " select * from contrato_parcelas where id = ".$parcela_id." ";
                                $conexao_BD_1->query($select);
                                $regParcela = $conexao_BD_1->leRegistro();
                                $vl_parcela = $regParcela["vl_parcela"];
                                $dt_vencimento_original = $regParcela["dt_vencimento_original"];
                                $dt_vencimento = $regParcela["dt_vencimento"];
                                
                                
                                $up_dt_original = " ,dt_vencimento_original = '".$dt_vencimento."'" ;
                                if (($dt_vencimento_original != "")&&($dt_vencimento_original != '0000-00-00')){
                                    $dt_vencimento = $dt_vencimento_original;
                                    $up_dt_original = "";
                                }
                                
                                $dt_inicio = new DateTime( $dt_vencimento );
                                
                                if ($dt_atualizacao == "" ){
                                    $dt_final = new DateTime( date("Y-m-d") );
                                }
                                else{
                                    $dt_final = new DateTime( $dt_atualizacao );
                                }
                                
                                $intervalo = $dt_inicio->diff($dt_final);
                                
                                $vl_multa = round($vl_parcela * 0.02,2);
                                $valor_ao_dia = round(0.54 * $intervalo->days,2);
                                
                                $vl_corrigido = $vl_parcela + $vl_multa + $valor_ao_dia;
                                
                                $update = " update contrato_parcelas 
                                set  vl_corrigido = ".$vl_corrigido.",
                                dt_vencimento = '".$dt_final->format("Y/m/d")."' 
                                $up_dt_original
                                where  id = ".$parcela_id;
                                return $conexao_BD_1->query_atualizacao($update);
                                
                                
                            }
                            
                            function exclui_parcela_e_cria_nova($conexao_BD_1, $parcela_id, $dados_parcela) {
                                try {
                                    $correcao       = $dados_parcela['correcao'];
                                    $juros          = $dados_parcela['juros'];
                                    $multa          = $dados_parcela['multa'];
                                    $taxas          = $dados_parcela['taxas'];
                                    $honorarios     = $dados_parcela['honorarios'];
                                    $valor_corrigido= $dados_parcela['valor_corrigido'];
                                    $vencimento     = $dados_parcela['vencimento'];

                                    $insert         = "INSERT INTO contrato_parcelas 
                                    (
                                        contratos_id,
                                        nu_parcela,
                                        vl_parcela,

                                        dt_vencimento,
                                        vl_correcao_monetaria,
                                        vl_juros,
                                        vl_honorarios,
                                        vl_taxa,
                                        vl_multa,
                                        vl_corrigido
                                    ) 
                                     
                                    
                                    (
                                        SELECT 
                                            contratos_id,
                                            nu_parcela,
                                            vl_parcela,
                                            '$vencimento',
                                            $correcao,
                                            $juros,
                                            $honorarios,
                                            $taxas,
                                            $multa,
                                            $valor_corrigido
                                        FROM contrato_parcelas WHERE id = $parcela_id
                                    )
                                    ";
                                    $nova_parcela_id = $conexao_BD_1->query_inserir($insert);

                                    $delete = "delete from contrato_parcelas where id = $parcela_id";
                                    $conexao_BD_1->query_atualizacao($delete);
                                    return $nova_parcela_id;
                                } catch (\Throwable $th) {
                                    return 0;
                                }
                            }
                            
                            function dados_boleto(&$conexao_BD_1, $contrato_id, $parcela_id = ''){

                                $where_parcela = '';
                                if ($parcela_id != '') {
                                    $where_parcela = " cp.id = $parcela_id and ";
                                }
                                
                                $select = "select cp.id as nosso_numero, date_format(cp.dt_vencimento, '%d%m%y') dt_vencimento, date_format(c.dt_contrato, '%d%m%y') dt_contrato,
                                cp.vl_corrigido, p.cpf_cnpj, p.nome, p.rua, p.numero, p.bairro, p.cidade, p.estado, p.cep, p.email
                                from contratos c
                                join contrato_parcelas cp on c.id = cp.contratos_id
                                join pessoas p on c.comprador_id = p.id
                                where c.id = ".$contrato_id." and $where_parcela
                                (dt_pagto is null or dt_pagto = '0000-00-00' ) and
                                arquivos_id_remessa is null ";
                    
                                return $conexao_BD_1->query($select);
                            }
                            
                            function atualiza_parcela_arquivo($conexao_BD_1, $parcela_id, $tp_arq, $arquivo_id, $linha_arq){
                                
                                if ($tp_arq == "REMESSA"){
                                    $up = " arquivos_id_remessa = ".$arquivo_id.
                                    ",nu_linha_remessa    = ".$linha_arq;
                                }
                                elseif ($tp_arq == "REMESSA"){
                                    $up = " arquivos_id_retorno = ".$arquivo_id.
                                    ",nu_linha_retorno    = ".$linha_arq;
                                }
                                $update = " update contrato_parcelas 
                                set ".$up."					
                                where id = ".$parcela_id;
                                
                                return $conexao_BD_1->query($update);
                            }
                            
                            function verifica_informacoes(&$conexao_BD_1, $contrato_id){
                                
                                $select = "select pc.cpf_cnpj doc_comprador, pc.nome nome_comprador, pc.rua rua_comprador, pc.numero numero_comprador, pc.bairro bairro_comprador, pc.cidade cidade_comprador, pc.estado estado_comprador, pc.cep cep_comprador, pc.email email_comprador,
                                pv.nome nome_vendedor, pv.cpf_cnpj doc_vendedor, pv.rua rua_vendedor, pv.numero numero_vendedor, pv.complemento complemento_vendedor, pv.bairro bairro_vendedor, pv.cidade cidade_vendedor, pv.cep cep_vendedor, pv.estado estado_vendedor
                                from contratos c
                                join pessoas pc on pc.id = c.comprador_id
                                join pessoas pv on pv.id = c.vendedor_id
                                where c.id = ".$contrato_id." ";
                                
                                return $conexao_BD_1->query($select);
                            }
                            
                            function lista_vendedores_contratos(&$conexao_BD_1){
                                $select = " SELECT p.id as vendedor_id, p.nome as vendedor_nome, 
                                pu.id as user_id, pu.nome as user_nome, 
                                COUNT(ct.id) total_ct , SUM(  CASE  WHEN ct.id = ( SELECT DISTINCT contratos_id 
                                FROM contrato_parcelas 
                                WHERE contratos_id = ct.id  AND 
                                dt_pagto = '0000-00-00' AND 
                                dt_vencimento < '".date('Y-m-d')."' 
                                ) 
                                THEN 1 ELSE 0 
                                END ) total_inadp
                                FROM contratos ct 
                                JOIN pessoas p ON p.id = ct.vendedor_id 
                                JOIN pessoas pu ON pu.id = ct.pessoas_id_inclusao  
                                WHERE 
                                (ct.tp_contrato = 'adimplencia') and contratos_id_pai is null 
                                and ct.id NOT IN ( SELECT c2.contratos_id_pai FROM contratos c2 WHERE c2.contratos_id_pai IS NOT NULL GROUP BY c2.contratos_id_pai ) 
                                and ct.id IN ( SELECT contratos_id FROM contrato_parcelas WHERE dt_pagto = '0000-00-00' )
                                GROUP BY p.id, p.nome , pu.id , pu.nome  
                                order by p.nome ";
                                
                                return $conexao_BD_1->query($select);
                            }
                            
                            function desfazer_pg_parcela($id_parcela, &$conexao_BD_1){
                                
                                //valida se não tem ted
                                $select = " SELECT * 
                                FROM contrato_parcelas
                                WHERE id = ".$id_parcela;
                                $parc = $conexao_BD_1->query($select);
                                if (!empty($parc[0]['teds_id']) && is_numeric($parc[0]['teds_id'])){
                                    return $parc[0]['teds_id'];
                                }
                                //desfaz liquidar
                                $update = "UPDATE contrato_parcelas 
                                SET dt_pagto = '0000-00-00', 
                                vl_pagto = 0.00 , 
                                dt_credito = NULL, 
                                tratar_ted = 0, 
                                pessoas_id_atualizacao = NULL, 
                                dt_processo_pagto = NULL, 
                                motivo_zerado = NULL,
                                observacao_zerado = NULL 
                                WHERE id = ".$id_parcela;
                                if($conexao_BD_1->query_atualizacao($update)){
                                    return 'OK';
                                }
                                
                                return 'error';
                            }
                            
                            function zerar_parcelas(&$conexao_BD_1, $contrato, $pessoas_id_atualizacao, $fl_acao_judicial = "N", $parcela_id = "", $motivo_zerado = "", $observacao_zerado = ""){
                                
                                $where_parcela = "";
                                $update_parcelas = "UPDATE contrato_parcelas
                                SET vl_pagto = 0.00,
                                pessoas_id_atualizacao = ".$pessoas_id_atualizacao.",
                                dt_processo_pagto='".$contrato->dt_parcelas_zerado."',
                                dt_pagto = '".$contrato->dt_parcelas_zerado."',
                                dt_credito='".$contrato->dt_parcelas_zerado."' ";
                                if ($fl_acao_judicial == "S"){
                                    $update_parcelas .= " ,fl_acao_judicial = 'S' ";
                                }
                                if ($motivo_zerado != ""){
                                    $update_parcelas .= " ,motivo_zerado = '".$motivo_zerado."' ";
                                }
                                if ($observacao_zerado != ""){
                                    $update_parcelas .= " ,observacao_zerado = '".$observacao_zerado."' ";
                                }
                                
                                if ($parcela_id != ""){
                                    $where_parcela  .= " and id = ".$parcela_id;
                                }
                                $update_parcelas .= " where contratos_id=".$contrato->id." and (dt_pagto is null or dt_pagto ='0000-00-00') and vl_pagto = 0.00 ".$where_parcela;
                                $conexao_BD_1->query_atualizacao($update_parcelas);
                                
                            }
                            
                            function atualiza_fl_negativada_parcela(&$conexao_BD_1, $parcela_id, $fl_negativada){
                                
                                $update_parcelas = "UPDATE contrato_parcelas
                                SET fl_negativada='".$fl_negativada."' 
                                WHERE id = ".$parcela_id;
                                return $conexao_BD_1->query_atualizacao($update_parcelas);
                                
                                
                            }

                            function altera_parcelas_contrato_retirado_suspensao( &$conexao_BD_1,  $id, $inicio, $fim){

                                /*
                                $INICIO ERA UTILIZADO NA ROTINA QUE REALOCAVA AS PARCELAS SOMENTE QUE ESTAVAM DENTRO
                                DO PERÍODO DE SUSPENSÃO.
                                ESTA ROTINA FOI ALTERADA, DE ACORDO COM PEDIDO DA CAROL PARA ALTERAR O VENCIMENTO DE TODAS AS ATRASADAS
                                DESCONSIDERANDO QUANDO O CONTRATO FOI SUSPENSO.
                                PARA MANTER O MÍNIMO DE ALTERAÇÕES O $INICIO FOI MANTIDO NA ROTINA, PORÉM NÃO ESTÁ SENDO UTILIZADO
                                */
				
                                // $select = " 		
                                // select id from contrato_parcelas where contratos_id = $id and dt_vencimento between '$inicio' and '$fim'
                                // ";
                                $select = "SELECT 
                                    id 
                                from 
                                    contrato_parcelas 
                                where 
                                    contratos_id = $id 
                                    and 
                                    dt_vencimento <= '$fim'
                                    and
                                    (dt_pagto is null or dt_pagto = '0000-00-00')
                                ";
                                // 
                                // echo $select;
                                // exit;
                                $parcelas_alterar_vencimento = $conexao_BD_1->query($select);
                                
                                $select = " 		
                                select max(dt_vencimento) as dt_vencimento from contrato_parcelas where contratos_id = $id
                                ";
                                
                                $ultimo_vencimento = new DateTime($conexao_BD_1->query($select)[0]['dt_vencimento']);
                                
                                foreach ($parcelas_alterar_vencimento as $key => $value) {
                                    $ultimo_vencimento->add(new DateInterval('P1M'));
                                    
                                    $update = "  update contrato_parcelas set arquivos_id_remessa = NULL, dt_vencimento = '" . $ultimo_vencimento->format('Y-m-d') . "' 
                                    where id = ".$value['id'];
                            
                                    $conexao_BD_1->query_atualizacao($update);
                                }
                                
                                
                                return 'ok';	
                                
                            }

                            function get_parcela( &$conexao_BD_1, $parcela_id) {
                                $select = "select * from contrato_parcelas where id = ".$parcela_id;
                                return $conexao_BD_1->query($select);
                            }

                            function get_contratos_aviso_spc( &$conexao_BD_1){
                                $select = "
                                    select c.id, p.nome, p.email, c.descricao,
                                    GROUP_CONCAT(cp.vl_parcela ORDER BY cp.dt_vencimento DESC SEPARATOR ',') valor_parcelas,
                                    GROUP_CONCAT(cp.dt_vencimento ORDER BY cp.dt_vencimento DESC SEPARATOR ',') vencimento_parcelas,
                                    (SELECT GROUP_CONCAT(p.id SEPARATOR ',') from pessoas p where p.supervisor = 'S') as supervisores
                                    from contrato_parcelas cp 
                                    join contratos c on c.id = cp.contratos_id
                                    join pessoas p on p.id = c.comprador_id
                                    where 
                                    dt_vencimento between (date_add(curdate(),INTERVAL -6 DAY)) and (date_add(curdate(),INTERVAL -1 DAY))
                                    and 
                                    (dt_pagto is null or dt_pagto = '0000-00-00')
                                    and
                                    (c.suspenso is null or c.suspenso = 'N')
                                    and
                                    (c.repasse is null or c.repasse = 'N')
                                    group by c.id
                                    order by c.id
                                ";
                                return $conexao_BD_1->query($select);
                            }

                            function get_contratos_inclusao_spc( &$conexao_BD_1){
                                $select = "
                                    select c.id, p.nome, p.email, c.descricao,
                                    GROUP_CONCAT(cp.vl_parcela ORDER BY cp.dt_vencimento DESC SEPARATOR ',') valor_parcelas,
                                    GROUP_CONCAT(cp.dt_vencimento ORDER BY cp.dt_vencimento DESC SEPARATOR ',') vencimento_parcelas,
                                    (SELECT GROUP_CONCAT(p.id SEPARATOR ',') from pessoas p where p.supervisor = 'S') as supervisores
                                    from contrato_parcelas cp 
                                    join contratos c on c.id = cp.contratos_id
                                    join pessoas p on p.id = c.comprador_id
                                    where 
                                    dt_vencimento = (date_add(curdate(),INTERVAL -7 DAY))
                                    and 
                                    (dt_pagto is null or dt_pagto = '0000-00-00')
                                    and
                                    (c.suspenso is null or c.suspenso = 'N')
                                    and
                                    (c.repasse is null or c.repasse = 'N')
                                    group by c.id
                                    order by c.id
                                ";
                                return $conexao_BD_1->query($select);
                            }

    function lista_historico($contrato_id, &$conexao_BD_1){
        $select = "
        SELECT *,
        (select count(*) from contrato_parcelas cp where cp.contratos_id = T3.id ) pc_total,
        (select count(*) from contrato_parcelas cp 
                where cp.contratos_id = T3.id  and 
                (dt_pagto is not null and 
                dt_pagto <> '0000-00-00' )) pc_liqd,
        (select count(*) from contrato_parcelas cp where cp.contratos_id = T3.id and 
                dt_vencimento < '".date('Y-m-d')."' and
                (dt_pagto is null or 
                dt_pagto = '0000-00-00' ) ) pc_atrasada

        FROM (
        SELECT T2.id, 
                T2.descricao,
                T2.dt_contrato,
                T2.tp_contrato,
                T2.status,
                T2.vl_contrato,
                T2.suspenso,
                T2.contratos_id_pai
        FROM (
            SELECT
                @r AS _id,
                (SELECT @r := contratos_id_pai FROM contratos WHERE id = _id) AS contratos_id_pai,
                @l := @l + 1 AS lvl
            FROM
                (SELECT @r := ".$contrato_id.", @l := 0) vars,
                contratos m
            WHERE @r <> 0) T1
        JOIN contratos T2
        ON T1._id = T2.id
        UNION ALL
        select  id,
                descricao,
                dt_contrato,
                tp_contrato,
                status,
                vl_contrato,
                suspenso,
                contratos_id_pai
        from    (select * from contratos
                    order by contratos_id_pai, id) products_sorted,
                (select @pv := '".$contrato_id."') initialisation
        where   find_in_set(contratos_id_pai, @pv)
        and     length(@pv := concat(@pv, ',', id))
        ) T3
        where not T3.status = 'excluido'
        order by T3.contratos_id_pai";


        return $conexao_BD_1->query($select);
        
    }                            
}