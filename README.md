README sistema.mecob.com.br
====================

# .htaccess
    SetEnv CAMINHO_RAIZ /home/pedro/projetos/mecob
    SetEnv CAMINHO_SITE http://mecob.how
    SetEnv MYSQL_DB dev
    SetEnv SERVER_NAME mecob.how

    IndexIgnore *

    RewriteEngine On

    RewriteRule ^sair acesso.php?logout [NC,L]

    RewriteRule ^download/?([=a-zA-Z0-9_-]+)\/?$ inc/download.php?file=$1 [NC,L]

    RewriteRule ^dashboard_boletos adm/dashboard_boletos.php [NC,L]
    RewriteRule ^dashboard adm/dashboard.php [NC,L]

    RewriteRule ^controle_acesso   adm/controle_acesso/lista_controle.php [NC,L]
    RewriteRule ^auditoria   	   adm/acesso_pessoas/lista_acessos.php [NC,L]

    RewriteRule ^usuarios    adm/pessoas/lista_pessoas.php [NC,L]
    RewriteRule ^leiloeiros  adm/pessoas/lista_pessoas.php [NC,L]
    RewriteRule ^compradores adm/pessoas/lista_pessoas.php [NC,L]
    RewriteRule ^vendedores  adm/pessoas/lista_pessoas.php [NC,L]
    RewriteRule ^usuarios    adm/pessoas/lista_pessoas.php [NC,L]

    RewriteRule ^haras 		 adm/haras/lista_haras.php [NC,L]
    RewriteRule ^lotes 		 adm/lotes/lista_lotes.php [NC,L]
    RewriteRule ^eventos 	 adm/eventos/lista_eventos.php [NC,L]
    RewriteRule ^contratos/pendentes 	 adm/contratos/lista_contratos.php?status=pendente [NC,L]
    RewriteRule ^contratos/?([0-9]+)\/?$ 	 adm/contratos/lista_contratos.php?id=$1 [NC,L]
    RewriteRule ^contratos_analitico 	 adm/contratos_analitico/lista_contratos.php [NC,L]
    RewriteRule ^contratos 	 adm/contratos/lista_contratos.php [NC,L]

    RewriteRule ^boletos_avulso 		 adm/boletos_avulso/lista_boletos_avulso.php [NC,L]

    RewriteRule ^arquivos 	 adm/arquivos/lista_arquivos.php [NC,L]


    RewriteRule ^parcelas/custodia$ 		 adm/parcelas/lista_parcelas.php?custodia  [NC,L]
    RewriteRule ^parcelas/liquidados_hoje$ 	 adm/parcelas/lista_parcelas.php?liquidados_hoje  [NC,L]
    RewriteRule ^parcelas/vencidos$ 		 adm/parcelas/lista_parcelas.php?vencidos  [NC,L]
    RewriteRule ^parcelas/vencidos_ontem$	 adm/parcelas/lista_parcelas.php?vencidos_ontem [NC,L]
    RewriteRule ^parcelas/?([0-9]+)\/?$ 	 adm/parcelas/lista_parcelas.php?ted_id=$1 [NC,L]
    RewriteRule ^parcelas 	 adm/parcelas/lista_parcelas.php [NC,L]

    RewriteRule ^teds/?([0-9]+)\/?$ 	 adm/teds/lista_teds.php?id=$1 [NC,L]
    RewriteRule ^teds 		 adm/teds/lista_teds.php [NC,L]

    RewriteRule ^domicilios 		 adm/domicilios/lista_domicilios.php [NC,L]

    RewriteRule ^segunda_via/?([0-9]+)\/?$  adm/pessoas/segunda_via.php?cpf=$1 [NC,L]
    RewriteRule ^segunda_via 				adm/pessoas/segunda_via.php [NC,L]

    RewriteRule ^segundavia/?([0-9]+)\/?$  adm/pessoas/segunda_via.php?cpf=$1 [NC,L]
    RewriteRule ^segundavia 				adm/pessoas/segunda_via.php [NC,L]


    RewriteRule ^pessoa/compras/?([0-9]+)\/?$ adm/pessoas/view_pessoa.php?id=$1&compra [NC,L]
    RewriteRule ^pessoa/vendas/?([0-9]+)\/?$ adm/pessoas/view_pessoa.php?id=$1&venda [NC,L]
    RewriteRule ^pessoa/pendentes/?([0-9]+)\/?$ adm/pessoas/view_pessoa.php?id=$1&pendente [NC,L]

    RewriteRule ^pessoa_compra/?([0-9]+)\/contrato/?([0-9]+)\/?$ adm/pessoas/view_pessoa.php?id=$1&compra&contrato=$2 [NC,L]
    RewriteRule ^pessoa_compra/?([0-9]+)\/?$ adm/pessoas/view_pessoa.php?id=$1&compra [NC,L]
    RewriteRule ^pessoa_venda/?([0-9]+)\/?$ adm/pessoas/view_pessoa.php?id=$1&venda [NC,L]
    RewriteRule ^pessoa_ted/?([0-9]+)\/?$ adm/pessoas/view_pessoa.php?id=$1&ted [NC,L]
    RewriteRule ^pessoa/?([0-9]+)\/?$ adm/pessoas/view_pessoa.php?id=$1 [NC,L]

    RewriteRule ^gerar_boletos/?([0-9]+) inc/boleto/gerar_boletos.php?id=$1 [NC,L]

    RewriteRule ^404 404.php [NC,L]
    RewriteRule ^401 401.php [NC,L]

    ErrorDocument 404 /404
    ErrorDocument 401 /401
