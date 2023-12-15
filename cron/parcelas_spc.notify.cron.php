<?php 
include_once("cron_config.php");
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
$cron = true;
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/util.php");

include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");
$contratosDB  = new contratosDB();

include_once(getenv('CAMINHO_RAIZ')."/repositories/ocorrencias/ocorrencias.class.php");
$ocorrencias  = new ocorrencias();

$contratos = $contratosDB->get_contratos_inclusao_spc($conexao_BD_1);

// date(w) = 0 -> Domingo
// date(w) = 1 -> Segunda
// date(w) = 6 -> Sábado
if (!retornaFeriados('2019-01-01') && date('w') != 0 && date('w') != 6 && date('w') != 1){
    foreach ($contratos as $key => $value) {
        try {
            
            $email_dest = $value['email'];
            // $email_dest = 'pedro.arua@gmail.com';
            $nome_dest = $value['nome'];
            $assunto = "Aviso de inclusão no SPC";

            $valor_parcelas = explode(',',$value['valor_parcelas']);
            $vencimento_parcelas = explode(',',$value['vencimento_parcelas']);

            $qtd_parcelas = count($valor_parcelas);
            
            if ( $qtd_parcelas === 1 )
                $escrito_parcela = 'parcela, que segue';
            else
                $escrito_parcela = 'parcelas, que seguem';
            
            $linha_parcela = "
                <tr>
                    <th style='width:50%;'>Vencimento</th>
                    <th style='width:50%;'>Valor</th>
                </tr>
            ";
            for ($i=0; $i < $qtd_parcelas; $i++) { 
                $vencimento_parcela = $vencimento_parcelas[$i];
                $valor_parcela = $valor_parcelas[$i];
                $linha_parcela .= "
                <tr>
                    <td>$vencimento_parcela</td>
                    <td>R$ $valor_parcela</td>
                </tr>
                ";
            }

            $mensagem = "
                Olá, $nome_dest, encontra-se em atraso a mais de 5 dias em nosso sistema, $qtd_parcelas $escrito_parcela abaixo:<br><br>
                <table border='1' cellpading='0' cellspacing='0' style='width:100%;'>
                    $linha_parcela
                </table>
                <br><br>
                <span style='color:#f00'>Por este motivo, seu CPF foi incluído nos órgãos de proteção ao crédito.</span><br>
                Favor regularizar a dívida o quanto antes, para que seu nome seja regularizado novamente.
                <br><br>
                <small>Caso já tenha efetuado o pagamento, por favor, desconsidere este e-mail, e entre em contato conosco para que possamos regularizar seu cadastro</small>
            ";
            // include "$raiz/inc/mail/inc_mail.php";

            $usuario_sistema = 3870;
            $ocorrencias->status = 'Aviso SPC';
            $ocorrencias->mensagem = 'Enviado e-mail de aviso sobre parcelas em atraso';
            $ocorrencias->pessoas_id = $usuario_sistema;
            $ocorrencias->contratos_id = $value['id'];
            $ocorrencias->data_ocorrencia = date('Y-m-d H:i:s');
            $conexao_BD_1->insert($ocorrencias);
            echo "email enviado; ocorrência cadastrada\n";
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
} else {
  switch (date('w')) {
    case 0:
        echo "email não enviado, pois hoje é Domingo";
        break;
    case 6:
        echo "email não enviado, pois hoje é Sábado";
        break;
    case 1:
        echo "email não enviado, pois hoje é Segunda-feira";
        break;

    default:
        echo "email não enviado, pois hoje é Feriado";
        break;
  }  
}

?>