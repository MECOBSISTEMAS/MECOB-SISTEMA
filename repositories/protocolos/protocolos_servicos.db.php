<?php
class protocolosDB{
    
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }
    
    public function __get($atrib){
        return $this->$atrib;
    }
    
    function lista_protocolos_servicos($protocolos, &$conexao_BD_1,  $filtros = "" , $order = "" , $inicial = 0,$limit=30,$ocorrencia=0){


        // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Filtros' . json_encode($order));

        $data_atual = date("Y-m-d");
        $data_prox  = new DateTime($data_atual);
        $data_prox->sub(new DateInterval("P5D"));
        $dtm5       = $data_prox->format('Y-m-d');

        $select = "
                    SELECT 
                        ps.*
                    FROM protocolos_servicos ps
        ";

        $where  = " where enable = 1 ";
        $where .= $this->retorna_where_lista_protocolos( $filtros, $protocolos);

        $orderby = " ORDER BY ";
        if($order != ""){
            $orderby.=rtrim($order, ',');
        } else {
            $orderby .= "  ps.nome asc  ";
        }
        
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
        $select = "select ps.*
                    from protocolos_servicos ps
                    where ps.id =".$protocolo_id;
        return $conexao_BD_1->query($select);
        
    }

    function retorna_where_lista_protocolos($filtros, $protocolos=""){
        $where = " ";
        
        if($filtros!=""){
            
            // Filtro Protocolo
            if(!empty($filtros["filtro_tipo"])){
                $where .= "  AND ps.tipo = '".$filtros["filtro_tipo"]."' ";
            }

            // Filtro data de registro
            if(!empty($filtros["filtro_data"])){
                $data = date('Y-m-d', strtotime(str_replace("/", "-", $filtros["filtro_data"])));
                $where .= " AND  p.dt_registro between '".$data." 00:00:00' and '".$data." 23:59:59' ";
            }
            

            // Filtro Vendedor que na realidade é nome do cliente
            if(!empty($filtros["filtro_vendedor"])){
                if($filtros["filtro_vendedor"][0] == '*'){
                    $filtro_vendedor = str_replace('*','',$filtros["filtro_vendedor"]);
                    $busca_array = explode(' ',$filtro_vendedor);
                    foreach($busca_array as $busca_item){
                        if(!empty(trim($busca_item))){
                                $where .="  AND  ( remove_acentos(ps.nome) LIKE '%".$busca_item."%' )  ";
                        }
                    }
                } else{
                    
                    $busca = trata_busca_sql_score(rtrim($filtros["filtro_vendedor"], ' '));
                    if(isset($busca['multi'])){

                        $filtro_vendedor = rtrim(str_replace('*','',$busca['multi']));
                        $busca_array = explode(' ',$filtro_vendedor);

                        foreach($busca_array as $key => $busca_item){
                            if($key == 0 ) { 
                                $where .=" AND ( remove_acentos(ps.nome) LIKE '%".$busca_item."%' ";
                            } else {
                                $where .=" OR remove_acentos(ps.nome) LIKE '%".$busca_item."%' ";
                            }
                        }
                        $where .= " ) ";                                                

                    } else{
                        $where .="  AND  ( remove_acentos(ps.nome) LIKE '%".$busca['simples']."%' )";

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
