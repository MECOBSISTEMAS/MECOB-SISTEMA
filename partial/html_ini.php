<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php  if(!empty($layout_title))echo $layout_title; else echo "Sistema de Cobrança";?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="Tue, 01 Jan 2030 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="shortcut icon" href="<?php echo $link;?>/imagens/favicon/favicon.png?des">
    <!--Loading bootstrap css-->
<!--    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,700"> -->
    <!--<link type="text/css" rel="stylesheet" href="<?php //echo $link;?>/css/opensans.css">-->
    <link href='//fonts.googleapis.com/css?family=Dosis:400,700' rel='stylesheet' type='text/css'>
 
    <?php if(isset($addcss_before)){echo $addcss_before;}?>
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/eventos.css" >
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/fontawesome.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/styles/jquery-ui-1.10.4.custom.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/styles/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/styles/bootstrap.min.css?qq">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/styles/animate.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/styles/all.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/styles/<?php 
		if(!empty($main_css))echo $main_css; else echo 'main.css';?>?q">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/styles/style-responsive.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/styles/zabuto_calendar.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/styles/pace.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/styles/jquery.news-ticker.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/js/alerts/jquery.alerts.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/bootstrap-multiselect.css" >
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/animations.css">
    
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/geral.css?awqji">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/estilo.css?awqki">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/js/bower_components/toastr/toastr.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $link;?>/css/adminLTE.css">

    <?php if(isset($addcss)){echo $addcss;}?>
    
    
   
</head>
<body onresize="resize_response()">

<?php #echo "<pre>" ;print_r($ck_mksist_permissao); echo "</pre>"; ?>

<?php if(!isset($gerar_planilha_pdf)){ ?>
<div class="sidebar-img row hidden " >
    <div  class="col-sm-12 ">
    <img src="<?php echo $link."/imagens/logo.jpg";?>" alt="" class="img-responsive   "/>
    </div>
</div>
<?php } ?>