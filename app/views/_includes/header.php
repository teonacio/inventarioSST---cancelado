<?php header('Content-Type: text/html; charset=UTF-8'); ?>

<?php if ( ! defined('ABSPATH')) exit; ?>
<?php if( isset( $modelo ) ) { $title = $this->title; } else { $title = 'Erro 404'; } ?>

<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="pt-BR">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="pt-BR">
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html lang="pt-BR">
<!--<![endif]-->

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width">
	
	<link rel="stylesheet" href="<?php echo HOME_URI;?>/style/_css/style.css">
	<link rel="stylesheet" href="<?php echo HOME_URI;?>/style/_css/menu.css">
	<link rel="stylesheet" href="<?php echo HOME_URI;?>/style/_css/bootstrap/bootstrap.css">
	<link rel="stylesheet" href="<?php echo HOME_URI;?>/style/_css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo HOME_URI;?>/style/_css/bootstrap/bootstrap-theme.css">
	<link rel="stylesheet" href="<?php echo HOME_URI;?>/style/_css/bootstrap/bootstrap-theme.min.css">
	<link rel="stylesheet" href="<?php echo HOME_URI;?>/style/_css/dataTables/jquery.dataTables.css">
	<link rel="stylesheet" href="<?php echo HOME_URI;?>/style/_css/dataTables/jquery.dataTables.min.css">
	<link rel="stylesheet" href="<?php echo HOME_URI;?>/style/_css/dataTables/jquery.dataTables_themeroller.css">

	<!--[if lt IE 9]>
	<script src="<?php echo HOME_URI;?>/style/_js/scripts.js"></script>
	<![endif]-->
	<script src="<?php echo HOME_URI;?>/style/_js/menu.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
	<script src="<?php echo HOME_URI;?>/style/_js/JQuery/calendario.js"></script>
	<script src="<?php echo HOME_URI;?>/style/_js/bootstrap/bootstrap.js"></script>
	<script src="<?php echo HOME_URI;?>/style/_js/bootstrap/bootstrap.min.js"></script>
	<script src="<?php echo HOME_URI;?>/style/_js/bootstrap/npm.js"></script>
	<script src="<?php echo HOME_URI;?>/style/_js/JQuery/dataTables/jquery.dataTables.js"></script>
	<script src="<?php echo HOME_URI;?>/style/_js/JQuery/dataTables/jquery.dataTables.min.js"></script>
	<script src="<?php echo HOME_URI;?>/style/_js/JQuery/dataTables/paginacao.js"></script>
	
	<title><?php echo $title; ?></title>
</head>
<body>
	
	<p class="logo">
		<a href = "<?php echo LOGIN_URI; ?>" />
			<img src = <?php echo HOME_URI . '/style/_images/logo_testes/logo_inven.jpg'; ?> alt="Inventario SST" />
		</a>
	</p>

<div class="main-page">