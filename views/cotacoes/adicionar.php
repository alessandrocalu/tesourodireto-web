<?php echo form_open_multipart('cotacoes/adicionar_post', array( 'class' => 'form-horizontal', 'onsubmit' => "$(this).ajaxSubmit({dataType : 'json', success: processModalSubmit}); return false;")); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Adicionar cotação de Título - <?php echo $nomeTitulo; ?></h4>
        </div>
        <div class="modal-body">
            <div class="row-fluid sortable">
                <div class="col-lg-6">
                    <?php echo validation_errors(); ?>
                    <div class="control-group">
                        <label class="control-label" for="dt_vencimento">Vencimento</label>
                        <div class="controls">
                            <input type="text" class="input datepicker" name="dt_vencimento" id="dt_vencimento" value="<?php echo date("d/m/Y");?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dt_atualizacao">Dt.Atualização</label>
                        <div class="controls">
                            <input type="text" class="input datepicker" name="dt_atualizacao" id="dt_atualizacao" value="<?php echo date("d/m/Y H:i");?>" required>
                        </div>
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="tx_compra">Taxa Compra (a.a)</label>
                        <div class="controls">
                            <span class="add-on">%</span>
                            <input id="prependedInput" name="tx_compra" id="tx_compra" min="0" max="100" size="5" type="number" step="any">
                        </div>    
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="tx_venda">Taxa Venda (a.a)</label>
                        <div class="controls">
                            <span class="add-on">%</span>
                            <input id="prependedInput" name="tx_venda" id="tx_venda" min="0" max="100" size="5" type="number" step="any">
                        </div>    
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="preco_compra">Preço Compra</label>
                        <div class="controls">
                            <span class="add-on">R$</span>
                            <input id="prependedInput" name="preco_compra" id="preco_compra" min="0" max="100000" size="10" type="number" step="any">
                        </div>    
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="preco_venda">Preço Venda</label>
                        <div class="controls">
                            <span class="add-on">R$</span>
                            <input id="prependedInput" name="preco_venda" id="preco_venda" min="0" max="100000" size="10" type="number" step="any">
                        </div>    
                    </div>
                </div>
                <!-- /.col-lg-6 (nested) -->
            </div>
        </div>
        <div class="modal-footer">
        	<input type="hidden" id="titulo_id" name="titulo_id" value="<?=$titulo_id;?>" />
            <p class="result-message pull-left"></p>&nbsp;<button type="submit" class="btn btn-primary">Salvar dados</button>
            <a class="btn btn-danger" data-dismiss="modal">Fechar</a>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
</form>

<script type="text/javascript">
    $('#dt_vencimento').datepicker();
    $('#dt_atualizacao').datetimepicker({
        lang: 'pt',
        mask:'39/19/9999 29:59',
        format:'d/m/Y H:i',
        formatTime: 'H:i',
        formatDate: 'd/m/Y',
        step: 60
    });
</script>