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
    <link href="<?php echo base_url('css/bootstrap-select.min.css'); ?>" rel="stylesheet">
    <link id="base-style" href="<?php echo base_url('janux/css/style.css'); ?>" rel="stylesheet">
    <link id="base-style-responsive" href="<?php echo base_url('janux/css/style-responsive.css'); ?>" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
    <link id="base-style-responsive" href="<?php echo base_url('css/jquery.datetimepicker.min.css'); ?>" rel="stylesheet">
    <!-- end: CSS -->


    
    <!-- start: Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('janux/img/favicon.ico'); ?>">
    <!-- end: Favicon -->
        
        
</head>

<body>
    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-87271619-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>

    <script>
        var gerar_graficos = false;
    </script>
        <!-- start: Header -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" ><span>Tesouro Direto Web</span></a>
                                
                <!-- start: Header Menu -->
                <div class="nav-no-collapse header-nav">
                    <ul class="nav pull-right">
                        <!-- start: User Dropdown -->
                        <li class="dropdown">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="halflings-icon white user"></i>
                                <?php echo $this->session->userdata('logged_in')['nome']; ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-menu-title">
                                    <span>Configurações</span>
                                </li>
                                <!--  <li><a href="#"><i class="halflings-icon user"></i> Meus dados</a></li> -->
                                <li><a href="<?php echo base_url(); ?>index.php/account/logout"><i class="halflings-icon off"></i> Logout</a></li>
                            </ul>
                        </li>
                        <!-- end: User Dropdown -->
                    </ul>
                </div>
                <!-- end: Header Menu -->
                
            </div>
        </div>
    </div>
    <!-- start: Header -->
    
        <div class="container-fluid-full">
        <div class="row-fluid">
                
            <!-- start: Main Menu -->
            <div id="sidebar-left" class="span2">
                <div class="nav-collapse sidebar-nav">
                    <ul class="nav nav-tabs nav-stacked main-menu">
                        <li><a href="<?php echo base_url(); ?>/"><i class="icon-bar-chart"></i><span class="hidden-tablet"> Visão Geral</span></a></li>   
                        <li><a href="<?php echo base_url(); ?>index.php/titulos"><i class="icon-bookmark-empty"></i><span class="hidden-tablet"> Cotações</span></a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/carteira"><i class="icon-list-ol"></i><span class="hidden-tablet"> Carteira</span></a></li>
                        <?php if ($admin){ ?>
                        <li><a href="<?php echo base_url(); ?>index.php/usuarios"><i class="icon-group"></i><span class="hidden-tablet"> Usuários</span></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <!-- end: Main Menu -->
            
            <noscript>
                <div class="alert alert-block span10">
                    <h4 class="alert-heading">Warning!</h4>
                    <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
                </div>
            </noscript>