<?php
// El campo 'displayfnc' permite desplegar distintos tipos de datos a un solo campo
// Por ejemplo, si se quiere usar una funcion CONCAT para concatenar valores
// El campo 'display' solo permite especificar columnas de tabla
$title = $field->title;
function build_options($parent_id, $db, $field, $selected) {
	global $title;
	$db->select($field->relation->field)->from($field->relation->table);
	if (!isset($field->relation->displayfnc) || empty($field->relation->displayfnc))
		$field->relation->displayfnc = $field->relation->display;
	if (isset($field->relation->filter) && !empty($field->relation->filter))
		$db->where($field->relation->filter, NULL, FALSE);
	if (isset($field->relation->order) && !empty($field->relation->order))
		$db->order_by($field->relation->order);
	else
		$db->order_by($field->relation->display);
	$db->select("{$field->relation->displayfnc} AS {$field->relation->display}",FALSE);
	if (isset($field->relation->type_field))
		$db->select("{$field->relation->type_field} AS type");
	if (isset($field->relation->limit))
		$db->limit($field->relation->limit);
	$db->where($field->relation->parent_field, $parent_id);
	$rows = $db->get()->result();
	foreach ($rows as &$row) {
		$row->children = build_options($row->{$field->relation->field}, $db, $field, $selected);
		if ($row->{$field->relation->field} == $selected) {
			$title = $row->{$field->relation->display};
		}
	}
	return $rows;
}

function build_list($options, $field, $fldArr) {
	foreach ($options as $option) { ?>
		<li>
			<a href="#" data-id="<?= $option->{$field->relation->field} ?>" onclick="setNestedDropdown(this,'<?= $fldArr['id'] ?>')"><?= $option->{$field->relation->display} ?></a><?php
		if (isset($option->children) && !empty($option->children)) { ?>
			<ul><?php build_list($option->children, $field, $fldArr); ?></ul><?php
		} ?>
		</li><?php
	}
}

$options = build_options(NULL, $this->db, $field, $obj->{$field->name});

$rows = $this->db->get($field->relation->table)->result(); 
?>
<div class="btn-group">
	<input type="hidden" id="<?= $fldArr['id'] ?>" name="<?= $fldArr['name'] ?>" value="<?= $obj->{$field->name} ?>" />
	<button class="btn dropdown-toggle" data-toggle="dropdown"><span id="<?= $fldArr['id'] ?>_title"><?= $title ?></span> <span class="caret"></span></button>
	<ul class="dropdown-menu">
		<?php if (isset($field->relation->required) && !$field->relation->required) : ?>
		<li class="nav-header">
			<a href="#" data-id="0" onclick="setNestedDropdown(this,'<?= $fldArr['id'] ?>')"><?= isset($field->relation->nulltext) ? $field->relation->nulltext : $field->title ?></a>
		</li>
		<?php endif; ?>
		<?php build_list($options, $field, $fldArr); ?>
	</ul>
</div>