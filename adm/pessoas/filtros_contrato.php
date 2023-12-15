<form action='' class="form_filtros_contratos" method="post">
    <div class="mg-bt-10 row mg-lf--5">


        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_data" class="form-control filtro_ct_data" placeholder="Data Contrato"
                <?php if(isset($_POST["filtro_data"])){echo 'value="'.$_POST["filtro_data"].'"';} ?>>
        </div>

        <!--<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_data_fim"  class="form-control filtro_ct_data" placeholder="Data Final" >
</div> -->



        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11 mg-bt-3 hidden ">
            <select name="filtro_status" class="form-control">
                <option value=""> Status</option>
                <option value="confirmado" <?php if(isset($_POST["filtro_status"]) && $_POST["filtro_status"] == 'confirmado'){
								echo 'selected="selected"';} ?>> Confirmado</option>
                <option value="em_acordo" <?php if(isset($_POST["filtro_status"]) && $_POST["filtro_status"] == 'em_acordo'){
								echo 'selected="selected"';} ?>> Em Acordo</option>
                <option value="em_acordo_vigente" <?php if(isset($_POST["filtro_status"]) && $_POST["filtro_status"] == 'em_acordo_vigente'){
								echo 'selected="selected"';} ?>> Em Acordo vigente</option>
                <option value="parcialmente_em_acordo" <?php if(isset($_POST["filtro_status"]) && $_POST["filtro_status"] == 'parcialmente_em_acordo'){
								echo 'selected="selected"';} ?>> Parcialmente em Acordo</option>
                <option value="pendente" <?php if(isset($_POST["filtro_status"]) && $_POST["filtro_status"] == 'pendente'){
								echo 'selected="selected"';} ?>> Pendente</option>
                <option value="virou_inadimplente" <?php if(isset($_POST["filtro_status"]) && $_POST["filtro_status"] == 'virou_inadimplente'){
								echo 'selected="selected"';} ?>> Virou Inadimplente</option>
            </select>
        </div>


        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_evento" class="form-control" placeholder="Evento"
                <?php if(isset($_POST["filtro_evento"])){echo 'value="'.$_POST["filtro_evento"].'"';} ?>>
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_contrato" class="form-control" placeholder="Contrato"
                <?php if(isset($_POST["filtro_contrato"])){echo 'value="'.$_POST["filtro_contrato"].'"';} ?>>
        </div>

        <?php if($cfg_filtros == 'compras'){ ?>
        <div class=" input-group  input-group-sm fl-lf  col-lg-3 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_vendedor" class="form-control"
                placeholder="Vendedor (Nome, animal, email ou documento)"
                <?php if(isset($_POST["filtro_vendedor"])){echo 'value="'.$_POST["filtro_vendedor"].'"';} ?>>
        </div>
        <?php 
}
if($cfg_filtros != 'compras'){ ?>
        <div class=" input-group  input-group-sm fl-lf  col-lg-3 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_comprador" class="form-control"
                placeholder="Comprador (Nome, animal, email ou documento)"
                <?php if(isset($_POST["filtro_comprador"])){echo 'value="'.$_POST["filtro_comprador"].'"';} ?>>
        </div>
        <?php } ?>



        <div class=" fl-lf col-lg-1  col-sm-11 col-md-2 col-xs-11  mg-bt-3 pd-0-0-0-5">
            <a href="<?php echo getenv('CAMINHO_SITE')."/pessoa/".$cfg_filtros."/".$id; ?>"
                class="btn btn-warning btn-sm  fl-lf"><i class="fa fa-history"></i></a>
            <button type="submit" class="btn btn-danger btn-sm fl-lf mg-lf-5 "><i class="fa fa-play"></i></button>
        </div>

    </div>
</form>
<br />