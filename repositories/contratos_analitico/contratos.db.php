<?php
class contratosDB{
    
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }
    
    public function __get($atrib){
        return $this->$atrib;
    }

    function monta_select($where, $filtros) 
    {
        $select =" select count(*)
        from contratos c 
        left join pessoas pc on pc.id = c.comprador_id
        left join pessoas pv on pv.id = c.vendedor_id           
        where $where ";

        $select .= $this->retorna_where_lista_contratos( $filtros);
        // echo $select;
        // exit;
        return $select;
    }
    
    function lista_contratos($contratos, &$conexao_BD_1,  $filtros = "" , $order = "" , $inicial = 0,$limit=30,$ocorrencia=0){
        
        $data_atual = date("Y-m-d");
        $data_prox = new DateTime($data_atual);
        $data_prox->sub(new DateInterval("P5D"));
        $dtm5 = $data_prox->format('Y-m-d');

        $filtro_data = '';
        if ($filtros != ''){
            if (!empty($filtros['filtro_data'])) {
                $data_inicial = ConverteData($filtros['filtro_data']);
                if (!empty($filtros['filtro_data_fim'])) {
                    $data_final = ConverteData($filtros['filtro_data_fim']);
                    $filtro_data = " (dt_vencimento between '$data_inicial' and '$data_final') and ";
                } else {
                    $filtro_data = " (dt_vencimento > '$data_inicial') and ";                
                }
            } else {
                if (!empty($filtros['filtro_data_fim'])) {
                    $data_final = ConverteData($filtros['filtro_data_fim']);
                    $filtro_data = " (dt_vencimento < '$data_final' and) ";
                }
            }
        }

        $vencidos = $this->monta_select("(c.suspenso = 'N' or c.suspenso is null) and c.id in (select cp.contratos_id from contrato_parcelas cp 
        where cp.contratos_id = c.id and 
        dt_vencimento < '".date('Y-m-d')."' and
        $filtro_data
        (dt_pagto is null or 
        dt_pagto = '0000-00-00'))",$filtros);

        $a_vencer = $this->monta_select("(c.suspenso = 'N' or c.suspenso is null) and c.id in (  select cp.contratos_id from contrato_parcelas cp 
        where cp.contratos_id = c.id and 
        dt_vencimento > '".date('Y-m-d')."' and
        $filtro_data
        (dt_pagto is null or 
        dt_pagto = '0000-00-00' ) ) 
        AND c.id  in (  select cp.contratos_id from contrato_parcelas cp 
        where cp.contratos_id = c.id and 
        dt_pagto is  null or dt_pagto = '0000-00-00'  )",$filtros);

        $liquidados = $this->monta_select("(c.suspenso = 'N' or c.suspenso is null) and c.id in (  select cp.contratos_id from contrato_parcelas cp 
        where cp.contratos_id = c.id and 
        $filtro_data
        (dt_pagto is not null and 
        dt_pagto <> '0000-00-00' ) )",$filtros);

        $suspensos = $this->monta_select("c.suspenso = 'S'",$filtros);

        $select = "select ($vencidos) as vencidos, ($a_vencer) as a_vencer, ($liquidados) as liquidados, ($suspensos) as suspensos";
        
        // echo $select;
        return $conexao_BD_1->query($select);
    }
        
        function retorna_where_lista_contratos($filtros){
            
            $where = " ";
            
            
            
            if($filtros!=""){
                if(!empty($filtros["filtro_tipo"])){
                    if($filtros["filtro_tipo"] == 'adimplencia'){
                        $where .= "  AND c.tp_contrato = 'adimplencia' ";
                    } elseif ($filtros["filtro_tipo"] == 'inadimplencia') {
                        $where .= "  AND c.tp_contrato = 'inadimplencia' ";
                    } elseif ($filtros["filtro_tipo"] == 'suspenso') {
                        $where .= "  AND c.suspenso = 'S' ";
                    }
                }
                if(!empty($filtros["filtro_vendedor"])){
                    
                    if($filtros["filtro_vendedor"][0] == '*'){
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
                
                if(!empty($filtros["filtro_comprador"])){
                    if($filtros["filtro_comprador"][0] == '*'){
                        $filtro_comprador = str_replace('*','',$filtros["filtro_comprador"]);
                        $busca_array = explode(' ',$filtro_comprador);
                        foreach($busca_array as $busca_item){
                            if(!empty(trim($busca_item)))
                            $where .="  AND  (       remove_acentos(pc.nome) LIKE '%".$busca_item."%' 
                            OR remove_acentos(pc.apelido) LIKE '%".$busca_item."%' 
                            OR pc.email LIKE '%".$busca_item."%' 
                            OR pc.cpf_cnpj LIKE '%".$busca_item."%'
                            )  ";
                        }
                    }
                    else{
                        
                        $busca = trata_busca_sql_score($filtros["filtro_comprador"]);
                        if(isset($busca['multi'])){
                            $where .= "  AND MATCH (pc.nome,pc.apelido,pc.email,pc.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";
                        }
                        else{
                            $where .="  AND  (       remove_acentos(pc.nome) LIKE '%".$busca['simples']."%' 
                            OR remove_acentos(pc.apelido) LIKE '%".$busca['simples']."%' 
                            OR pc.email LIKE '%".$busca['simples']."%' 
                            OR pc.cpf_cnpj LIKE '%".$busca['simples']."%'
                            )  ";
                        }
                    }
                    
                }
            }

            return $where;
            
        }
        
        
        
    }