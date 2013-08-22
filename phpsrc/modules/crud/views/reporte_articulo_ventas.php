<style type="text/css" media="print">
h2{font-size:9pt;}
h3{font-size:8pt;margin-left:1in;}
th,caption{font-size:7pt;}
td{font-size:6pt;}
.article{margin-left:2in;width:6in;}
.warehouse{margin-left:4in;width:4in;}
</style>
<style type="text/css">
h1{text-align:center;}
h2{text-align:left !important;color:#FFF;background:#888;border:1px solid #000;padding:0 10px;}
h3{color:#000;background:#CCC;border:1px solid #000;padding:0 10px;}
.article {margin-top:5px;}
.article thead th{border:1px solid #000;}
.article tbody td{border:1px solid #CCC;padding:0 3px;}
.article tbody td:first-child{border:none;text-align:right}
.warehouse {margin-top:20px;}
.warehouse caption{border:1px solid #000;text-align:center;padding:5px 0;font-weight:700;}
.warehouse thead th{border:1px solid #000;border-top:none;}
.warehouse tbody td{border:1px solid #CCC;padding:0 3px;}
.warehouse tbody td:first-child{border:none;text-align:right}
.warehouse tfoot td{border:1px solid #000;}
</style>
<?php if ($report === FALSE) : ?>
<div class="row">
	<div class="span8 offset2">
		<?=form_open(current_url(), array('class'=>'form-horizontal','target'=>'_blank'))?>
			<?=form_fieldset(!empty($options->legend) ? $options->legend : ($options->operation == 'add' ? 'Agregar nuevo registro' : 'Editar registro'))?>
				<div class="control-group">
					<label for="form_field_fecha_inicio" class="control-label">Fecha de inicio:</label>
					<div class="controls">
						<input type="text" name="data[FechaInicio]" id="form_field_fecha_inicio" class="datepicker" value="<?=set_value('data[FechaInicio]',date('Y-m-d'))?>">
					</div>
				</div>
				<div class="control-group">
					<label for="form_field_fecha_fin" class="control-label">Fecha final:</label>
					<div class="controls">
						<input type="text" name="data[FechaFin]" id="form_field_fecha_fin" class="datepicker" value="<?=set_value('data[FechaFin]',date('Y-m-d'))?>">
					</div>
				</div>
				<div class="control-group">
					<label for="form_field_fecha_fin" class="control-label">Categor&iacute;a:</label>
					<div class="controls">
						<select name="data[IdCategoriaArticulo]" id="form_field_id_categoria_articulo">
						<?php foreach ($categorias as $category) : ?>
							<option value="<?php echo $category->IdCategoriaArticulo; ?>"><?php echo $category->Nombre; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
		<?=form_fieldset_close()?>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary" name="action" value="print">Imprimir</button>
				<a href="<?=site_url($meta->module_name)?>" class="btn btn-link" id="btn-cancel">Cancelar</a>
			</div>
		<?=form_close()?>
	</div>
</div>
<?php elseif (empty($report)) : ?>
<h1>No hay datos para este reporte con los parametros especificados</h1>
<?php else : ?>
<div class="row">
	<div class="span12 last report">
		<h1>Reporte de Ventas Generales por Articulo</h1><hr/>
		<?php 
		foreach ($report as $idCategoria=>$catSuperior) : 
			if (!empty($idCategoria)) : ?>
		<h2><?php echo $catSuperior->Nombre; ?></h2>
		<div class="span11 offset1"><?php
				foreach ($catSuperior->Subcategorias as $catHija) : ?>
			<h3><?php echo $catHija->Nombre; ?></h3><?php
					foreach ($catHija->Articulos as $articulo) : ?>
			<div class="row">
				<table class="span11 article">
					<thead>
						<th>Nombre<br/>Art&iacute;culo</th>
						<th>Ajuste</th>
						<th>Devoluci&oacute;n</th>
						<th>Vent</th>
						<th>Vent Pro</th>
						<th>Ventas Brutas (BRR)</th>
						<th>Comisi&oacute;n (BRR)</th>
						<th>Ventas Netas (BRR)</th>
						<th>Costo (BRR)</th>
						<th>Utilidad (BRR)</th>
					</thead>
					<tbody>
						<td><?php echo strtoupper($articulo->Nombre); ?></td>
						<td><?php echo $articulo->Ajustes; ?></td>
						<td><?php echo $articulo->Devoluciones; ?></td>
						<td><?php echo $articulo->Ventas; ?></td>
						<td><?php echo $articulo->VentasPromo; ?></td>
						<td><?php echo $articulo->VentasBrutas; ?></td>
						<td><?php echo $articulo->Comision; ?></td>
						<td><?php echo $articulo->VentasNetas; ?></td>
						<td><?php echo $articulo->Costo; ?></td>
						<td><?php echo $articulo->Utilidad; ?></td>
					</tbody>
				</table>
			</div><?php
					endforeach;
				endforeach;
			else :
				foreach ($catSuperior->Articulos as $articulo) : ?>
			<div class="row">
				<table class="span11 article">
					<thead>
						<th>Nombre<br/>Art&iacute;culo</th>
						<th>Ajuste</th>
						<th>Devoluci&oacute;n</th>
						<th>Vent</th>
						<th>Vent Pro</th>
						<th>Ventas Brutas (BRR)</th>
						<th>Comisi&oacute;n (BRR)</th>
						<th>Ventas Netas (BRR)</th>
						<th>Costo (BRR)</th>
						<th>Utilidad (BRR)</th>
					</thead>
					<tbody>
						<td><?php echo strtoupper($articulo->Nombre); ?></td>
						<td><?php echo $articulo->Ajustes; ?></td>
						<td><?php echo $articulo->Devoluciones; ?></td>
						<td><?php echo $articulo->Ventas; ?></td>
						<td><?php echo $articulo->VentasPromo; ?></td>
						<td><?php echo $articulo->VentasBrutas; ?></td>
						<td><?php echo $articulo->Comision; ?></td>
						<td><?php echo $articulo->VentasNetas; ?></td>
						<td><?php echo $articulo->Costo; ?></td>
						<td><?php echo $articulo->Utilidad; ?></td>
					</tbody>
				</table>
				</div><?php
				endforeach;
				endif;
		endforeach;
		?>
		</div>
	</div>
</div>
<?php endif; ?>