<?php echo form_open_multipart('titulos/editar_post', array( 'class' => 'form-horizontal', 'onsubmit' => "$(this).ajaxSubmit({dataType : 'json', success: processModalSubmit}); return false;")); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Editar título</h4>
        </div>
        <div class="modal-body">
            <div class="row-fluid sortable">
                <div class="col-lg-6">
                    <?php echo validation_errors(); ?>
                    <input type="hidden" id="id" name="id" value="<?=$details['id'];?>" />
                    <div class="control-group">
                      <label class="control-label" for="sigla" >Sigla</label>
                      <div class="controls">
                        <input type="text" id="sigla" name="sigla" class="input" value="<?=$details['sigla'];?>" > 
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="nome" >Título</label>
                      <div class="controls">
                        <input type="text" id="nome" name="nome" class="input-xlarge" value="<?=$details['nome'];?>" required> 
                      </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dt_vencimento">Vencimento</label>
                        <div class="controls">
                            <input type="text" class="input datepicker" name="dt_vencimento" id="dt_vencimento" value="<?=date("d/m/Y",strtotime($details['dt_vencimento']));?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dt_ult_alteracao">Dt.Atualização</label>
                        <div class="controls">
                            <input type="text" class="input datepicker" name="dt_ult_alteracao" id="dt_ult_alteracao" value="<?=date("d/m/Y H:i",strtotime($details['dt_ult_alteracao']));?>">
                        </div>
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="tx_compra">Taxa Compra (a.a)</label>
                        <div class="controls">
                            <span class="add-on">%</span>
                            <input id="prependedInput" name="tx_compra" id="tx_compra" min="0" max="100" size="5" type="number" value="<?=$details['tx_compra'];?>" step="any">
                        </div>    
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="tx_venda">Taxa Venda (a.a)</label>
                        <div class="controls">
                            <span class="add-on">%</span>
                            <input id="prependedInput" name="tx_venda" id="tx_venda" min="0" max="100" size="5" type="number" value="<?=$details['tx_venda'];?>" step="any" >
                        </div>    
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="preco_compra">Preço Compra</label>
                        <div class="controls">
                            <span class="add-on">R$</span>
                            <input id="prependedInput" name="preco_compra" id="preco_compra" min="0" max="100000" size="10" type="number" value="<?=$details['preco_compra'];?>" step="any" >
                        </div>    
                    </div>
                    <div class="input-prepend">
                        <label class="control-label" for="preco_venda">Preço Venda</label>
                        <div class="controls">
                            <span class="add-on">R$</span>
                            <input id="prependedInput" name="preco_venda" id="preco_venda" min="0" max="100000" size="10" type="number" value="<?=$details['preco_venda'];?>" step="any">
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
    $('#dt_vencimento').datepicker();
    $('#dt_ult_alteracao').datetimepicker({
        lang: 'pt',
        mask:'39/19/9999 29:59',
        format:'d/m/Y H:i',
        formatTime: 'H:i',
        formatDate: 'd/m/Y',
        step: 60
    });
</script>