<form id="form_filtros_contratos" action="javascript:filtrar_fields();" method="post">
<div class="mg-bt-10 row mg-lf--5">

<div class=" input-group  input-group-sm fl-lf  col-lg-1 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_id" id="filtro_id" class="form-control" placeholder="Id"
<?php if(isset($_GET['id']) && is_numeric($_GET['id'])){ echo 'value="'.$_GET['id'].'"'; $ini_filtro=1;}?>
 >
</div> 

<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_data" id="filtro_data" class="form-control" placeholder="Data Inicial" >
</div> 


<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
    <input type="text" name="filtro_data_fim" id="filtro_data_fim" class="form-control" placeholder="Data Final" >
</div> 

<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_dia" id="filtro_dia" class="form-control" placeholder="Vencimento da parcela" >
</div> 



<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<select  name="filtro_pagto" id="filtro_pagto" class="form-control"  >
<option value=""> Parcelas</option>
<option value="atraso"
<?php if(isset($_GET['parcelas']) && $_GET['parcelas'] == 'em_atraso' ){ 
    echo 'selected="selected"'; $ini_filtro=1;
}?>> Em atraso</option>
<option value="aberto"> A vencer</option>
<option value="liquidado"> Liquidado</option>
<option value="negativada"> Negativada</option>
</select>

</div>  

<div class=" input-group  input-group-sm fl-lf  col-lg-3 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<select  name="filtro_status" id="filtro_status" class="form-control"  >
<option value=""> Status</option>
<option value="confirmado"
<?php if(isset($_GET['status']) && $_GET['status'] == 'confirmado' ){ 
    echo 'selected="selected"'; $ini_filtro=1;
}?>> Confirmado</option>
<option value="em_acordo"> Em Acordo</option>
<option value="em_acordo_vigente"> Em Acordo vigente</option>
<option value="parcialmente_em_acordo"> Parcialmente em Acordo</option>
<option value="pendente" 
<?php if(isset($_GET['status']) && $_GET['status'] == 'pendente' ){ 
    echo 'selected="selected"'; $ini_filtro=1;
}?>
> Pendente</option>
<option value="virou_inadimplente"> Virou Inadimplente</option>
<option value="acao_judicial"> Ação Judicial</option>
<option value="suspenso"> Suspenso</option>
<option value="repasse"> Repasse</option>
<option value="excluido"> Excluído</option>
</select>
</div>

<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
    <select  name="filtro_zerado" id="filtro_zerado" class="form-control"  >
        <option value=""> Motivo zerado </option>
        <option value="Pagamento direto para o cliente">Pagamento direto para o cliente</option>
        <option value="Abatimento de parcela">Abatimento de parcela</option>
        <option value="Cancelamento">Cancelamento</option>
        <option value="Outros">Outros</option>
    </select>
</div>

<div class=" input-group  input-group-sm fl-lf  col-lg-3 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_contrato" id="filtro_contrato" class="form-control" placeholder="Contrato" >
</div> 


<div class=" input-group  input-group-sm fl-lf  col-lg-3 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_vendedor" id="filtro_vendedor" class="form-control" placeholder="Vendedor (Nome, email ou documento)" >
</div> 

<div class=" input-group  input-group-sm fl-lf  col-lg-3 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_comprador" id="filtro_comprador" class="form-control" placeholder="Comprador (Nome, email ou documento)" >
</div> 

<div class=" input-group  input-group-sm fl-lf  col-lg-4 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
    <input type="text" name="filtro_evento" id="filtro_evento" class="form-control" placeholder="Evento">
</div>


<div class=" fl-lf col-lg-3  col-sm-11 col-md-2 col-xs-11  mg-bt-3 pd-0-0-0-5">
<button type="button" class="btn btn-warning btn-sm  fl-lf" onclick="limpa_filtros();"><i class="fa fa-history"></i></button>
<button type="submit" class="btn btn-danger btn-sm fl-lf mg-lf-5 " ><i class="fa fa-play"></i></button>
<button type="button" class="btn btn-success btn-sm mg-lf-3 fl-lf " onclick="gerar_planilha_lista()"  rel="tooltip" data-placement="bottom" data-html="true" data-original-title="Gerar Planilha" >
<i class="fa fa-file-excel-o" aria-hidden="true"></i>
</button>
<button type="button" class="btn btn-info btn-sm mg-lf-3 fl-lf " onclick="gerar_pdf_lista()"  rel="tooltip" data-placement="bottom" data-html="true" data-original-title="Gerar PDF" >
<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
</button>
<button type="button" class="btn   btn-sm mg-lf-3 fl-lf " onclick="gerar_planilha_vendedores()"  rel="tooltip" data-placement="bottom" data-html="true" data-original-title="Gerar Planilha"  style="background-color:#FF0; color:#999">
<i class="fa fa-star" aria-hidden="true"></i>
</button>
</div>

</div>
</form>