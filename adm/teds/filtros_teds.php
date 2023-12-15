<form id="form_filtros_teds" action="javascript:filtrar_fields();" method="post">
<div class="mg-bt-10 row mg-lf--5">

<div class=" input-group  input-group-sm fl-lf  col-lg-1 pd-lf-5 col-sm-11 col-md-2 col-xs-11   mg-bt-3">
<input type="text" name="filtro_id" id="filtro_id"    class="form-control " placeholder="Id TED" 
<?php if(isset($_GET['id']) && is_numeric($_GET['id'])){ echo 'value="'.$_GET['id'].'"'; $ini_filtro=1;}?>
>
</div> 


<div class=" input-group  input-group-sm fl-lf col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_per_ini" id="filtro_per_ini" class="form-control  " placeholder="Agendamento Inicio">
<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
</div>

<div class=" input-group  input-group-sm fl-lf col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_per_fim" id="filtro_per_fim" class="form-control  " placeholder="Agendamento Final">
<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
</div>


<div class=" input-group  input-group-sm fl-lf  col-lg-3 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<select name="filtro_status" id="filtro_status"    class="form-control " >
<option value="">Status TED</option>
<option value="1">Aguardando Envio p/ banco</option>
<option value="2">Agendada</option>
<option value="3">Confirmada</option>
<option value="4">Corrompida</option>
</select>
</div> 


<div class=" input-group  input-group-sm fl-lf col-lg-2   pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_dt_inclusao" id="filtro_dt_inclusao" class="form-control  " placeholder="Data Inclusão">
<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
</div>

<?php if(!$ehCliente){   ?>
<div class=" input-group  input-group-sm fl-lf  col-lg-6 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_vendedor" id="filtro_vendedor"    class="form-control " placeholder="Vendedor" >
</div>
<?php } ?> 


<div class=" fl-lf col-lg-2  col-sm-11 col-md-2 col-xs-11  mg-bt-3 pd-0-0-0-5">
<button type="button" class="btn btn-warning btn-sm  fl-lf" onclick="limpa_filtros();"><i class="fa fa-history"></i></button>
<button type="submit" class="btn btn-danger btn-sm fl-lf mg-lf-5 " ><i class="fa fa-play"></i></button>
<button type="button" class="btn btn-success btn-sm mg-lf-3 fl-lf hidden" onclick="gerar_pdf_lista()"  rel="tooltip" data-placement="bottom" data-html="true" data-original-title="Gerar Planilha" >
<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
</button>
</div>

</div>
</form>