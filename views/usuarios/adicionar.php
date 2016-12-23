<?php echo form_open_multipart('usuarios/adicionar_post', array( 'class' => 'form-horizontal', 'onsubmit' => "$(this).ajaxSubmit({dataType : 'json', success: processModalSubmit}); return false;")); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Adicionar usuário</h4>
        </div>
        <div class="modal-body">
            <div class="row-fluid sortable">
                <div class="col-lg-6">
                    <?php echo validation_errors(); ?>
                    <div class="control-group">
                      <label class="control-label" for="nome" >Nome</label>
                      <div class="controls">
                        <input type="text" id="nome" name="nome" class="input-xlarge"  required> 
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="email" >Email</label>
                      <div class="controls">
                        <input type="text" id="email" name="email" class="input-xlarge"  required> 
                      </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dt_nasc">Data Nascimento</label>
                        <div class="controls">
                            <input type="text" class="input datepicker" name="dt_nasc" id="dt_nasc" value="28/11/1977">
                        </div>
                    </div>
                    <?php if($admin) : ?>
                        <div class="control-group">
                            <label class="control-label" for="perfil" >Perfil</label>
                            <div class="controls">
                                <input type="text" id="perfil" name="perfil" class="input"  required> 
                            </div>
                        </div>
                    <?php endif; ?>    
                    <div class="control-group">
                        <label class="control-label" for="senha">Senha</label>
                        <div class="controls">
                            <input type="password" class="input" name="senha" id="senha" required>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="con">Confirmar Senha</label>
                        <div class="controls">
                            <input type="password" class="input" name="confirmar_senha" id="confirmar_senha" required>
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
    $('#dt_nasc').datepicker();
</script>

