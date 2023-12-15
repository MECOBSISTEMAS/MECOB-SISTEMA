<form id="form_filtros_alertas" action="javascript:filtrar_fields();" method="post">
    <div class="mg-bt-10 row mg-lf--5">

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
            <select name="filtro_status" id="filtro_status" class="form-control ">
                <option value="">Status</option>
                <!-- <option value="1">Não Lidos</option> -->
                <!-- <option value="2">Lidos</option> -->
                <option value="3">Concluídos</option>
                <option value="4">Atrasados</option>
            </select>
        </div>

        <div class=" fl-lf col-lg-2  col-sm-11 col-md-2 col-xs-11  mg-bt-3 pd-0-0-0-5">
            <button type="submit" class="btn btn-danger btn-sm fl-lf mg-lf-5 "><i class="fa fa-play"></i></button>
        </div>

    </div>
</form>