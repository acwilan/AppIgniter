				<table class="table"><?php
					foreach ($obj->permisos as $mod) : ?>
					<tr>
						<td colspan="2">
							<label class="checkbox"><?php echo $mod->nombre; ?></label>
						</td>
						<td>
							<input type="checkbox" name="data[permissions][<?php echo $mod->id_modulo; ?>]" <?php echo $mod->asignado==1 ? 'checked="checked"' : ''; ?> />
						</td>
					</tr><?php
						if (isset($mod->children) && is_array($mod->children)) :
							foreach ($mod->children as $submod) : ?>
					<tr>
						<td>&nbsp;</td>
						<td>
							<label class="checkbox"><?php echo $submod->nombre; ?></label>
						</td>
						<td>
							<input type="checkbox" name="data[permissions][<?php echo $submod->id_modulo; ?>]" <?php echo $submod->asignado==1 ? 'checked="checked"' : ''; ?> />
						</td>
					</tr><?php
							endforeach;
						endif;
					endforeach; ?>
				</table>