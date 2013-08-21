<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width = device-width" />
		<base href="<?= base_url() ?>" />
		<link type="text/css" rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>" />
		<link type="text/css" rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-responsive.min.css'); ?>" />
		<link type="text/css" rel="stylesheet" href="<?php echo site_url('assets/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" />
		<link type="text/css" rel="stylesheet" href="<?php echo site_url('assets/css/main.css?v=2'); ?>" />
<?php 
if (!empty($css_files))
	foreach($css_files as $file): ?>
		<link type="text/css" rel="stylesheet" href="<?php echo site_url("assets/css/$file"); ?>" />
 
<?php endforeach; ?>
		<title>Sistema Administrativo :: Circo Tihany</title>
	</head>
	<body><?php if ($show) : ?>
		<div id="top-bar" class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand<?php echo $this->module_name == '' ? ' active' : ''; ?>" href="<?php echo site_url(); ?>"><i class="icon-white icon-home"></i> Inicio</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
	<?php 
		$breadcrumbs = array(''=>'Inicio');
		$selected = explode('/', $selected);
		
		if (!empty($modules)) {
			$i	= 0;
			while ($i < count($modules)) {
				$parent	= $modules[$i];
				if (($i + 1 < count($modules)) && ($modules[$i + 1]->id_modulo_padre != null)) { ?>
							<li class="dropdown<?php echo $parent->nombre_clave == $selected[0] ? ' active' : ''; ?>"><?php 
					$menuChildren = '';
					$i += 1;
					$module		= $modules[$i];
					
					$menuChildren .=	'<ul class="dropdown-menu" role="menu" aria-labeledby="menu_'.$parent->nombre_clave.'">';
					while (($i < count($modules)) && ($modules[$i]->id_modulo_padre != null)) {
						$module		= $modules[$i];
						if( $module->hidden ){
							$i += 1;
							continue;
						}
						
						if ($module->nombre_clave == 'separator') {
							$menuChildren .=	'		<li class="divider"></li>';
						} else {
							$menuChildren .=	'		<li'.( $module->nombre_clave === @$selected[1] ? ' class="active"' : '' ).'>';
							$menuChildren .=	'			<a href="' . site_url($parent->nombre_clave.'/'.$module->nombre_clave) . '">' . $module->nombre . '</a>';
							$menuChildren .=	'		</li>';
							if ($module->nombre_clave === @$selected[1]) {
								$breadcrumbs [$parent->nombre_clave] = $parent->nombre;
								$breadcrumbs [$parent->nombre_clave.'/'.$module->nombre_clave] = $module->nombre;
							} // if
						}
						$i += 1;
					} // while
					
					$menuChildren .=	'</ul>';
					$i -= 1; ?>
								<a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#" role="button" id="menu_<?php echo $parent->nombre_clave; ?>">
									<?php echo $parent->nombre; ?>
									<b class="caret"></b>
								</a><?php echo $menuChildren; ?>
							</li><?php				
				} else {
					if ($parent->nombre_clave === @$selected[0]) {
						$breadcrumbs[$parent->nombre_clave] = $parent->nombre;
					} 
					if ($parent->nombre_clave == 'separator') { ?><li class="divider"></li><?php } else { ?>
							<li<?php echo $parent->nombre_clave === @$selected[0] ? ' class="active"' : ''; ?>>
								<a href="<?php echo site_url($parent->nombre_clave); ?>"><?php echo $parent->nombre; ?></a>
							</li><?php
					}
				} // if/else
				$i += 1;
			} // while
		} // if  ?>
						</ul><?php if (!empty($user_id) && !empty($user_info)) : ?>
						<ul class="nav pull-right">
							<li class="dropdown pull-right">
								<a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#" role="button" id="user_menu"><i class="icon-white icon-user"></i> <?php echo $user_info->username; ?><b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu" aria-labeledby="user_menu">
									<li><a href="<?php echo site_url('system/users_management/profile'); ?>">
										<i class="icon-pencil"></i> Editar perfil</a></li>
									<li class="divider"></li>
									<li><a href="<?php echo site_url('auth/logout'); ?>">
										<i class="i"></i> Cerrar sesi&oacute;n</a></li>
								</ul>
							</li>
						</ul><?php endif; ?>
					</div>
				</div>
			</div>
		</div><?php endif; ?>
		<div class="container" role="main" id="main"><?php if ($show) : ?>
			<div id="header" class="row">
				<div id="header-logo" class="span2">
					<a href="<?php echo site_url(); ?>">
						<img src="<?php echo site_url('assets/images/logo_tihany.jpeg') ?>" id="header-img-logo" alt="Tihany" border="0" />
					</a>
				</div>
				<h1 id="header-title" class="span7"><?php echo $title; ?></h1>
<?php if (!empty($user_id) && !empty($user_info)) :  ?>
				<div class="span3 last">
					<div class="row">
						<div class="span2">
							<div class="btn-group">
								<a class="btn btn-warning captitle" href="<?php echo !empty($current_period) ? site_url('system/cities/edit/'.$current_period->id_ciudad) : site_url('system/cities/change'); ?>"><i class="icon-calendar icon-white"></i> <?php echo $current_period !== FALSE ? "{$current_period->ciudad}, {$current_period->pais}" : 'Cambiar ciudad, pais'; ?></a>
								<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo site_url('system/cities/add'); ?>">
										<i class="icon-plus"></i> Agregar nueva</a></li><?php if ($current_period != FALSE) : ?>
									<li><a href="<?php echo site_url('system/cities/edit/'.$current_period->id_ciudad); ?>">
										<i class="icon-pencil"></i> Editar ciudad</a></li>
									<li class="divider"></li><?php endif; ?>
									<li><a href="<?php echo site_url('system/cities/change'); ?>">
										<i class="icon-refresh"></i> Cambiar ciudad</a></li><?php if ($current_period != FALSE) : ?>
									<li class="divider"></li>
									<li><a href="#">Moneda: <?php echo $current_period->simbolo; ?></a></li>
									<?php endif; ?>
								</ul>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="span1">
							<div class="btn-group">
								<a class="btn btn-success" href="<?php echo !empty($current_period)&&!empty($current_period->id_periodo) ? site_url('system/periods/edit/'.$current_period->id_periodo) : site_url('system/periods'); ?>"><i class="icon-calendar icon-white"></i> <?php echo $current_period !== FALSE && !empty($current_period->id_periodo) ? $current_period->codigo : 'Cambiar semana'; ?></a>
								<a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
								<ul class="dropdown-menu"><?php if ($current_period != FALSE && !empty($current_period->id_periodo)) : ?>
									<li><a href="<?php echo site_url('system/periods/edit/'.$current_period->id_periodo); ?>">
				<i class="icon-pencil"></i> Editar semana</a></li>
									<li class="divider"></li><?php endif; ?>
									<li><a href="<?php echo site_url('system/periods/add'); ?>">
									<i class="icon-plus"></i> Empezar nueva</a></li>
									<li><a href="<?php echo site_url('system/periods'); ?>">
									<i class="icon-refresh"></i> Cambiar a otra</a></li>
									<li><a href="<?php echo site_url('system/periods/current'); ?>">
									<i class="icon-screenshot"></i> Semana actual</a></li>
									<?php if ($current_period != FALSE && !empty($current_period->id_periodo)) : ?>
									<li class="divider"></li>
									<li><a href="#">1 US$ = <?php echo $current_period->simbolo.' '.round($current_period->tasa_cambio,2); ?></a></li>
									<?php endif; ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<div id="breadcrumbs" class="row">
				<div class="span12">
					<ul class="breadcrumb"><?php
	$i = 0;
	foreach ($breadcrumbs as $path=>$title) : 
		if ( (!empty($operation) || ($i < count($breadcrumbs)-1 && !is_int($path))) && $i != 1) : ?>
						<li>
							<a class="pathway" href="<?php echo site_url($path); ?>"><?php echo $title; ?></a>
							<span class="divider">/</span>
						</li><?php
		else : ?>
						<li class="active">
							<?php echo $title; 
							if (is_int($path) || $i == 1) : ?>
							<span class="divider">/</span><?php
							endif; ?>
						</li><?php
		endif;
		$i++;
		endforeach; 
		if (!empty($operation)) : ?>
						<li class="active">
							<?php echo lang("crud_$operation"); ?>
							</li><?php
		endif; ?>
					</ul>
				</div>
			</div><?php
	if (isset($errors) && !empty($errors)) : ?>
			<div id="error-msg" class="row">
				<div class="span8 offset2"><?php
		foreach ($errors as $error) : ?>
					<div class="alert alert-<?php echo $error->type; ?>">
						<button type="button" class="close" data-dismiss="alert">x</button>
					<?php echo $error->msg; ?>
					</div><?php
		endforeach; ?>
				</div>
			</div><?php
	endif; ?><?php endif; ?>
			<div id="main-content">