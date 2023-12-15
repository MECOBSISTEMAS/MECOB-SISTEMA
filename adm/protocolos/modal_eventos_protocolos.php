<!-- modal ocorrências (eventos) protocolos -->
<div class="modal fade" id="md_ocorrencias" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog wd-90p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="md_ocorrencias_tt"></h4>

            </div>

            <div class="modal-body">
                <div class="panel panel-bordo">
                    <div class="panel-body pan">
                        <div id="md_ocorrencias_add" class="pd-lf-15">
                            <h2 id="ocorrencia_header" class="mg-tp-0">Ocorrências</h2>

                            <button id="btnAddOcor" type="button" class="btn btn-sm btn-success"
                                onclick="nova_ocorrencia();"
                                style="    position: absolute;  right: 25px;    top: 25px;"><i class="fa fa-plus"></i>
                                Nova
                            </button>

                            <div id="divAddOcor" class="hidden">
                                <form id="form_ocorrencia" action="javascript:salvar_ocorrencia()">
                                    <input type="hidden" id="OcorProtocolosID" name="protocolos_id" />
                                    <input type="hidden" id="OcorProtocolos" name="protocolos" />
                                    <input type="hidden" id="OcorUsuario" name="pessoas_id" />
                                    <input type="hidden" id="OcorSetor" name="setor" />

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group input-icon right">
                                                <textarea id="OcorMensagem" name="ocorrencia"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="cancela_ocorrencia();">Cancelar</button>
                                    <button id="btn-save-ocor" type="submit"
                                        class="btn btn-sm btn-success">Salvar</button>


                                </form>


                            </div>

                        </div>
                        <div class="panel panel-bordo">
                        <div class="panel-body pan">
                            <!-- Lista as ocorrências -->
                            <div id="md_ocorrencias_bd" class="pd-lf-15 mg-tp-50"></div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
function mostra_ocorrencias(protocolos_id, protocolo, setor) {
    // Pega o usuário
    var usuario = "<?php echo $_SESSION['id']; ?>";

    $.getJSON(
        '<?php echo $link . "/repositories/protocolos/protocolos_ocorrencias.ctrl.php?acao=lista_ocorrencias"; ?>&inicial=' +
        exibidos, {
            protocolos_id: protocolos_id,
            ajax: 'true'
        },
        function(j) {

            /////    OCORRÊNCIAS
            ocorrencias = j.ocorrencias;
            // alert(JSON.stringify(ocorrencias));
            // alert(JSON.stringify(j.status));

            html_ocorrencias = "";
            for (var i = 0; i < ocorrencias.length; i++) {

                html_ocorrencias += "<hr>";
                html_ocorrencias += "<h4><div class='row'>";
                html_ocorrencias += "<div class='col-sm-6'>";
                html_ocorrencias += "Setor: " + ocorrencias[i].setor;
                html_ocorrencias += "</div>";
                html_ocorrencias += "<div class='col-sm-6 ar'>";
                html_ocorrencias += "Usuario: " + ocorrencias[i].nome;
                html_ocorrencias += "</div>";
                html_ocorrencias += "</div></h4>";
                html_ocorrencias += "<div class='ocor_msg'>";
                html_ocorrencias += ConverteData(ocorrencias[i].data);

                html_ocorrencias += "<br>" + ocorrencias[i].ocorrencia;
                html_ocorrencias += "</div>";
            }
            if (html_ocorrencias == "") {
                html_ocorrencias = "<h4>Nenhuma ocorrência cadastrada</h4>";
            }

            // Lista as ocorrências
            $('#md_ocorrencias_bd').html(html_ocorrencias);

            // Atualiza os dados do modal
            $("#OcorProtocolosID").val(protocolos_id);
            $("#OcorProtocolos").val(protocolo);
            $("#OcorUsuario").val(usuario);
            $("#OcorSetor").val(setor);

            $("#ocorrencia_header").text('Ocorrências do protocolo ' + protocolo);

            // Abre o modal
            $('#md_ocorrencias').modal('show');

        });

}

function nova_ocorrencia() {
    $('#OcorMensagem').val('');
    CKEDITOR.instances.OcorMensagem.setData('');
    $('#divAddOcor').removeClass('hidden');
    $('#btnAddOcor').addClass('hidden');

}

function cancela_ocorrencia() {
    $('#OcorMensagem').val('');
    CKEDITOR.instances.OcorMensagem.setData('');
    $('#divAddOcor').addClass('hidden');
    $('#btnAddOcor').removeClass('hidden');

}

function salvar_ocorrencia() {
    msg = document.getElementById('OcorMensagem').value.replace(/(^\s+|\s+$)/g, ' ');
    // console.log('MSG ' + msg);
    if (msg == null || msg == '') {
        jAlert("Preencha a mensagem/observação.");
    } else {
        $('#btn-save-ocor').addClass('hidden');
        ocorrencia = $('#form_ocorrencia').serializeArray();
        // alert(JSON.stringify(ocorrencia));
        $.getJSON("<?php echo $link . "/repositories/protocolos/protocolos_ocorrencias.ctrl.php?acao=inserir"; ?>", {
            ocorrencias: ocorrencia
        }, function(result) {

            if (result.status > 0) {
                //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
                cancela_ocorrencia();
                $('#btn-save-ocor').removeClass('hidden');
                carregar_resultados();
                mostra_ocorrencias($('#OcorProtocolosID').val(), $('#OcorProtocolos').val(), $('#OcorSetor').val());
                jAlert(result.msg, 'Bom trabalho!', 'ok');
            } else {
                $('#btn-save-ocor').removeClass('hidden');
                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
            }
        });
    }
}

</script>