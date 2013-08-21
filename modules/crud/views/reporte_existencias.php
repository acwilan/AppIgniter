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
.article {margin-top:10px;}
.article thead th{border:1px solid #000;}
.article tbody td{border:1px solid #CCC;padding:0 3px;}
.article tbody td:first-child{border:none;text-align:right}
.warehouse {margin-top:20px;}
.warehouse caption{border:1px solid #000;text-align:center;padding:5px 0;font-weight:700;}
.warehouse thead th{border:1px solid #000;border-top:none;}
.warehouse tbody td{border:1px solid #CCC;padding:0 3px;}
.warehouse tbody td:first-child{border:none;text-align:right}
.warehouse tfoot td{border:1px solid #000;}
.warehouse tfoot td:first-child{border:none;}
</style>
<?php 
if ($report === FALSE) : ?>
<div class="row">
	<div class="span8 offset2">
		<?=form_open(current_url(), array('class'=>'form-horizontal','target'=>'_blank'))?>
			<?=form_fieldset(!empty($options->legend) ? $options->legend : ($options->operation == 'add' ? 'Agregar nuevo registro' : 'Editar registro'))?>
				<div class="control-group">
					<label class="control-label">
						<input type="radio" name="data[AgruparPor]" value="Articulo" <?=set_radio('data[AgruparPor]','Articulo',TRUE)?> />
						Agrupar por art&iacute;culo
					</label>
					<div class="controls">
						<select name="data[IdArticulo]">
							<option value="0" <?=set_select('data[IdArticulo]',0,TRUE)?>>-- TODOS --</option>
						<?php foreach ($articulos as $articulo) : ?>
							<option value="<?=$articulo->IdArticulo?>" <?=set_select('data[IdArticulo]',$articulo->IdArticulo)?>><?=$articulo->Nombre?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">
						<input type="radio" name="data[AgruparPor]" value="Bodega" <?=set_radio('data[AgruparPor]','Bodega',FALSE)?> />
						Agrupar por bodega
					</label>
					<div class="controls">
						<select name="data[IdBodega]">
							<option value="0" <?=set_select('data[IdBodega]',0,TRUE)?>>-- TODAS --</option>
						<?php foreach ($bodegas as $bodega) : ?>
							<option value="<?=$bodega->IdBodega?>" <?=set_select('data[IdBodega]',$bodega->IdBodega)?>><?=$bodega->Nombre?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Moneda del reporte:</label>
					<div class="controls">
						<label>
							<input type="radio" name="data[IdMoneda]" id="form_field_id_moneda" value="<?=$season->IdMoneda?>" />
							Moneda local (<?=$season->IdMoneda?>)
						</label>
						<label>
							<input type="radio" name="data[IdMoneda]" id="form_field_id_moneda" value="0" />
							D&oacute;lares
						</label>
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
		<h1>Reporte de Existencias</h1><hr/>
		<?php 
		if ($agruparPor == 'Articulo') : 
			foreach ($report as $idCategoria=>$catSuperior) : 
				if (!empty($idCategoria)) : ?>
		<h2><?php echo $catSuperior->Nombre; ?></h2>
		<div class="row">
			<div class="span11 offset1"><?php
					foreach ($catSuperior->Subcategorias as $catHija) : ?>
				<h3><?php echo $catHija->Nombre; ?></h3><?php
						foreach ($catHija->Articulos as $articulo) : ?>
				<div class="row">
					<table class="span8 article">
						<thead>
							<th colspan="2">Art&iacute;culo</th>
							<th>Costo (<?= $season->IdMoneda ?>)</th>
							<th>Precio (<?= $season->IdMoneda ?>)</th>
							<th>% Com</th>
							<th>Maneja Stock</th>
							<th>Estado</th>
						</thead>
						<tbody>
							<td><?php echo $articulo->Codigo; ?></td>
							<td><strong><?php echo strtoupper($articulo->Nombre); ?></strong></td>
							<td><?php echo $articulo->Costo; ?></td>
							<td><?php echo $articulo->Precio; ?></td>
							<td><?php echo $articulo->PctComision; ?></td>
							<td><?php echo $articulo->ManejaStock?'Si':'No'; ?></td>
							<td><?php echo $articulo->Estado; ?></td>
						</tbody>
					</table>
				</div>
				<div class="row">
					<table class="span8 offset3 warehouse">
						<caption>Detalle de existencia</caption>
						<thead>
							<th colspan="2">Bodega</th>
							<th>% Com Propio</th>
							<th>% Com</th>
							<th>Stock</th>
						</thead>
						<tbody><?php
							$total = 0;
							foreach ($articulo->Bodegas as $bodega) : ?>
							<tr>
								<td><?php echo $bodega->Codigo; ?></td>
								<td><?php echo $bodega->Nombre; ?></td>
								<td><?php echo $bodega->PctComisionPropio?'Si':'No'; ?></td>
								<td><?php echo $bodega->PctComision; ?></td>
								<td><?php echo $bodega->Stock; ?></td>
							</tr>
							<?php
								$total += $bodega->Stock;
							endforeach;?>
						</tbody>
						<tfoot>
							<td colspan="2">&nbsp;</td>
							<td colspan="2" align="center"><strong>Total</strong></td>
							<td align="right"><strong><?php echo $total; ?></strong></td>
						</tfoot>
					</table>
				</div><?php
						endforeach;
					endforeach;?>
			</div>
		</div><?php
				else : ?>
		<h2>Sin categoria</h2><?php
					foreach ($catSuperior->Articulos as $articulo) : ?>
		<div class="row">
			<table class="span8 article">
				<thead>
					<th colspan="2">Art&iacute;culo</th>
					<th>Costo (<?= $season->IdMoneda ?>)</th>
					<th>Precio (<?= $season->IdMoneda ?>)</th>
					<th>% Com</th>
					<th>Maneja Stock</th>
					<th>Estado</th>
				</thead>
				<tbody>
					<td><?php echo $articulo->Codigo; ?></td>
					<td><strong><?php echo strtoupper($articulo->Nombre); ?></strong></td>
					<td><?php echo $articulo->Costo; ?></td>
					<td><?php echo $articulo->Precio; ?></td>
					<td><?php echo $articulo->PctComision; ?></td>
					<td><?php echo $articulo->ManejaStock?'Si':'No'; ?></td>
					<td><?php echo $articulo->Estado; ?></td>
				</tbody>
			</table>
		</div>
		<div class="row">
			<table class="span8 offset3 warehouse">
				<caption>Detalle de existencia</caption>
				<thead>
					<th colspan="2">Bodega</th>
					<th>% Com Propio</th>
					<th>% Com</th>
					<th>Stock</th>
				</thead>
				<tbody><?php
						$total = 0;
						foreach ($articulo->Bodegas as $bodega) : ?>
					<tr>
						<td><?php echo $bodega->Codigo; ?></td>
						<td><?php echo $bodega->Nombre; ?></td>
						<td><?php echo $bodega->PctComisionPropio?'Si':'No'; ?></td>
						<td><?php echo $bodega->PctComision; ?></td>
						<td><?php echo $bodega->Stock; ?></td>
					</tr>
					<?php
							$total += $bodega->Stock;
						endforeach;?>
				</tbody>
				<tfoot>
					<td colspan="2">&nbsp;</td>
					<td colspan="2" align="center"><strong>Total</strong></td>
					<td align="right"><strong><?php echo $total; ?></strong></td>
				</tfoot>
			</table>
			</div><?php
					endforeach;
				endif; 
			endforeach;
		endif;
		?>
	</div>
</div>
<?php endif; ?>