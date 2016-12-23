<!-- start: Content -->
<div id="content" class="span10">

	<ul class="breadcrumb">
	    <li>
	        <i class="icon-list-ol"></i>
	        <a href="index.html">Usuários</a> 
	    </li>
	</ul>   


	<?php if($this->input->get('message_success')) : ?>
	<div class="row-fluid sortable">
		<div class="alert alert-success">
			<?php echo $this->input->get('message_success'); ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="halflings-icon white edit"></i><span class="break"></span>Filtrar em listagem</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<form method="get" class="form-horizontal">
				  <fieldset>
					<div class="control-group">
					  <label class="control-label" for="filtro" >Buscar</label>
					  <div class="controls">
						<input type="text" id="filtro" name="filtro" class="input" value="<?=$filtro;?>" />	
					  </div>
					</div>
					<div class="control-group">
					  <label class="control-label" for="status">Status</label>
					  <div class="controls">
						<select class="form-control" id="status" name="status">
							<option value="">Mostrar tudo</option>
							<option value="0" <?= $status == '0' ? 'selected' : ''; ?>>Somente inativos</option>
							<option value="1" <?= $status == '1' ? 'selected' : ''; ?>>Somente ativos</option>
						 </select>
					  </div>
					</div>

					<div class="form-actions">
					  <input type="hidden" name="page" value="1" />
					  <button type="submit" class="btn btn-default btn-primary">Consultar</button>
					</div>
				  </fieldset>
				</form>   

			</div>
		</div><!--/span-->
	</div><!--/row-->

	<div class="row-fluid sortable">	
		<div class="box span12">
			<div class="box-header">
				<h2><i class="halflings-icon white align-justify"></i><span class="break"></span>Listagem</h2>
					<div class="box-icon">
						<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
					</div>
			</div>
			<div class="box-content">
				<div class="text-right marginbottom10">
					<a href="<?php echo base_url(); ?>index.php/usuarios/adicionar" title="Incluir usuário" class="btn btn-primary btn-sm modal-dlg">Incluir usuário</a>
					<a href="<?php echo base_url(); ?>index.php/usuarios" title="Atualizar" class="btn btn-primary btn-sm">Atualizar</a>
				</div><!--.row-->
				<br>
				<br>
				<table class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th>E-mail</th>
							<th>Nome</th>
							<th>Ativo</th>
							<th></th>                                          
						</tr>
					</thead>   
					<tbody>
					<?php foreach($data_rows['data'] as $row) : ?>
						<tr>
							<td>
								<?=$row['email'];?>
							</td>
							<td>
								<?=$row['nome'];?>
							</td>
							<td>
								<?=$row['ativo'] == '1' ? 'Sim' : 'Não';?>
							</td>
							<td style="text-align: center">
								<a class="modal-dlg btn btn-info btn-sm" href="<?php echo base_url(); ?>index.php/usuarios/editar/<?=$row['id'];?>" title="Editar usuário"><i class="icon-edit"></i></a>
								<?php if($row['ativo'] == '1') : ?><a title="Desativar usuário" class="modal-dlg btn btn-danger btn-sm" href="<?php echo base_url(); ?>index.php/usuarios/modal_desativar/<?=$row['id'];?>"><i class="icon-trash"></i></a><?php endif; ?>
								<?php if($row['ativo'] == '0') : ?><a title="Reativar usuário" class="modal-dlg btn btn-info btn-sm" href="<?php echo base_url(); ?>index.php/usuarios/modal_ativar/<?=$row['id'];?>"><i class="icon-repeat"></i></a><?php endif; ?>
							</td>                                     
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<div  class="pagination pagination-centered">
					<ul class="pagination">
						<?php if($data_rows['page'] > 1) : ?>
						<li><a href="javascript:changePage(<?=(int)$data_rows['page']-1;?>)" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
						<?php else: ?>
						<li class="disabled"><a aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
						<?php endif; ?>

						<?php for($i = 1; $i < (int)$data_rows['page']; $i++) : ?>
							<li class=""><a href="javascript:changePage(<?=$i;?>)"><?=$i;?> <span class="sr-only"></span></a></li>
						<?php endfor; ?>
						<li class="active"><a href="#"><?=$data_rows['page'];?> <span class="sr-only">(atual)</span></a></li>
						<?php for($i = (int)$data_rows['page'] + 1; $i <= min((int)$data_rows['page'] + 3, (int)$data_rows['totalPages']); $i++) : ?>
							<li class=""><a href="javascript:changePage(<?=$i;?>)"><?=$i;?> <span class="sr-only"></span></a></li>
						<?php endfor; ?>

						<?php if((int)$data_rows['page'] < (int)$data_rows['totalPages']) : ?>
						<li><a href="javascript:changePage(<?=(int)$data_rows['page']+1;?>)" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
						<?php else: ?>
							<li class="disabled"><a aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>	
		</div>
	</div>	
</div>