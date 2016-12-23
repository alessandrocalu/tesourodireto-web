<?php echo form_open('cotacoes/excluir_post', array('onsubmit' => "$(this).ajaxSubmit({dataType : 'json', success: processModalSubmit}); return false;")); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Excluir Cotação de Título -  <?php echo $nomeTitulo; ?></h4>
        </div>
        <div class="modal-body">
            <div id="msg_editar"></div>
            <p>Deseja realmente excluir o título abaixo?</p>

            <div class="form-group">
                <label for="id">Id *</label>
                <input type="text" class="form-control" id="id" name="id" value="<?=$details['id'];?>" disabled />
            </div>

            <div class="form-group">
                <label for="nome_grupo">Data</label>
                <input type="text" name="nome" id="nome" value="<?=date("d/m/Y",strtotime($details['dt_atualizacao']));?>" class="input-xlarge" disabled  />
            </div>

        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="<?=$details['id'];?>"/>
            <button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
            <button type="submit" class="btn btn-danger">Confirmar exclusão</button>
        </div>
       </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</form>