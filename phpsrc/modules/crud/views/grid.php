<div class="row" id="main-grid">
	<div class="span12"><?php 
		if ($options->allow_add || $options->allow_delete || $options->allow_search || $options->custom_buttons) : ?>
		<div class="row">
			<div class="span6">
				<div class="btn-toolbar"><?php
					if ($options->allow_add) : ?>
					<a href="<?php echo site_url($meta->module_name.'/add'); ?>" class="btn">
						<i class="icon-plus"></i> Agregar
					</a><?php
					endif;
					if ($options->allow_delete) : ?>
					<a href="#" class="btn" id="btn-del-selected">
						<i class="icon-trash"></i> Eliminar seleccionados
					</a><?php
					endif;
					if ($options->custom_buttons) : 
						foreach ($options->custom_buttons as $key=>$button) : ?>
					<a href="<?php echo $button->link; ?>" class="btn" id="btn-<?php echo $key; ?>"<?php echo isset($button->target) ? ' target="'.$button->target.'"' : ''; ?>>
						<?php echo isset($button->icon) ? '<i class="icon-'.$button->icon.'"></i> ' : ''; ?>
						<?php echo $button->label; ?>
					</a><?php
						endforeach;
					endif; ?>
				</div>
			</div><?php 
			if ($options->allow_search) : ?>
			<div class="span6">
				<form class="form-search">
					<div class="pull-right input-append grid-search-form">
						<input type="text" class="input-medium search-query" value="<?php echo $meta->catalog_state->search->enabled ? $meta->catalog_state->search->term : ''; ?>" />
						<a class="clear" href="#" title="Restaurar"><i class="icon-remove"></i></a>
						<button type="submit" class="btn">Buscar</button>
					</div>
				</form>
			</div><?php 
			endif; ?>
		</div><?php 
		endif; ?>
		<div style="position:relative;z-index:0;"><?php 
		if (!empty($table_data)) : ?>
			<table class="table <?php echo @$options->striped ? 'table-striped ' : ''; ?>table-hover table-bordered table-sortable" id="grid_<?php echo str_replace('/','_',$meta->module_name); ?>"><?php
			if (!empty($column_data)) : ?>
				<tr role="header"><?php
				foreach ($column_data as $column) : 
					if (!isset($column->hidden) || !$column->hidden) : ?>
					<th scope="col" role="columnheader" class="<?php echo $meta->catalog_state->sort->field == $column->name || (isset($column->sortable_field) && $meta->catalog_state->sort->field == $column->sortable_field) ? "sortable sorted sorted-{$meta->catalog_state->sort->order}" : (isset($column->sortable) && $column->sortable ? 'sortable unsorted' : 'none'); ?>" data-id="<?php echo isset($column->sortable_field) ? $column->sortable_field : $column->name; ?>">
						<?php echo $column->title; ?> <i class="icon-sort"></i>
					</th><?php
					endif;
				endforeach; ?><?php
				if ($options->show_actions) : ?>
					<th scope="col" role="columnheader">Acciones</th><?php
				endif; ?>
				</tr><?php
			endif; 
			$cols = 0;
			foreach ($table_data as $row) : 
				$keyname = FALSE;
				?>
				<tr role="row"><?php
				foreach ($column_data as $column) : 
					if (!isset($column->hidden) || !$column->hidden) :
						if (isset($column->key) && $column->key) $keyname = $column->name; ?>
					<td<?php echo isset($column->key) && $column->key ? ' role="rowheader"' : ''; ?>><?php
						if (isset($column->key) && $column->key && (!isset($column->hidden) || !$column->hidden)) { ?>
						<input type="checkbox" id="grid_<?php echo str_replace('/','_',$meta->module_name).'_'.$column->name.'_'.$row->{$column->name}; ?>" value="<?php echo $row->{$column->name}; ?>" class="row-header" /><?php
						} 
						else {
							switch ($column->type) {
								case 'bool': ?>
						<a href="<?php echo site_url("{$meta->module_name}/toggle_ajax/{$column->name}/{$row->{array_pop(explode('.', $meta->primary_key))}}"); ?>" class="btn-toggle">
							<i class="icon icon-<?php echo $row->{$column->name} ? 'ok' : 'remove'; ?>"></i>
						</a><?php
									break;
								case 'decimal':
									$dc = isset($column->decimals) ? $column->decimals : 2;
									echo number_format($row->{$column->name}, $dc);
									break;
								case 'money':
									$dc = isset($column->decimals) ? $column->decimals : 2;
									$column->symbol = isset($column->symbol) ? $column->symbol : $meta->current_period->simbolo;
									echo '<em>'.$column->symbol.'</em> '.number_format($row->{$column->name}, $dc);
									break;
								case 'date':
									$column->format = isset($column->format) ? $column->format : 'Y-m-d';
									echo date($column->format, strtotime($row->{$column->name}));
									break;
								case 'tooltip': ?>
						<a href="#" rel="popover" data-title="<?= $column->title ?>" data-content="<?php echo $row->{$column->name}; ?>"><?php echo $column->legend; ?></a><?php
									break;
								default: 
									if (valid_email($row->{$column->name}))
										echo mailto($row->{$column->name}, $row->{$column->name});
									else
										echo $row->{$column->name};
									break;
							}
						} ?>
					</td><?php
					endif;
				endforeach; 
				if ($options->show_actions) : ?>
					<td><?php 
					if ($options->allow_edit) : ?>
						<a href="<?php echo site_url($meta->module_name.'/edit/'.$row->{$keyname}); ?>" class="btn btn-mini"><i class="icon icon-edit"></i> <?php echo $options->edit_label; ?></a><?php
					endif;
					if ($options->custom_actions !== FALSE)
						foreach ($options->custom_actions as $action) : ?>
						<a href="<?php echo site_url($meta->module_name.'/'.sprintf($action->url_format, $row->{array_pop(explode('.', $meta->primary_key))})); ?>" class="btn btn-mini"<?php echo isset($action->target) ? ' target="'.$action->target.'"' : ''; ?>><?php if (isset($action->icon)) : ?><i class="icon icon-<?php echo $action->icon; ?>"></i> <?php endif; ?><?php echo $action->label; ?></a><?php
						endforeach; ?>
					</td><?php
				endif; ?>
				</tr><?php
				$cols++;
			endforeach; ?>
			</table><?php
		else : ?>
			<div class="hero-unit">
				<h2 style="text-align:center"><?php echo $options->empty_message; ?></h2>
			</div>
		<?php
		endif; ?>
			<div class="row">
				<div class="span6"><?php $pg = $meta->catalog_state->pagination; ?>
					Mostrando <?php echo (($pg->current_page-1)*$pg->rpp+1).'-'.($pg->current_page >= $pg->total_pages ? $pg->total_records : $pg->current_page*$pg->rpp); ?> registro<?php echo count($meta->catalog_state->pagination->rpp) > 0 ? 's' : ''; ?> de <?php echo $meta->catalog_state->pagination->total_records; ?>
				</div>
				<div class="span6">
					<div class="pagination pagination-right" id="<?php echo str_replace('/','_',$meta->module_name); ?>-pagination"><?php 
				$pg = $meta->catalog_state->pagination; ?>
						<ul>
							<li<?php echo $pg->current_page == 1 ? ' class="disabled"' : ''; ?>><a href="#" data-id="prev">&laquo;</a></li><?php
						for ($i = 0; $i < $pg->total_pages; $i++) : ?>
							<li<?php echo $pg->current_page == $i+1 ? ' class="disabled"' : ''; ?>><a href="#" data-id="<?php echo $i+1; ?>"><?php echo $i+1; ?></a></li><?php
						endfor; ?>
							<li<?php echo $pg->current_page == $pg->total_pages ? ' class="disabled"' : ''; ?>><a href="#" data-id="next">&raquo;</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="grid-overlay"></div>
		</div>
	</div>
</div>