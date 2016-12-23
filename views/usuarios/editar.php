<?php echo form_open_multipart('usuarios/editar_post', array( 'class' => 'form-horizontal', 'onsubmit' => "$(this).ajaxSubmit({dataType : 'json', success: processModalSubmit}); return false;")); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Editar usuário</h4>
        </div>
        <div class="modal-body">
            <div class="row-fluid sortable">
                <div class="col-lg-6">
                    <?php echo validation_errors(); ?>
                    <input type="hidden" id="id" name="id" value="<?=$details['id'];?>" />
                    <div class="control-group">
                      <label class="control-label" for="email" >Nome</label>
                      <div class="controls">
                        <input type="text" id="nome" name="nome" class="input-xlarge" value="<?=$details['nome'];?>" required> 
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="email" >Email</label>
                      <div class="controls">
                        <input type="text" id="email" name="email" class="input-xlarge" value="<?=$details['email'];?>" required> 
                      </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dt_nasc">Data Nascimento</label>
                        <div class="controls">
                            <input type="text" class="input-xlarge datepicker" name="dt_nasc" id="dt_nasc" value="<?=date("d/m/Y",strtotime($details['dt_nasc']));?>">
                        </div>
                    </div>
                    <?php if($admin) : ?>
                        <div class="control-group">
                            <label class="control-label" for="perfil" >Perfil</label>
                            <div class="controls">
                                <input type="text" id="perfil" name="perfil" class="input" value="<?=$details['perfil'];?>" required> 
                            </div>
                        </div>
                    <?php endif; ?>    
                    <div class="control-group">
                        <label class="control-label" for="senha">Senha</label>
                        <div class="controls">
                            <input type="password" class="input" name="senha" id="senha">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="con">Confirmar Senha</label>
                        <div class="controls">
                            <input type="password" class="input" name="confirmar_senha" id="confirmar_senha">
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

