<?php
class protocolosDB{
    
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }
    
    public function __get($atrib){
        return $this->$atrib;
    }
    
    function lista_protocolos($protocolos, &$conexao_BD_1,  $filtros = "" , $order = "" , $inicial = 0,$limit=30,$ocorrencia=0){

        $data_atual = date("Y-m-d");
        $data_prox  = new DateTime($data_atual);
        $data_prox->sub(new DateInterval("P5D"));
        $dtm5       = $data_prox->format('Y-m-d');
        
        // $select = "SELECT p.*
        //             FROM protocolos p
        //         ";

        $select = "
                    SELECT 
                        p.*,
                        (select po.data  
                            from protocolos_eventos po 
                            where po.protocolos_id = p.id 
                            order by po.id desc
                            limit 1) as dt_ocorrencia
                    FROM protocolos p
        ";

        $where  = " where enable = 1 ";
        $where .= $this->retorna_where_lista_protocolos( $filtros, $protocolos);

        $orderby = " ORDER BY ";
        if($order != ""){
            $orderby.=$order;
        }
        $orderby .= "  p.id asc  ";
        
        if ($limit == "N"){
            $limite = "";
        }
        else{
            if ($limit == ""){
                $limit = 30;
            }
            $limite = " LIMIT $inicial, $limit  ";
        }

        // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Contratos total ' . json_encode($select.$where.$orderby.$limite));

        $ret = $conexao_BD_1->query($select.$where.$orderby.$limite);
        
        return $ret;
    }

    function lista_totais($protocolos, &$conexao_BD_1,  $filtros = "" , $order = "" , $inicial = 0,$limit=30,$ocorrencia=0){

        $data_atual = date("Y-m-d");
        $data_prox  = new DateTime($data_atual);
        $data_prox->sub(new DateInterval("P5D"));
        $dtm5       = $data_prox->format('Y-m-d');
        
        // $select = "SELECT p.*
        //             FROM protocolos p
        //         ";

        $select = "
                    SELECT 
                        count(*) as qtd,
                        sum(valor) as valor,
                        sum(status = 'Pendente') as pendente,
                        sum(status = 'Finalizado') as finalizado,
                        sum(status = 'Cancelado') as cancelado,
                        sum(prazo < curdate() and status = 'Pendente') as atrasado                    
                    FROM protocolos p
        ";

        $where  = " where enable = 1 ";
        $where .= $this->retorna_where_lista_protocolos( $filtros, $protocolos);

        $orderby = " ORDER BY ";
        if($order != ""){
            $orderby.=$order;
        }
        $orderby .= "  p.id asc  ";
        
        if ($limit == "N"){
            $limite = "";
        }
        else{
            if ($limit == ""){
                $limit = 30;
            }
            $limite = " LIMIT $inicial, $limit  ";
        }
        // $ret = $conexao_BD_1->query($select.$where.$orderby.$limite);
        $conexao_BD_1->query($select.$where.$orderby.$limite);
        $ret = $conexao_BD_1->leRegistro();

        $retorno = ['qtd' => $ret['qtd'],
                    'valor' => $ret['valor'],
                    'pendente' => $ret['pendente'],
                    'finalizado' => $ret['finalizado'],
                    'cancelado' => $ret['cancelado'],
                    'atrasado' => $ret['atrasado'],
                ];

        return $retorno;
    }

    function busca_protocolo(&$conexao_BD_1, $protocolo_id){
        $select = "select pr.*,
                            pc.nome as p_cadastro,
                            ps.nome as p_setor,
                            pf.nome as p_finalizado
                    from protocolos pr
                    left join pessoas as pc on pc.id = cad_pessoa
                    left join pessoas as ps on ps.id = trans_pessoa
                    left join pessoas as pf on pf.id = finalizado_pessoa
                    where pr.id =".$protocolo_id;
        return $conexao_BD_1->query($select);
        
    }

    function retorna_where_lista_protocolos($filtros, $protocolos=""){
        $where = " ";
        
        if($filtros!=""){
            
            // Filtro Protocolo
            if(!empty($filtros["filtro_protocolo_id"])){
                $where .= "  AND p.protocolo = ".$filtros["filtro_protocolo_id"]." ";
            }

            // Filtro data de registro
            if(!empty($filtros["filtro_data"])){
                $data = date('Y-m-d', strtotime(str_replace("/", "-", $filtros["filtro_data"])));
                $where .= " AND  p.dt_registro between '".$data." 00:00:00' and '".$data." 23:59:59' ";
            }
            
            // Filtro data do prazo
            if(!empty($filtros["filtro_prazo"])){
                $data = date('Y-m-d', strtotime(str_replace("/", "-", $filtros["filtro_prazo"])));
                $where .= " AND  p.prazo between '".$data." 00:00:00' and '".$data." 23:59:59' ";
            }

            // Filtro por Status
            if(!empty($filtros["filtro_status"])){
                $where .= " AND  p.status = '".$filtros["filtro_status"]."' ";
            }

            // Filtro por Setor
            if(!empty($filtros["filtro_setor"])){
                $where .= " AND  p.setor = '".$filtros["filtro_setor"]."' ";
            }

            // Filtro por prazo se vencido ou a vencer
            if(!empty($filtros["filtro_vencimento"])){
                if($filtros["filtro_vencimento"] == 'vencido'){
                    $where .= " AND  p.prazo < '".date('Y-m-d')."' AND p.status = 'Pendente' ";
                } else {
                    $where .= " AND  p.prazo >= '".date('Y-m-d')."' AND p.status = 'Pendente' ";
                }
            }

            // Filtro Vendedor
            if(!empty($filtros["filtro_vendedor"])){
                if($filtros["filtro_vendedor"][0] == '*'){
                    $filtro_vendedor = str_replace('*','',$filtros["filtro_vendedor"]);
                    $busca_array = explode(' ',$filtro_vendedor);
                    foreach($busca_array as $busca_item){
                        if(!empty(trim($busca_item))){
                                $where .="  AND  ( remove_acentos(p.vendedor) LIKE '%".$busca_item."%' )  ";
                        }
                    }
                } else{
                    
                    $busca = trata_busca_sql_score(rtrim($filtros["filtro_vendedor"], ' '));
                    if(isset($busca['multi'])){

                        $filtro_vendedor = rtrim(str_replace('*','',$busca['multi']));
                        $busca_array = explode(' ',$filtro_vendedor);

                        foreach($busca_array as $key => $busca_item){
                            if($key == 0 ) { 
                                $where .=" AND ( remove_acentos(p.vendedor) LIKE '%".$busca_item."%' ";
                            } else {
                                $where .=" OR remove_acentos(p.vendedor) LIKE '%".$busca_item."%' ";
                            }
                        }
                        $where .= " ) ";                                                

                    } else{
                        $where .="  AND  ( remove_acentos(p.vendedor) LIKE '%".$busca['simples']."%' )";

                    }
                }
                
            }

            // Filtro Comprador
            if(!empty($filtros["filtro_comprador"])){
                if($filtros["filtro_comprador"][0] == '*'){
                    $filtro_comprador = str_replace('*','',$filtros["filtro_comprador"]);
                    $busca_array = explode(' ',$filtro_comprador);
                    foreach($busca_array as $busca_item){
                        if(!empty(trim($busca_item))){
                                $where .="  AND  ( remove_acentos(p.comprador) LIKE '%".$busca_item."%' )  ";
                        }
                    }
                } else{
                    
                    $busca = trata_busca_sql_score(rtrim($filtros["filtro_comprador"], ' '));
                    if(isset($busca['multi'])){

                        $filtro_comprador = rtrim(str_replace('*','',$busca['multi']));
                        $busca_array = explode(' ',$filtro_comprador);

                        foreach($busca_array as $key => $busca_item){
                            if($key == 0 ) { 
                                $where .=" AND ( remove_acentos(p.comprador) LIKE '%".$busca_item."%' ";
                            } else {
                                $where .=" OR remove_acentos(p.comprador) LIKE '%".$busca_item."%' ";
                            }
                        }
                        $where .= " ) ";                                                

                    } else{
                        $where .="  AND  ( remove_acentos(p.comprador) LIKE '%".$busca['simples']."%' )";

                    }
                }
                
            }

            // Filtro Eventos
            if(!empty($filtros["filtro_evento"])){
                if($filtros["filtro_evento"][0] == '*'){
                    $filtro_evento = str_replace('*','',$filtros["filtro_evento"]);
                    $busca_array = explode(' ',$filtro_evento);
                    foreach($busca_array as $busca_item){
                        if(!empty(trim($busca_item))){
                                $where .="  AND  ( remove_acentos(p.evento) LIKE '%".$busca_item."%' )  ";
                        }
                    }
                } else{
                    
                    $busca = trata_busca_sql_score(rtrim($filtros["filtro_evento"], ' '));
                    if(isset($busca['multi'])){

                        $filtro_evento = rtrim(str_replace('*','',$busca['multi']));
                        $busca_array = explode(' ',$filtro_evento);

                        foreach($busca_array as $key => $busca_item){
                            if($key == 0 ) { 
                                $where .=" AND ( remove_acentos(p.evento) LIKE '%".$busca_item."%' ";
                            } else {
                                $where .=" OR remove_acentos(p.evento) LIKE '%".$busca_item."%' ";
                            }
                        }
                        $where .= " ) ";                                                

                    } else{
                        $where .="  AND  ( remove_acentos(p.evento) LIKE '%".$busca['simples']."%' )";

                    }
                }
                
            }

            // Filtro Produto
            if(!empty($filtros["filtro_produto"])){
                if($filtros["filtro_produto"][0] == '*'){
                    $filtro_produto = str_replace('*','',$filtros["filtro_produto"]);
                    $busca_array = explode(' ',$filtro_produto);
                    foreach($busca_array as $busca_item){
                        if(!empty(trim($busca_item))){
                                $where .="  AND  ( remove_acentos(p.produto) LIKE '%".$busca_item."%' )  ";
                        }
                    }
                } else{
                    
                    $busca = trata_busca_sql_score(rtrim($filtros["filtro_produto"], ' '));
                    if(isset($busca['multi'])){

                        $filtro_produto = rtrim(str_replace('*','',$busca['multi']));
                        $busca_array = explode(' ',$filtro_produto);

                        foreach($busca_array as $key => $busca_item){
                            if($key == 0 ) { 
                                $where .=" AND ( remove_acentos(p.produto) LIKE '%".$busca_item."%' ";
                            } else {
                                $where .=" OR remove_acentos(p.produto) LIKE '%".$busca_item."%' ";
                            }
                        }
                        $where .= " ) ";                                                

                    } else{
                        $where .="  AND  ( remove_acentos(p.produto) LIKE '%".$busca['simples']."%' )";

                    }
                }
                
            }

            // Fim dos filtros
        }

        return $where;
    }

    function busca_trans_setor(&$conexao_BD_1, $protocolo_id){
        $select = "select   ps.setor,
                            ps.data as data,
                            ( SELECT data 
                                from protocolos_setor t2 
                                where t2.id > ps.id and t2.protocolos_id = ".$protocolo_id." 
                                limit 1
                            ) as dataF,
                            pe.nome
                    from protocolos_setor ps
                    left join pessoas pe on pe.id = ps.pessoas_id
                    where ps.protocolos_id = ".$protocolo_id;

        // $select = "select * from protocolos_setor";

        return $conexao_BD_1->query($select);
        
    }

    function lista_ocorrencias(&$conexao_BD_1, $protocolos_id){
        $select = "select   po.setor,
                            po.ocorrencia,
                            po.data,

                            pe.nome
                    from protocolos_eventos po
                    left join pessoas pe on pe.id = po.pessoas_id
                    where po.protocolos_id = ".$protocolos_id."
                     order by po.id desc ";

        // $select = "select * from protocolos_eventos";

        return $conexao_BD_1->query($select);
        
    }


}
