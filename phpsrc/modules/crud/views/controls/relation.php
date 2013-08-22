<?php
					// El campo 'displayfnc' permite desplegar distintos tipos de datos a un solo campo
					// Por ejemplo, si se quiere usar una funcion CONCAT para concatenar valores
					// El campo 'display' solo permite especificar columnas de tabla
					if (isset($field->relation->query) && !empty($field->relation->query)) {
						$rows = $this->db->query($field->relation->query)->result();
					}
					else {
						$this->db->select($field->relation->field);
						if (!isset($field->relation->displayfnc) || empty($field->relation->displayfnc))
							$field->relation->displayfnc = $field->relation->display;
						if (isset($field->relation->filter) && !empty($field->relation->filter))
							$this->db->where($field->relation->filter, NULL, FALSE);
						if (isset($field->relation->order) && !empty($field->relation->order))
							$this->db->order_by($field->relation->order);
						else
							$this->db->order_by($field->relation->display);
						$this->db->select("{$field->relation->displayfnc} AS {$field->relation->display}",FALSE);
						if (isset($field->relation->type_field))
							$this->db->select("{$field->relation->type_field} AS type");
						if (isset($field->relation->limit))
							$this->db->limit($field->relation->limit);
						$rows = $this->db->get($field->relation->table)->result();
					} 
					if (empty($obj->{$field->name}) && isset($field->relation->default)) {
						$obj->{$field->name} = $field->relation->default;
					}
					//unset($fldArr['value']);
					unset($fldArr['title']);
					?>
	<select name="<?php echo $fldArr['name'] ?>" id="<?php echo $fldArr['id']; ?>"<?php echo $options->readonly ? ' disabled="disabled"' : ''; ?><?= isset($field->role) ? ' role="'.$field->role.'"' : '' ?>><?php
					if (isset($field->relation->required) && !$field->relation->required) : ?>
		<option value="0" data-type="0"><?php if (isset($field->relation->nulltext)) : echo $field->relation->nulltext; else : ?>-- Seleccione uno --<?php endif; ?></option><?php
					endif;
					foreach ($rows as $d) : ?>
		<option value="<?= $d->{$field->relation->field} ?>" <?= set_select($fldArr['name'], $d->{$field->relation->field}, $d->{$field->relation->field} == $obj->{$field->name}) ?><?= isset($d->type) ? ' data-type="'.$d->type.'"' : '' ?>><?php echo $d->{$field->relation->display}; ?></option><?php
					endforeach; ?>
	</select><?php
					// Esta opcion se añadio por si acaso se quiere desplegar un link para agregar
					// mas valores al dropdown
					if (isset($field->add_link) && !empty($field->add_link)) : ?>
	 <a class="btn btn-small" href="<?php echo $field->add_link; ?>?return_url=<?php echo urlencode(current_url().(!empty($_SERVER['QUERY_STRING'])?"?{$_SERVER['QUERY_STRING']}":'')); ?>"><i class="icon-plus"></i> Agregar</a><?php
					endif;
