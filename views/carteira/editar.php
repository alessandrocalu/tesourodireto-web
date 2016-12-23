<?php echo form_open_multipart('carteira/editar_post', array( 'class' => 'form-horizontal', 'onsubmit' => "$(this).ajaxSubmit({dataType : 'json', success: processModalSubmit}); return false;")); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Editar registro de compra de Título</h4>
        </div>
        <div class="modal-body">
            <div class="row-fluid sortable">
                <div class="col-lg-6">
                    <?php echo validation_errors(); ?>
                    <input type="hidden" id="id" name="id" value="<?=$details['id'];?>" />
                    <div class="control-group">
                        <label class="control-label" for="titulo_id">Título</label>
                        <div class="controls">
                            <select id="titulo_id" name="titulo_id" data-rel="chosen" required>
                            <?php foreach ($titutos as $titulo) { ?>
                                <option value="<?php echo $titulo["id"]; ?>" <?php echo ($titulo["id"] == $details['titulo_id']?"selected":""); ?> ><?php echo $titulo["nome"]; ?></option>
                            <?php  } ?>    
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dt_atualizacao">Data compra</label>
                        <div class="controls">
                            <input type="text" class="input datepicker" name="dt_compra" id="dt_compra" value="<?=date("d/m/Y H:i",strtotime($details['dt_compra']));?>" required>
                            <button  id="pesq-cotacao" class="btn" type="button">Pesquisar Cotação</button>
                            <div id="div-message-cotacao" class="alert alert-error">
                                <button id="btn-message-cotacao" type="button" class="close">×</button>
                                <nobr id="message-cotacao"></nobr>
                            </div>    
                        </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="preco_compra">Quantidade</label>
                        <div class="controls">
                            <input id="prependedInput" name="quantidade" id="quantidade" min="0" max="100000" size="10" type="number" step="any" value="<?=$details['quantidade'];?>" required>
                        </div>    
                    </div>

                    <div class="input-prepend">
                        <label class="control-label" for="preco_compra">Preço Compra</label>
                        <div class="controls">
                            <span class="add-on">R$</span>
                            <input id="preco_compra" name="preco_compra" min="0" max="100000" size="10" type="number" step="any" value="<?=$details['preco_compra'];?>" required>
                        </div>    
                    </div>

                    <div class="input-prepend">
                        <label class="control-label" for="tx_pactuada">Taxa Pactuada</label>
                        <div class="controls">
                            <span class="add-on">%</span>
                            <input id="prependedInput" name="tx_pactuada" id="tx_pactuada" min="0" max="100" size="5" type="number" value="<?=$details['tx_pactuada'];?>" step="any">
                        </div>    
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="tx_bvmf">Taxa BVMF</label>
                        <div class="controls">
                            <span class="add-on">%</span>
                            <input id="prependedInput" name="tx_bvmf" id="tx_bvmf" min="0" max="100" size="5" type="number" value="<?=$details['tx_bvmf'];?>" step="any">
                        </div>    
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="tx_corretora">Taxa Corretora</label>
                        <div class="controls">
                            <span class="add-on">%</span>
                            <input id="prependedInput" name="tx_corretora" id="tx_corretora" min="0" max="100" size="5" type="number" value="<?=$details['tx_corretora'];?>" step="any">
                        </div>    
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="tx_iof">Taxa IOF</label>
                        <div class="controls">
                            <span class="add-on">%</span>
                            <input id="prependedInput" name="tx_iof" id="tx_iof" min="0" max="100" size="5" type="number" value="<?=$details['tx_iof'];?>" step="any">
                        </div>    
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="tx_ir">Taxa IR</label>
                        <div class="controls">
                            <span class="add-on">%</span>
                            <input id="prependedInput" name="tx_ir" id="tx_ir" min="0" max="100" size="5" type="number" value="<?=$details['tx_ir'];?>" step="any">
                        </div>    
                    </div>
                </div>
                <!-- /.col-lg-6 (nested) -->
            </div>
        </div>
        <div class="modal-footer">
        	<p class="result-message pull-left"></p>&nbsp;<button type="submit" class="btn btn-primary">Salvar dados</button>
            <a class="btn btn-danger" data-dismiss="modal">Fechar</a>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
</form>

<script type="text/javascript">
    $('#dt_compra').datetimepicker({
        lang: 'pt',
        mask:'39/19/9999 29:59',
        format:'d/m/Y H:i',
        formatTime: 'H:i',
        formatDate: 'd/m/Y',
        step: 60
    });
    
    $('[data-rel="chosen"],[rel="chosen"]').chosen();

    $('#div-message-cotacao').hide();


    $('#pesq-cotacao').on("click", function(){

        var titulo_id = $('#titulo_id').attr("value");
        var data = $('#dt_compra').attr("value");

        if ((titulo_id > 0) && (data != "")){
            var parametro = $.param({
                titulo_id: titulo_id,
                data: data
            });

            $.get("<?php echo base_url('index.php/cotacoes/getcotacaoapi/'); ?>?"+parametro, function(result) {
                if (result.indexOf("Acesso") >= 0 ){
                    $('#message-cotacao').html(result);
                    $('#div-message-cotacao').show();
                }else if (result.indexOf("Cotação") >= 0 ){
                    $('#message-cotacao').html(result);
                    $('#div-message-cotacao').show();
                } else {
                    $('#preco_compra').val(result);
                }
            }).fail(function (){
                $('#message-cotacao').html('Falha em comunicação com servidor!');
                $('#div-message-cotacao').show();
            });
        }
    });

    $('#btn-message-cotacao').on("click", function (){
        $('#div-message-cotacao').hide();    
    });
</script>