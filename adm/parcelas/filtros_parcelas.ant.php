<form id="form_filtros_parcelas" action="javascript:filtrar_fields();" method="post">
    <div class="mg-bt-10 row mg-lf--5">

        <div class=" input-group  input-group-sm fl-lf  col-lg-1 pd-lf-5 col-sm-11 col-md-2 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_contrato_id" id="filtro_contrato_id" class="form-control "
                placeholder="Id Contrato">
        </div>

        <div class=" input-group  input-group-sm fl-lf col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_per_ini" id="filtro_per_ini" class="form-control  "
                placeholder="Período Inicio" <?php  if(isset($_GET['vencidos_ontem'])){
	$data_atual = date("Y-m-d");
	$data_prox = new DateTime($data_atual);
	$data_prox->sub(new DateInterval("P".abs(1)."D"));
	$data_final = $data_prox->format('d/m/Y'); 
	echo 'value="'.$data_final.'"'; $ini_filtro=1;
}
elseif(isset($_GET['liquidados_hoje'])){
	echo 'value="'.date("d/m/Y").'"'; $ini_filtro=1;
}

?>>
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>

        <div class=" input-group  input-group-sm fl-lf col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_per_fim" id="filtro_per_fim" class="form-control  "
                placeholder="Período Final">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_dia" id="filtro_dia" class="form-control"
                placeholder="Vencimento da parcela">
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <select name="filtro_status" id="filtro_status" class="form-control ">
                <option value="">Status Parcela</option>
                <option value="1" <?php if(isset($_GET['vencidos_ontem']) || isset($_GET['vencidos'])){
	echo 'selected="selected"'; $ini_filtro=1;
} ?>>Atrasada</option>
                <option value="2" <?php if(isset($_GET['custodia'])){
	echo 'selected="selected"'; $ini_filtro=1;
} ?>>A vencer</option>
                <option value="3" <?php if(isset($_GET['liquidados_hoje'])){
	echo 'selected="selected"'; $ini_filtro=1;
} ?>>Liquidada</option>
                <option value="4">TED Realizada</option>
                <option value="5">Negativada</option>
                <option value="6">Inserir no SPC</option>
                <option value="7">Retirar do SPC</option>
            </select>
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-2 col-xs-11   mg-bt-3">
            <select name="filtro_tpcontrato" id="filtro_tpcontrato" class="form-control ">
                <option value="">Tipo Contrato</option>
                <option value="adimplencia" <?php if(isset($_GET['vencidos_ontem']) || isset($_GET['vencidos'])  || isset($_GET['custodia']) ){	echo 'selected="selected"';} ?>>Adimplência</option>
                <option value="inadimplencia">Inadimplência</option>
                <option value="repasse">Repasse</option>
            </select>
        </div>
        <?php if($ehCliente){   ?>
            <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-2 col-xs-11   mg-bt-3">
                <select name="tipo_operacao" id="tipo_operacao" class="form-control ">
                    <option value="compra" selected>Compra</option>
                    <option value="venda">Venda</option>
                </select>
            </div>
        <?php }?>

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <select name="filtro_status_ct" id="filtro_status_ct" class="form-control">
                <option value=""> Status Contrato</option>
                <option value="confirmado"> Confirmado</option>
                <option value="em_acordo"> Em Acordo</option>
                <option value="em_acordo_vigente"> Em Acordo vigente</option>
                <option value="parcialmente_em_acordo"> Parcialmente em Acordo</option>
                <option value="pendente"> Pendente</option>
                <option value="virou_inadimplente"> Virou Inadimplente</option>
                <option value="acao_judicial"> Ação Judicial</option>
                <option value="confirmado,em_acordo,parcialmente_em_acordo" <?php if(isset($_GET['vencidos_ontem']) || isset($_GET['vencidos'])  || isset($_GET['custodia']) ){
	echo 'selected="selected"'; $ini_filtro=1;
} ?>> Confirmado + Em Acordol + Parcialmente em Acordo</option>
            </select>
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-4 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3
<?php if($ehCliente){  echo 'hidden'; }  ?>
">
            <input type="text" name="filtro_vendedor" id="filtro_vendedor" class="form-control"
                placeholder="Vendedor (Nome, email ou documento)"
                <?php if($ehCliente){  echo 'value="'.$user_id.'"'; }  ?>>
        </div>


        <div class=" input-group  input-group-sm fl-lf  col-lg-4 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3"
        <?php if($ehCliente){  echo 'hidden'; }  ?> >
            <input type="text" name="filtro_comprador" id="filtro_comprador" class="form-control"
                placeholder="Comprador (Nome, email ou documento)">
        </div>


        <div class=" input-group  input-group-sm fl-lf  col-lg-1 pd-lf-5 col-sm-11 col-md-2 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_ted_id" id="filtro_ted_id" class="form-control " placeholder="Id TED"
                <?php if(isset($_GET['ted_id']) && is_numeric($_GET['ted_id'])){ echo 'value="'.$_GET['ted_id'].'"'; $ini_filtro=1;}?>>
        </div>


        <div class=" fl-lf col-lg-2  col-sm-11 col-md-2 col-xs-11  mg-bt-3 pd-0-0-0-5">
            <button type="button" class="btn btn-warning btn-sm  fl-lf" onclick="limpa_filtros();"><i
                    class="fa fa-history"></i></button>
            <button type="submit" class="btn btn-danger btn-sm fl-lf mg-lf-5 "><i class="fa fa-play"></i></button>
            <button type="button" class="btn btn-info btn-sm mg-lf-3 fl-lf " onclick="gerar_pdf_lista()" rel="tooltip"
                data-placement="bottom" data-html="true" data-original-title="Gerar PDF">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            </button>
        </div>

    </div>
</form> 