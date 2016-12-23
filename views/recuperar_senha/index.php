<?php if(isset($chave_invalida) && $chave_invalida) :?>
    Chave inválida!
<?php else: ?>
<?php echo validation_errors(); ?>
<?php if(isset($mensagem_erro)) : ?>
	<div class="row" style="padding-left: 10px; padding-right: 10px">
		<div class="alert alert-warning">
			<?=$mensagem_erro;?>
		</div>
	</div>
<?php endif; ?>
<?php echo form_open('recuperar_senha/alterar'); ?>
    <input type="hidden" name="email" value="<?=$email;?>">
    <input type="hidden" name="chave" value="<?=$chave;?>">
    <fieldset>
        <div class="form-group">
            <label>E-mail</label>
            <?=$email;?>
        </div>                                                        
        <div class="form-group">
            <input class="form-control" placeholder="Nova senha" name="senha" type="password" value="">
        </div>
        <div class="form-group">
            <input class="form-control" placeholder="Confirmação" name="confirmar_senha" type="password" value="">
        </div>                            
        <!-- Change this to a button or input when using this as a form -->
        <input type="submit" class="btn btn-lg btn-success btn-block" value="Alterar senha" />
    </fieldset>
</form>
<?php endif; ?>     