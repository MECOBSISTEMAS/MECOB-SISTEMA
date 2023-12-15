<?php

class rodiziosDB{
    
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }
    
    public function __get($atrib){
        return $this->$atrib;
    }
    
    function gerarRodizio(&$conexao_BD_1){
        //INICIO CARTEIRAS ORDENADAS POR QUANTIDADE DE CONTRATOS
            $select = " SELECT 
                clientes.id vendedor_id,
                count(c.id) qtdContratos,
                clientes.nome,
                clientes.email
            FROM 
                pessoas clientes 
                JOIN 
                contratos c ON c.vendedor_id = clientes.id
                JOIN
                contrato_parcelas cp ON cp.contratos_id = c.id
            WHERE 
                (eh_vendedor = 'S') 
                AND 
                status_id = 1
                AND
                c.tp_contrato = 'adimplencia'
                AND
                (cp.dt_pagto = '0000-00-00' OR cp.dt_pagto IS NULL)
            GROUP BY vendedor_id
            ORDER BY qtdContratos DESC
            ";
            $carteiras = $conexao_BD_1->query($select);
        //FIM CARTEIRAS ORDENADAS

        //INICIO LISTA OPERADORES
            $select = "SELECT
                id,
                nome,
                email
            from pessoas
            where
                operador = 'S' 
            ";
            $operadores = $conexao_BD_1->query($select);

            $total_operadores = count($operadores)-1;
            $operador_atual = 0;
        //FIM LISTA OPERADORES

        $select = " SELECT max(id_rodizio) as ultimo_rodizio from rodizio_clientes";
        $ultRodizio = $conexao_BD_1->query($select);
        if ($ultRodizio[0]['ultimo_rodizio'] == null) {
            $ultRodizio = 0;
        } else {
            $ultRodizio = $ultRodizio[0]['ultimo_rodizio'];
        }
        $ultRodizio ++;
        
        $insert = "INSERT
        into rodizio_clientes 
        (pessoas_id, tp_contrato, vendedor_id, data_inicio, id_rodizio, ativo)
        values ";
        foreach($carteiras as $key => $value){
            if ($operador_atual > $total_operadores){
                $operador_atual = 0;
            }

            $pessoas_id = $operadores[$operador_atual]['id'];
            $vendedor_id = $value['vendedor_id'];
            $data_inicio = date('Y-m-d');

            $insert .= "('$pessoas_id','adimplencia',$vendedor_id,'$data_inicio',$ultRodizio,'N'),";
            // echo $value['vendedor_id']."<br>";
            
            $operador_atual ++;
        }
        $insert = substr($insert,0,strlen($insert)-1);        
        $conexao_BD_1->query_inserir($insert);
        // exit;
        return 1;
    }

    function carregarRodizios(&$conexao_BD_1){
        $select = "SELECT
        id_rodizio, date_format(data_inicio,'%d/%m/%Y') data_inicio, ativo from rodizio_clientes group by id_rodizio, data_inicio, ativo order by id_rodizio desc";
        return $conexao_BD_1->query($select);
    }
    function carregarRodizioId(&$conexao_BD_1,$id){
        $select = "SELECT
        p.id,
        p.nome,
        count(rc.id) qtd,
        id_rodizio
        from
        rodizio_clientes rc
        join
        pessoas p on p.id = rc.pessoas_id
        where rc.id_rodizio = $id group by p.id";
        return $conexao_BD_1->query($select);
    }
    function carregarRodizioPessoa(&$conexao_BD_1,$id,$pessoa){
        $select = "SELECT
        rc.id_rodizio,
        v.id,
        v.nome,
        (
            select count(distinct(contratos_id)) from contrato_parcelas where contratos_id in (select id from contratos where vendedor_id = rc.vendedor_id and (suspenso = 'N' or suspenso is null) and (repasse = 'N' or repasse is null))
            and (dt_pagto is null or dt_pagto = '0000-00-00') and dt_vencimento > date(now())
        ) qtdContratosAVencer
        from
        rodizio_clientes rc
        join
        pessoas v on v.id = rc.vendedor_id
        where rc.id_rodizio = $id and rc.pessoas_id = $pessoa group by v.id order by v.nome";
        // echo $select;        
        return $conexao_BD_1->query($select);
    }

    function carregarListaOperador(&$conexao_BD_1,$id,$limit=0,$ultimosFeitos=false){
        $limit = ($limit == 0) ? "" : "LIMIT $limit";
        $select = "SELECT
        max(id_rodizio) as id_rodizio
        from
        rodizio_clientes
        where ativo = 'S'";
        $rodizio_atual = $conexao_BD_1->query($select)[0]['id_rodizio'];
        if ($ultimosFeitos == true){
            $ultimos = ' > ';
            $ordernarUltima = ' ultima_ocorrencia desc';
        } else {
            $ordernarUltima = '';
            $ultimos = ' = ';
        }
        
        $select = "SELECT
                rc.id operador_id,
                c.id contratos_id,
                pv.nome nome_vendedor,
                pc.nome nome_comprador,
                p.dt_vencimento,
                p.vl_corrigido,
                p.nu_parcela,
                pv.id id_vendedor,
                pc.id id_comprador,                
                pc.telefone,
                pc.celular,
                tdv.total_divida,
                max(o.data_ocorrencia) as ultima_ocorrencia,
                '0' feitos
            from 
                contratos c
            join
                rodizio_clientes rc on c.vendedor_id = rc.vendedor_id
            join pessoas pv on pv.id = c.vendedor_id
            join pessoas pc on pc.id = c.comprador_id
            join contrato_parcelas p on p.contratos_id = c.id        
            left join total_divida_vendedor tdv on tdv.vendedor_id = c.vendedor_id
            left join ocorrencias o on o.contratos_id = c.id and o.pessoas_id = rc.pessoas_id and o.data_ocorrencia >= p.dt_vencimento
            where
                rc.pessoas_id = $id
                and
                rc.id_rodizio = $rodizio_atual
                and
                p.dt_vencimento between date_sub(now(),INTERVAL 5 DAY) and date_sub(now(),INTERVAL 1 DAY)
                and
                (p.dt_pagto is null or p.dt_pagto = '0000-00-00')
                and
                c.status in('confirmado','em_acordo','parcialmente_em_acordo','pendente')
                and
                o.id is null
            group by c.id
            order by
                p.dt_vencimento,
                total_divida desc
        $limit";

        if ($ultimosFeitos == true){
            $select = "($select) UNION (";
            $select .= " SELECT
            rc.id operador_id,
            c.id contratos_id,
            pv.nome nome_vendedor,
            pc.nome nome_comprador,
            p.dt_vencimento,
            p.vl_corrigido,
            p.nu_parcela,
            pv.id id_vendedor,
            pc.id id_comprador,                
            pc.telefone,
            pc.celular,
            tdv.total_divida,
            max(o.data_ocorrencia) as ultima_ocorrencia,
            '1' feitos
            from 
            contratos c
            join
            rodizio_clientes rc on c.vendedor_id = rc.vendedor_id
            join pessoas pv on pv.id = c.vendedor_id
            join pessoas pc on pc.id = c.comprador_id
            join contrato_parcelas p on p.contratos_id = c.id        
            left join total_divida_vendedor tdv on tdv.vendedor_id = c.vendedor_id
            left join ocorrencias o on o.contratos_id = c.id and o.pessoas_id = rc.pessoas_id and o.data_ocorrencia >= p.dt_vencimento
            where
            rc.pessoas_id = $id
            and
            rc.id_rodizio = $rodizio_atual
            and
            p.dt_vencimento between date_sub(now(),INTERVAL 5 DAY) and date_sub(now(),INTERVAL 1 DAY)
            and
            (p.dt_pagto is null or p.dt_pagto = '0000-00-00')
            and
            c.status in('confirmado','em_acordo','parcialmente_em_acordo','pendente')
            and
            o.id is not null
            group by c.id
            order by
            ultima_ocorrencia desc,
            p.dt_vencimento,
            total_divida desc
            $limit)";    
        }

        // echo $select;
        // exit;

        return $conexao_BD_1->query($select);
    }

    function ativarRodizio(&$conexao_BD_1,$id){
        $update = "UPDATE
        rodizio_clientes set ativo = 'S' where id_rodizio = $id";
        // echo $update;
        $conexao_BD_1->query($update);
        //INICIO LISTA OPERADORES
            $select = "SELECT
                id,
                nome,
                email
            from pessoas
            where
                operador = 'S' 
            ";
            $operadores = $conexao_BD_1->query($select);
        //FIM LISTA OPERADORES
        foreach ($operadores as $key => $value) {
            $nome_operador = $value['nome'];
            $assunto = "Rodízio de carteiras";
            $mensagem = "
            Ola, $nome_operador foi realizado um rodízio e sua carteira de clientes foi alterada, favor entrar no sistema de gestão de carteiras para verificar seus novos clientes.
            ";
            $email_dest = $value['email'];
            // $email_dest = 'pedro.arua@gmail.com';
            $nome_dest = $nome_operador;
            $email_reply = 'contato@mecob.com.br';
            $nome_reply = 'Mecob';
            $raiz= getenv('CAMINHO_RAIZ');
            include "$raiz/inc/mail/inc_mail.php";
        }
        //INICIO LISTA CARTEIRAS
            $select = "SELECT
                p.nome,
                p.email,
                v.nome,
                v.email
            FROM rodizio_clientes rc
            join pessoas as p on p.id = rc.pessoas_id
            join pessoas as v on v.id = rc.vendedor_id 
            WHERE id_rodizio = $id
            ";
            $carteiras = $conexao_BD_1->query($select);
        //FIM LISTA CARTEIRAS

        foreach ($carteiras as $key => $value) {
            $nome_vendedor = $value['nome'];
            $nome_operador = $operadores[$operador_atual]['nome'];
            $assunto = "Rodízio de carteiras";
            $mensagem = "
                Olá, $nome_vendedor, foi realizado um rodízio interno para a gestão de sua carteira. <br/>
                No mês presente e no próximo, seus contratos ficarão aos cuidados da(o) operadora(o) $nome_operador.<br/>
                Para todas as dúvidas e necessidades, favor entrar em contato com o operador mencionado acima no telefone (47) 3045-2767.
            ";
            $email_dest = $value['email'];
            // $email_dest = 'pedro.arua@gmail.com';
            $nome_dest = $value['nome'];
            $email_reply = $operadores[$operador_atual]['email'];
            $nome_reply = $operadores[$operador_atual]['nome'];
            $raiz= getenv('CAMINHO_RAIZ');
            include "$raiz/inc/mail/inc_mail.php";
        }
    }

    function listarSituacaoAtual($conexao_BD_1){
        $select = "SELECT
        *
        from
        pessoas
        where operador = 'S' order by nome";
        $operadores = $conexao_BD_1->query($select);
        return $operadores;

        // $select = "SELECT
        // max(id_rodizio) as id_rodizio
        // from
        // rodizio_clientes
        // where ativo = 'S'";
        // $rodizio_atual = $conexao_BD_1->query($select)[0]['id_rodizio'];
            

        // 	$select = " SELECT
        //         c.id contratos_id,
        //         po.nome nome_operador,
        //         pv.nome nome_vendedor,
        //         pc.nome nome_comprador,
        //         p.dt_vencimento,
        //         p.vl_corrigido,
        //         p.nu_parcela,
        //         pv.id id_vendedor,
        //         pc.id id_comprador,                
        //         pc.telefone,                
        //         pc.celular,                
        //         tdv.total_divida
        //     from 
        //         contratos c
        //     join rodizio_clientes rc on c.vendedor_id = rc.vendedor_id
        //     join pessoas po on po.id = rc.pessoas_id
        //     join pessoas pv on pv.id = c.vendedor_id
        //     join pessoas pc on pc.id = c.comprador_id
        //     join contrato_parcelas p on p.contratos_id = c.id
        //     left join total_divida_vendedor tdv on tdv.vendedor_id = c.vendedor_id
        //     where
        //         po.operador = 'S'
        //         and
        //         rc.id_rodizio = $rodizio_atual
        //         and
        //         p.dt_vencimento between date_sub(now(),INTERVAL 5 DAY) and date_sub(now(),INTERVAL 1 DAY)
        //         and
        //         (p.dt_pagto is null or p.dt_pagto = '0000-00-00')
        //         and
        //         c.status in('confirmado','em_acordo','parcialmente_em_acordo','pendente')
        //     group by c.id
        //     order by
        //         po.id,
        //         p.dt_vencimento,
        //         total_divida desc";
        // 	return $conexao_BD_1->query($select);
    }

    function alterarOperador($conexao_BD_1,$operador,$vendedor,$rodizio){
        $update = "UPDATE rodizio_clientes set pessoas_id = $operador where vendedor_id = $vendedor and id_rodizio = $rodizio";
        // echo $update;
        if ($conexao_BD_1->query($update)) {
            return 1;
        } else {
            return 0;
        }
    }

    function excluirCarteira($conexao_BD_1,$vendedor,$rodizio){
        $update = " DELETE from rodizio_clientes where vendedor_id = $vendedor and id_rodizio = $rodizio";
        echo $update;
        if ($conexao_BD_1->query($update)) {
            return 1;
        } else {
            return 0;
        }
    }
                            
}