<!-- start: Content -->
<div id="content" class="span10">

	<ul class="breadcrumb">
	    <li>
	        <i class="icon-list-ol"></i>
	        <a href="<?php echo base_url(); ?>index.php/titulos">Títulos</a> 
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
					<?php
					if ($admin){
					?>	
					<a href="<?php echo base_url(); ?>index.php/titulos/adicionar" title="Incluir título" class="btn btn-primary btn-sm modal-dlg">Incluir título</a>
					<?php
					}
					?>
					<a href="<?php echo base_url(); ?>index.php/titulos" title="Atualizar" class="btn btn-primary btn-sm">Atualizar</a>
				</div><!--.row-->
				<br>
				<br>
				<table class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th rowspan="2" style="text-align: center">Sigla</th>
							<th rowspan="2" style="text-align: center">Nome</th>
							<th rowspan="2" style="text-align: center">Vencimento</th>
							<th colspan="2" style="text-align: center">Taxa (a.a)</th>
							<th colspan="2" style="text-align: center">Preço Unitário Dia</th>   
							<th rowspan="2" style="text-align: center">Data Atualização</th>
							<th rowspan="2" style="text-align: center"></th>                                       
						</tr>
						<tr>
							<th style="text-align: center">Compra</th>
							<th style="text-align: center">Venda</th>
							<th style="text-align: center">Compra</th>
							<th style="text-align: center">Venda</th>                                          
						</tr>
					</thead>   
					<tbody>
					<?php foreach($data_rows['data'] as $row) : ?>
						<tr>
							<td>
								<?=$row['sigla'];?>
							</td>
							<td>
								<?=$row['nome'];?>
							</td>
							<td style="text-align: center">
								<?=date("d/m/Y",strtotime($row['dt_vencimento']));?>
							</td>
							<td style="text-align: right">
								<?=number_format($row['tx_compra'],2,',','.');?>
							</td>
							<td style="text-align: right">
								<?=number_format($row['tx_venda'],2,',','.');?>
							</td>
							<td style="text-align: right">
								<?=number_format($row['preco_compra'],2,',','.');?>
							</td>
							<td style="text-align: right">
								<?=number_format($row['preco_venda'],2,',','.');?>
							</td>
							<td style="text-align: center">
								<?=date("d/m/Y H:i",strtotime($row['dt_ult_alteracao']));?>
							</td>
							<td style="text-align: center">
								<nobr>
								<?php
								if ($admin){
								?>
								<a class="modal-dlg btn btn-info btn-sm" href="<?php echo base_url(); ?>index.php/titulos/editar/<?=$row['id'];?>" title="Editar título"><i class="icon-edit"></i></a>
								<?php
								}
								?>
								<a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>index.php/cotacoes/historico/<?=$row['id'];?>" title="Cotações de título"><i class="icon-external-link"></i></a>
								<?php
								if ($admin){
								?>	
								<a title="Apagar título" class="modal-dlg btn btn-danger btn-sm" href="<?php echo base_url(); ?>index.php/titulos/modal_excluir/<?=$row['id'];?>"><i class="icon-trash"></i></a>
								<?php
								}
								?>
								</nobr>
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