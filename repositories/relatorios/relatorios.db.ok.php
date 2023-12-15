<?php
class relatoriosDB{
    
    public function __set($atrib, $value){
        $this->$atrib = $value;
    }
    
    public function __get($atrib){
        return $this->$atrib;
    }

    function lista_sem_ocorrencias(&$conexao_BD_1, $dias=null, array $tipo=null, array $status=null) {
        if($dias   == null) $dias      = 5;
        if($tipo   == null) $tipo[0]   = 'adimplencia';
        if($status == null) $status[0] = 'confirmado';

        $oc_tipo = implode("', '", $tipo);
        $oc_status = implode("', '", $status);

        // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Ocorrências db dias ' . $dias);

        $query = "
            SELECT
                    co.id,
                    co.descricao,
                    co.tp_contrato,
                    co.status,
                    pa.dt_vencimento,
                    oc.data_ocorrencia,
                    pe.nome
            FROM contratos as co
            inner join (select id,
                            contratos_id,
                            max(data_ocorrencia) as data_ocorrencia,
                            pessoas_id
                        from ocorrencias
                        group by contratos_id
                        ) as oc on co.id = oc.contratos_id
            inner join (select contratos_id,
                            sum(vl_parcela) as vl_parcelas,
                            min(dt_vencimento) as dt_vencimento,
                            min(dt_pagto) as dt_pagto,
                            count(*) as qtd_parcelas
                        from contrato_parcelas
                        where (dt_pagto = '0000-00-00' or isnull(dt_pagto))
                        and dt_vencimento < curdate()            
                        group by contratos_id
                        ) as pa on co.id = pa.contratos_id
                
            LEFT JOIN pessoas as pe on pe.id = oc.pessoas_id
            where co.tp_contrato in ('".$oc_tipo."')
            and co.status in ('".$oc_status."')
            and dt_vencimento < date_add(curdate(), INTERVAL -".$dias." DAY)
            and date(data_ocorrencia) < date_add(curdate(),INTERVAL -".$dias." DAY)
            and (isnull(co.suspenso) or co.suspenso = 'N')
            and (isnull(co.repasse) or co.repasse = 'N')
            and isnull(co.motivo_zerado)
        ";

        // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Query ' . $query);
        // echo $query;
        return $conexao_BD_1->query($query);
    }

    function lista_qtd_adimplencia(&$conexao_BD_1, $datai=null, $dataf=null) {
        if($datai  == null) {
            $datai = date('Y-m-d');
        } else {
            $datai = date('Y-m-d', strtotime(str_replace('/', '-', $datai)));
        }

        if($dataf  == null) {
            $dataf = date('Y-m-d');
        } else {
            $dataf = date('Y-m-d', strtotime(str_replace('/', '-', $dataf)));
        }

        $query = "

            SELECT date(co.dt_inclusao) as data,
                pe.nome as nome,
                count(*) as total,
                sum(co.vl_contrato) as valor 
            FROM contratos as co
            join pessoas as pe on pe.id = co.pessoas_id_inclusao
            where 1=1
            and co.tp_contrato = 'adimplencia'
            and co.status = 'confirmado'
            and date(co.dt_inclusao) between '".$datai."' and '".$dataf."'
            group by date(co.dt_inclusao), co.pessoas_id_inclusao
            order by date(co.dt_inclusao) asc, pe.nome

        ";

        // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Query ' . $query);
        // echo $query;
        return $conexao_BD_1->query($query);
    }

    function lista_rpt_baixas(&$conexao_BD_1, $data=null) {
        if($data  == null) {
            $data = date('Y-m-d');
        } else {
            $data = date('Y-m-d', strtotime(str_replace('/', '-', $data)));
        }

        $query = "

            SELECT 
                cp.contratos_id as id_contrato,
                case when not isnull(pev.nome) then pev.nome else 'boleto avulso' end as vendedor,
                case when not isnull(pec.nome) then pec.nome else peb.nome end as comprador,
                cp.nu_parcela as nu_parcela,
                cp.vl_parcela as vl_parcela,
                cp.vl_pagto,
                cp.dt_vencimento as dt_vencimento,
                cp.dt_credito as dt_credito,
                cp.dt_processo_pagto as dt_processamento,
                case when cp.contratos_id > 12460 or isnull(cp.contratos_id) then 'UNICRED' else 'BRADESCO' end as banco,
                co.nu_parcelas as tt_parcelas,
                (select count(*) from contrato_parcelas cpx 
                where cpx.contratos_id = cp.contratos_id 
                    and (not isnull(dt_pagto) and not dt_pagto = '0000-00-00') ) as tt_quitadas,
                co.parcela_primeiro_pagto as parcela_primeiro_pagto,
                ev.nome as evento,
                co.descricao as produto
                
            FROM contrato_parcelas cp
            LEFT JOIN contratos co on co.id = cp.contratos_id
            LEFT JOIN boletos_avulso bo on bo.id = cp.boletos_avulso_id
            LEFT JOIN pessoas pec on pec.id = co.comprador_id
            LEFT JOIN pessoas pev on pev.id = co.vendedor_id
            LEFT JOIN pessoas peb on peb.id = bo.pessoas_id
            LEFT JOIN eventos ev on ev.id = co.eventos_id
            where date(dt_processo_pagto) = '".$data."'
            and not isnull(arquivos_id_retorno)
            
            order by banco desc, cp.contratos_id  asc

        ";

        // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Query ' . $query);
        // echo $query;
        return $conexao_BD_1->query($query);
    }

    function lista_rpt_extrato(&$conexao_BD_1, $data=null) {
        if($data  == null) {
            $data = date('Y-m-d');
        } else {
            $data = date('Y-m-d', strtotime(str_replace('/', '-', $data)));
        }

        $query = "

            SELECT x1.*
                    ,cp.id
                    ,cp.contratos_id
                    ,cp.nu_parcela
                    
            FROM (SELECT LEFT(a.nosso_numero,length(a.nosso_numero)-1) * 1 as id_parcela,
                    a.nosso_numero,
                    a.dt_vencimento,
                    a.vl_boleto,
                    a.vl_juros,
                    a.vl_pago,
                    a.dt_credito,
                    a.fl_processado       
            FROM dados_arquivo_retorno a
            where arquivos_id in (SELECT id FROM arquivos
                                    where tp_arq = 'RETORNO'
                                    and date(dt_processamento) = '$data' )
            and id_ocorrencia = '06' ) x1
            left join contrato_parcelas as cp on cp.id = x1.id_parcela
            
            order by fl_processado desc

        ";

        return $conexao_BD_1->query($query);
    }

    function lista_saldo_cliente(&$conexao_BD_1, $datai=null, $dataf=null) {
        if($datai  == null) {
            $datai = date('Y-m-d');
        } else {
            $datai = date('Y-m-d', strtotime(str_replace('/', '-', $datai)));
        }

        if($dataf  == null) {
            $dataf = date('Y-m-d');
        } else {
            $dataf = date('Y-m-d', strtotime(str_replace('/', '-', $dataf)));
        }

        $query = "

            SELECT
                cliente_id,
                nome,
                mes,
                SUM(receber) receber,
                SUM(recebido) recebido,
                SUM(receber) + SUM(recebido) receber_total,
                SUM(pagar) pagar,
                SUM(pagar_avulso) pagar_avulso
            FROM
            (SELECT
                    DATE_FORMAT(dt_vencimento, '%Y-%m') AS mes,
                    SUM(vl_corrigido) AS receber,
                    0 as recebido,
                    0 AS pagar,
                    0 AS pagar_avulso,
                    c.vendedor_id as cliente_id
            FROM
                contrato_parcelas cp
            JOIN contratos c ON c.id = cp.contratos_id
            WHERE 1=1
                    AND c.status IN ('confirmado' , 'acao_judicial')
                    AND (dt_pagto IS NULL
                    OR dt_pagto = '0000-00-00')
                    AND (vl_pagto IS NULL OR vl_pagto = 0)
                    AND (dt_vencimento between '".$datai."' and '".$dataf."')
                    AND (c.suspenso = 'N' OR c.suspenso IS NULL)
            GROUP BY c.vendedor_id
            UNION SELECT
                    DATE_FORMAT(dt_credito, '%Y-%m') AS mes,
                0 as receber,
                    SUM(vl_pagto) AS recebido,
                    0 AS pagar,
                    0 AS pagar_avulso,
                    c.vendedor_id as cliente_id
            FROM
                contrato_parcelas cp
            JOIN contratos c ON c.id = cp.contratos_id
            WHERE 1=1
                    AND teds_id IS NULL
                    AND (dt_pagto IS NOT NULL
                    AND dt_pagto <> '0000-00-00'
                    AND (vl_pagto IS NOT NULL AND vl_pagto > 0))
                    AND (dt_credito between '".$datai."' and '".$dataf."')
                    AND (c.suspenso = 'N' OR c.suspenso IS NULL)
            GROUP BY c.vendedor_id
            
            UNION
            
            SELECT
                    DATE_FORMAT(dt_vencimento, '%Y-%m') AS mes,
                    0 AS receber,
                    0 AS recebido,
                    SUM(vl_corrigido) AS pagar,
                    0 AS pagar_avulso,
                    c.comprador_id as cliente_id
            
            FROM
                contrato_parcelas cp
            JOIN contratos c ON c.id = cp.contratos_id
            WHERE 1=1
                    AND c.status IN ('confirmado' , 'acao_judicial')
                    AND (dt_pagto IS NULL
                    OR dt_pagto = '0000-00-00')
                    AND (vl_pagto IS NULL OR vl_pagto = 0)
                    AND (dt_vencimento between '".$datai."' and '".$dataf."')
                    AND (c.suspenso = 'N' OR c.suspenso IS NULL)
            GROUP BY c.comprador_id
            
            UNION
            
            SELECT
                    DATE_FORMAT(dt_vencimento, '%Y-%m') AS mes,
                    0 AS receber,
                0 AS recebido,
                    0 AS pagar,
                    SUM(vl_corrigido) AS pagar_avulso,
                    c.pessoas_id as cliente_id
            FROM
                contrato_parcelas cp
            JOIN boletos_avulso c ON c.id = cp.boletos_avulso_id
            WHERE 1=1
                    AND (dt_credito IS NULL
                    OR dt_credito = '0000-00-00')
                    AND (vl_pagto IS NULL OR vl_pagto = 0)
                    AND (dt_vencimento between '".$datai."' and '".$dataf."')
            GROUP BY c.pessoas_id ) fluxo
            
            LEFT JOIN pessoas as pe on pe.id = fluxo.cliente_id   
            WHERE 1=1
            -- and cliente_id in (31, 3828)
            group by fluxo.cliente_id
            order by pe.id

        ";

        // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Query ' . json_encode($query));
        // echo $query;
        return $conexao_BD_1->query($query);
    }

    function lista_boletos_adimplencia(&$conexao_BD_1, $datai=null) {
        $dt_mes_atual = date('m');
        
        if($datai  == null) {
            $datai = date('Y-m-01');
        } else {
            $datai = date('Y-m-01', strtotime(str_replace('/', '-', $datai)));
        }

        $dataf = date('Y-m-t', strtotime(str_replace('/', '-', $datai)));

        $where = " and cp.dt_vencimento >= '".$datai."' and cp.dt_vencimento <= '".$dataf."' ";

        if($dt_mes_atual == date('m', strtotime(str_replace('/', '-', $datai))) ) {
            $dataf = date('Y-m-d');
            $where = " and cp.dt_vencimento >= '".$datai."' and cp.dt_vencimento < '".$dataf."' ";
        } 

        $query = "

        SELECT 
            cp.dt_vencimento as vencimento,
            count(*) as total,
            sum(case when (cp.vl_pagto > 0 or (not cp.dt_pagto = '0000-00-00' and not isnull(cp.dt_pagto))) then 1 else 0 end) as pago,
            sum(case when (vl_pagto > 0 or (not cp.dt_pagto = '0000-00-00' and not isnull(cp.dt_pagto))) then 0 else 1 end) as n_pago,
            SUM(case when (vl_pagto > 0 or (not cp.dt_pagto = '0000-00-00' and not isnull(cp.dt_pagto))) then 0 else 1 end) / count(*) * 100 as inadimplencia
            
        FROM contrato_parcelas as cp
        left join contratos as co on co.id = cp.contratos_id
        where 1=1
        " . $where . "
            and (co.suspenso = 'N' or co.suspenso is null)
            and (co.repasse = 'N' or co.repasse is null)
            and co.tp_contrato = 'adimplencia'
            and co.status = 'confirmado'
            and isnull(co.motivo_zerado)
            and isnull(cp.motivo_zerado)
        
        group by cp.dt_vencimento

        ";

        // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Query ' . json_encode($query));
        return $conexao_BD_1->query($query);
    }

}
?>
