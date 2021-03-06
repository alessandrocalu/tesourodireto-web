<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$esconder_menu = $this->input->get('esconder_menu') || (isset($esconder_menu) && $esconder_menu);
//$usuario_id = $this->session->userdata('logged_in')['id']; 
//echo base_url('sb-admin/dist/css/sb-admin-2.css'); 
//$this->tb_usuario->permiteTela($usuario_id, 'perfis/index')
?><!DOCTYPE html>
<html lang="pt-br">
<head>
    
    <!-- start: Meta -->
    <meta charset="utf-8">
    <title>Carteira Tesouro Direto</title>
    <!-- end: Meta -->
    
    <!-- start: Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- end: Mobile Specific -->
    
    <!-- start: CSS -->
    <link id="bootstrap-style" href="<?php echo base_url('janux/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('janux/css/bootstrap-responsive.min.css'); ?>" rel="stylesheet">
    <link id="base-style" href="<?php echo base_url('janux/css/style.css'); ?>" rel="stylesheet">
    <link id="base-style-responsive" href="<?php echo base_url('janux/css/style-responsive.css'); ?>" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
    <!-- end: CSS -->
    
      
    <!-- start: Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('janux/img/favicon.ico'); ?>">
    <!-- end: Favicon -->

    <style type="text/css">
		body { background: url("<?php echo base_url('janux/img/bg-login.jpg'); ?>") !important; }
	</style>
        
        
</head>

<body>

<div class="container-fluid-full">
	<div class="row-fluid">
		<div class="login-box">
			<br>
			<h2>Carteira Tesouro Direto - Gerar nova senha</h2>
			<div class="row-fluid sortable">
				<div class="alert alert-danger <?php echo !empty($erro) ? '' : 'hidden' ?>">
          			<?php echo $erro; ?>
        		</div>
        	</div> 
			<form class="form-horizontal" action="<?php echo base_url('/index.php/account/sendemail') ?>" method="post">
				<fieldset>
					
					<div class="input-prepend" title="Email">
						<span class="add-on"><i class="halflings-icon user"></i></span>
						<input class="input-large span10" name="login" id="login" type="text" placeholder="email" required>
					</div>
					<div class="clearfix"></div>
								
					<div class="button-login">	
						<button type="submit" class="btn btn-primary">Login</button>
					</div>
					<div class="clearfix"></div>
			</form>
		</div><!--/span-->
	</div><!--/row-->
</div><!--/.fluid-container-->



 <!-- start: JavaScript-->
<script src="<?php echo base_url('janux/js/jquery-1.9.1.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery-migrate-1.0.0.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery-ui-1.10.0.custom.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.ui.touch-punch.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/modernizr.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/bootstrap.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.cookie.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/fullcalendar.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.dataTables.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/excanvas.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.flot.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.flot.pie.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.flot.stack.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.flot.resize.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.chosen.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.uniform.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.cleditor.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.noty.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.elfinder.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.raty.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.iphone.toggle.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.uploadify-3.1.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.gritter.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.imagesloaded.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.masonry.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.knob.modified.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/jquery.sparkline.min.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/counter.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/retina.js'); ?>"></script>

<script src="<?php echo base_url('janux/js/custom.js'); ?>"></script>
    <!-- end: JavaScript-->
    
</body>
</html>