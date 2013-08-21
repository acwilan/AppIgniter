<?php

function build_menu($node, &$breadcrumbs, $selected = NULL, $ancestors = array()) {
	$ancestors []= $node->name; 
	$selpath = explode('/', $selected);
	$active = in_array($node->name, explode('/', $selected));// strpos($selected, implode('/', $ancestors)) === 0;
	if ($active)
		$breadcrumbs[implode('/', $ancestors)] = $node->title;
	if (isset($node->items) && count($node->items) > 0) { ?>
		<li class="dropdown<?php echo $active ? ' active' : ''; ?>">
			<a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#" role="button" id="menu_<?php echo $node->name; ?>">
				<?php echo $node->title; ?>
				<b class="caret"></b>
			</a>
			<ul class="dropdown-menu" role="menu" aria-labeledby="menu_<?php echo $node->name; ?>"><?php
		foreach ($node->items as $subnode) {
			build_menu($subnode, $breadcrumbs, $selected, $ancestors);
		} ?>
			</ul>
		</li><?php
	}
	elseif ($node->name == 'separator') { ?>
		<li class="divider"></li><?php
	}
	elseif ($node->name == 'header') { ?>
		<li class="nav-header"><?php echo $node->title; ?></li><?php
	}
	else { ?>
		<li<?php echo $active ? ' class="active"' : ''; ?>>
			<a href="<?php echo isset($node->link) && !empty($node->link) ? site_url($node->link) : site_url(implode('/', $ancestors)); ?>"><?php echo $node->title; ?></a>
		</li><?php
	}
}