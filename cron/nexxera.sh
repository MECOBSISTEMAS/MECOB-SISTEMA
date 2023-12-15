#!/bin/bash
#
# ##############################
# Script para envio e recebimento 
# dos arquivos via Nexxera
# ##############################
DirCron='/home/mecob/sistema.mecob.com.br/cron'
DirINBOX='/home/skyunix/INBOX'
DirRetorno='/home/mecob/sistema.mecob.com.br/boletos/retorno/a_importar'

data=$(date +%d/%m/%Y)
# executa a cópia dos arquivos de remessa para o OUTBOX do skyline 
result=$(/usr/bin/php $DirCron/nexxera_busca_remessa.php)

echo "Remessa do dia $data"
echo "$result"
echo "Fim do envio das remessas..."

echo " "

# Executa o Skyline para transferir os arquivos de remessa e buscar os arquivos de retorno
echo "Executando o Nexxera Skyline."

# Chama o Skyline
/home/skyunix/skyunix7.4-64bits /SE=motta19 /Q

echo "Fim Nexxera Skyline."
echo " "

# Move os arquivos recebidos pelo skyline para a pasta de importação do MECOB
echo "Movendo arquivos para importacao"

/bin/mv -f $DirINBOX/*.RET $DirRetorno 2>/dev/null
/bin/mv -f $DirINBOX/*.ret $DirRetorno 2>/dev/null

if [ $? -eq 0 ] 
then
    # Processa todos os arquivos recebidos
    echo "Iniciando processamento dos arquivos de retorno..."
    retorno=$(/usr/bin/php $DirCron/nexxera_processa_retorno.php)
    echo "Fim do processamento dos arquivos de retorno..."
else 
    echo "Não havia nenhum arquivo na pasta INBOX..."
fi

### mail -s "Nexxera envio" olavo@mecob.com.br <<< `cat /home/skyunix/SESSION.LOG`
exit 0
