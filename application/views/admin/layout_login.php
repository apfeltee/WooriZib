<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $config->name;?> <?php echo lang('admin_administrator');?></title>

<link href="/assets/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/assets/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/css/style.css" rel="stylesheet" type="text/css"/>
<link href="/assets/admin/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>


<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/assets/plugin/respond.min.js"></script>
<script src="/assets/plugin/excanvas.min.js"></script> 
<![endif]-->

<script src="/assets/plugin/jquery.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/assets/plugin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery.form.js" type="text/javascript" charset="UTF-8"></script>
<script src="/assets/plugin/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/assets/plugin/jquery.cokie.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/plugin/select2/select2.min.js"></script>

</head>
<body class="login" oncontextmenu="return false">
	<?php echo $content_for_layout;?>
</body>
</html>