<?php

/*
Este controlador extiende del controlador base del sitio.
Se utiliza para generar catalogos CRUD a partir de configuracion.
*/
class Crud_Controller extends MY_Controller {
	
	// Tabla que se usa para CRUD de datos
	public $table_name = '';
	
	// Llave primaria de la tabla
	public $primary_key = 'id';
	
	// Se permite eliminar elementos
	public $allow_delete = FALSE;
	
	// Se permite agregar elementos
	public $allow_add = TRUE;
	
	// Se permite editar elementos
	public $allow_edit = TRUE;
	
	// Se permite realizar busquedas
	public $allow_search = TRUE;
	
	// Mostrar acciones en el formulario (guardar, cerrar, etc.)
	public $show_actions = TRUE;
	
	// Esta variable contiene informacion sobre el estado del catalogo.
	// Se obtiene y almacena desde la sesion, y se guarda el campo de orden,
	// la busqueda, la pagina actual, etc.
	public $catalog_state = NULL;
	
	// Etiqueta del boton de editar
	public $edit_label = 'Editar';
	
	// Mensaje a mostrar cuando no hay registros
	public $empty_message = 'No hay registros a mostrar.';
	
	// Vista que se utilizara para el grid
	public $grid_view = 'crud/grid';
	
	// Agregar botones
	public $custom_buttons = FALSE;
	
	public $item_id = NULL;
	
	public $form_view = 'crud/form';
	
	public $crud_options = array();
	
	public $autocomplete = array();
	
	private $default_sort = NULL;
	
	/* Constructor. Recibe como paramentros:
		- $module_name: Nombre del modulo
		- $table_name: Nombre de la tabla
		- $primary_key: Nombre de la llave primaria
	*/
	public function __construct($module_name,$table_name,$primary_key = 'id') {
		parent::__construct($module_name);
		
		$this->table_name = $table_name;
		$this->primary_key = $primary_key;
		$this->allow_add = $this->allow_edit = 1; //$this->user_info->es_admin == 1;
		
		// Se obtiene el estado del grid
		$this->_get_catalog_state();
		
		$this->load->helper('email');
		$this->load->helper('form');
		$this->load->helper('crud');
		$this->config->load('crud_main');
		$this->config->load('crud');
		$this->load->library('crud');
		$this->data_parts['row_status_field'] = FALSE;
		$this->load->library('form_validation');
		$this->form_validation->set_message('valid_date', 'Ingrese una fecha valida en formato dd.mm.yy'); 
		
		// Acciones por default en formulario
		$actions = config_item('form_actions');
		if (isset($actions[$this->controller_name]) && is_array($actions[$this->controller_name]))
			$this->data_parts['form_actions'] = $actions[$this->controller_name];
		else
			$this->data_parts['form_actions'] = $actions;
		if (empty($this->data_parts['form_actions']))
			$this->data_parts['form_actions'] = array(
				'save-close'=>(object)array(
						'label'=>'Guardar y cerrar',
						'link'=>FALSE,
						'primary'=>TRUE,
						'hidden'=>FALSE,
					),
				'save-only'=>(object)array(
						'label'=>'Guardar',
						'link'=>FALSE,
						'primary'=>FALSE,
						'hidden'=>FALSE,
					),
				'btn-cancel'=>(object)array(
						'label'=>'Cancelar',
						'link'=>TRUE,
						'primary'=>FALSE,
						'hidden'=>FALSE,
					),
			);
		
		$custom_actions = config_item('custom_actions');
		$this->data_parts['custom_actions'] = isset($custom_actions[$this->controller_name]) ? 
			$custom_actions[$this->controller_name] : FALSE;
		$this->data_parts['operation'] = '';
	}
	
	// Muestra un grid con los elementos
	public function index() {
		$this->_get_catalog_state(TRUE);
		if (!empty($this->default_sort)) {
			$this->catalog_state->sort = $this->default_sort;
		}
		
		$this->data_parts['selected'] = $this->module_name;
		$this->_show_header();
		
		$this->crud->data_grid($this->_get_grid_columns(), $this->_get_data(),
			array(
				'allow_add'=>$this->allow_add,
				'allow_edit'=>$this->allow_edit,
				'allow_delete'=>$this->allow_delete,
				'allow_search'=>$this->allow_search,
				'show_actions'=>$this->show_actions,
				'edit_label'=>$this->edit_label,
				'empty_message'=>$this->empty_message,
				'custom_buttons'=>$this->custom_buttons,
				'custom_actions'=>$this->data_parts['custom_actions'],
			),
			array(
				'module_name'=>$this->module_name,
				'table_name'=>$this->table_name,
				'primary_key'=>$this->primary_key,
				'catalog_state'=>$this->catalog_state,
				'user_info'=>$this->user_info,
				'current_period'=>$this->current_period,
			));
	
		$this->_show_footer();
	}
	
	// Esta funcion ajax permite realizar funciones sobre el grid, como ordenar,
	// buscar, cambiar paginacion. El parametro $op lleva la operacion a realizar.
	// Los demas parametros se envian por POST.
	public function grid_opp($op) {
		switch ($op) {
			// Ordenar
			case 'sort':
				$field = $this->input->post('field');
				if ($this->catalog_state->sort->enabled && $this->catalog_state->sort->field == $field) {
					switch ($this->catalog_state->sort->order) {
						case 'asc':
							$this->catalog_state->sort->order = 'desc';
							$this->catalog_state->sort->enabled = TRUE;
							break;
						case 'desc':
							$this->catalog_state->sort->order = NULL;
							$this->catalog_state->sort->enabled = FALSE;
							break;
						default:
							$this->catalog_state->sort->order = 'asc';
							$this->catalog_state->sort->enabled = TRUE;
							break;
					}
				}
				else {
					$this->catalog_state->sort->field = $field;
					$this->catalog_state->sort->order = 'asc';
					$this->catalog_state->sort->enabled = TRUE;
				}
				break;
			// Cambiar paginacion
			case 'pag':
				$page = $this->input->post('page');
				if ($page == 'prev' && $this->catalog_state->pagination->current_page > 1) {
					$this->catalog_state->pagination->current_page--;
				}
				elseif ($page == 'next' && $this->catalog_state->pagination->current_page < $this->catalog_state->pagination->total_pages) {
					$this->catalog_state->pagination->current_page++;
				}
				else {
					$this->catalog_state->pagination->current_page = $page;
				}
				break;
			// Busqueda
			case 'search':
				$term = $this->input->post('term');
				if (!empty($term)) {
					$this->catalog_state->search->enabled = TRUE;
					$this->catalog_state->search->term = $term;
					$this->catalog_state->pagination->current_page = 1;
				}
				else {
					$this->catalog_state->search->enabled = FALSE;
					$this->catalog_state->search->term = NULL;
				}
				break;
		}
		// Almacenar nuevo estado del catalogo
		$this->_set_catalog_state();
		/*$this->data_parts['column_data'] = $this->_get_grid_columns();
		$this->data_parts['table_data'] = $this->_get_data();
		// Cargar nuevamente el grid
		$this->load->view($this->grid_view, $this->data_parts);*/
		$this->crud->data_grid($this->_get_grid_columns(), $this->_get_data(),
			array(
				'allow_add'=>$this->allow_add,
				'allow_edit'=>$this->allow_edit,
				'allow_delete'=>$this->allow_delete,
				'allow_search'=>$this->allow_search,
				'show_actions'=>$this->show_actions,
				'edit_label'=>$this->edit_label,
				'empty_message'=>$this->empty_message,
				'custom_buttons'=>$this->custom_buttons,
				'custom_actions'=>$this->data_parts['custom_actions'],
			),
			array(
				'module_name'=>$this->module_name,
				'table_name'=>$this->table_name,
				'primary_key'=>$this->primary_key,
				'catalog_state'=>$this->catalog_state,
				'user_info'=>$this->user_info,
				'current_period'=>$this->current_period,
		));
	}
	
	// ADD y EDIT tienen la misma funcionalidad, llamando a una funcion base
	public function add() {
		$this->data_parts['operation'] = 'add';
		$this->_process_form();
	}
	
	public function edit($id) {
		$this->data_parts['operation'] = 'edit';
		$this->_process_form($id);
	}
	
	// Funcion base de procesamiento de datos, ya sea para a�adir o editar. En
	// caso del edit se envia como parametro el ID del registro a editar.
	protected function _process_form($id = FALSE) {
		$this->data_parts['selected'] = "{$this->module_name}/{$this->data_parts['operation']}";
		$this->item_id = $id;
		// Obtener acciones (guardar, guardar y nuevo, cerrar, etc.)
		$this->data_parts['form_actions'] = $this->_get_form_actions();
		
		// Obtiene informacion de los campos a mostrar
		$columns = $this->_get_form_columns();
		
		// Se obtienen los datos posteados
		$data = $this->input->post('data');
		if (!empty($data) && is_array($data)) {
			$rules = 0;
			// En este proceso se obtienen las reglas de validacion y se pasan
			// a la libreria de form_validation, para su posterior proceso
			foreach ($columns as $column) {
				if ($column->type == 'details') continue;
				
				if ($column->type == 'file') {
					$fpath = $this->_process_uploaded_file($column->name);
					$data[$column->name] = $fpath ? $fpath : NULL;
					// Si es edicion y no se envio un nuevo archivo, no actualizar el registro
					if (empty($data[$column->name]) && !empty($id)) {
						unset($data[$column->name]);
					}
				}
				
				if (($column->type == 'date' || $column->type == 'datetime') && isset($column->format) && !empty($data[$column->name])) {
					$date = date_create_from_format($column->format, $data[$column->name]);
					if ($date !== FALSE) {
						if ($column->type == 'date')
							$data[$column->name] = date_format($date, 'Y-m-d');
						elseif ($column->type == 'datetime')
							$data[$column->name] = date_format($date, 'Y-m-d H:i:s');
					}
				}
				elseif (empty($data[$column->name])) {
					$data[$column->name] = NULL;
				}
				
				if (isset($column->relation) && isset($column->relation->required) && !$column->relation->required && empty($data[$column->name])) {
					$data[$column->name] = NULL;
				}
				if (isset($column->required) && !$column->required && empty($data[$column->name])) {
					$data[$column->name] = NULL;
				}
				// si se esta actualizando un registro y tiene validacion de unique...
				if (isset($column->rules) && strpos($column->rules,'is_unique') !== FALSE && !empty($id)) {
					$column->rules = preg_replace('/is_unique\[([^\]]+)\]/i',"is_unique[$1.id.$id]",$column->rules);
				}
				/*if ($column->type == 'autocomplete' && isset($column->autocomplete) && isset($column->autocomplete->text_field) && isset($data[$column->autocomplete->text_field])) {
					if (empty($data[$column->name]) && !empty($data[$column->autocomplete->text_field])) {
						$this->CI->db->insert($column->autocomplete->table, array($column->autocomplete->display=>$data[$column->autocomplete->text_field]));
						$_POST['data'][$column->name] = $data[$column->name] = $this->CI->db->insert_id();
					}
					//unset($data[$column->autocomplete->text_field]);
				}*/
				if (isset($column->rules) && !empty($column->rules)) {
					// Este hack se hizo en dado caso que algunos campos se muestran o esconden
					// en el agregar o editar. Por ejemplo en usuarios, el campo de password
					// esta disponible al agregar pero no al editar.
					$f1 = 'disable_on_'.(empty($id)?'add':'edit'); $f2 = 'hide_on_'.(empty($id)?'add':'edit');
					if ((!isset($column->{$f1}) || !$column->{$f1}) && 
						(!isset($column->{$f2}) || !$column->{$f2})) {
						if ($column->type == 'city') {
							if (empty($data[$column->name.'[id]'])) {
								$this->form_validation->set_rules("data[{$column->name}][value]", $column->title, $column->city->rules);
							}
							else
								$this->form_validation->set_rules("data[{$column->name}][id]", $column->title, $column->rules);
						}
						else
							$this->form_validation->set_rules("data[{$column->name}]", $column->title, $column->rules);
						$rules++;
					}
				}
				if (($column->type == 'bool' || $column->type == 'checkbox') && !isset($data[$column->name]))
					$data[$column->name] = 0;
			}
			
			// Se validan los campos y en dado caso haya error, se setean los errores
			if ($rules > 0 && $this->form_validation->run() == FALSE) {
				foreach ($columns as $column) {
					if ($this->form_validation->error_exists("data[{$column->name}]"))
						$column->error = $this->form_validation->get_error("data[{$column->name}]");
				}
				$this->_add_error('error','Hubieron algunos errores de validacion');
			}
			// Si no hay error, se procede a actualizar la informacion
			else {
				$success = FALSE;
				$oper = NULL;
				// Edit
				if (!empty($id)) {
					$success = $this->_update($id, $data);
					$oper = 'edit';
				}
				// Insert
				else {
					$id = $this->_insert($data);
					$success = !empty($id);
					$oper = 'add';
				}
				if (!$success) {
					$this->_add_error('error','No se pudo agregar el registro');
				}
				else {
					$this->_add_error('success','El registro fue actualizado exitosamente');
					$action = $this->input->post('action');
					$this->_after_save($action, $id, $data, $oper);
				}
			}
		}
		
		$this->_show_header();
		
		$this->crud->form_view = $this->form_view;
		$this->crud_options['operation'] = $this->data_parts['operation'];
		$this->crud->data_form($columns, $this->_get_object_model($id, $data), $this->crud_options,
			array(
				'module_name'=>$this->module_name,
				'table_name'=>$this->table_name,
				'primary_key'=>$this->primary_key,
				'catalog_state'=>$this->catalog_state,
			),
			$this->data_parts);
		
		$this->_show_footer();
	}
	
	// Esta funcion ajax permite borrar elementos en bulk por medio de un parametro idList
	// enviado por POST. Devuelve una respuesta JSON de exito o error y el mensaje.
	public function delete_ajax() {
		$success = TRUE;
		$msg = NULL;
		if ($this->allow_delete) {
			$ids = $this->input->post('idList');
			if (!empty($this->table_name) && !empty($this->primary_key)) {
				try {
					$this->CI->db->where_in($this->primary_key, $ids)->delete($this->table_name);
				} catch (Exception $ex) {
					$success = FALSE;
					$msg = 'Unable to delete element(s). Please contact your administrator.';
				}
			}
			else {
				$this->_delete_data($ids);
			}
		}
		else {
			$success = FALSE;
			$msg = 'No se puede eliminar';
		}
		$this->output->set_content_type('application/json')
			->set_output(json_encode((object)array(
					'success'=>$success,
					'message'=>$msg,
				)));
	}
	
	// Esta funcion permite en el grid hacer "toggle" de campos estilo bool
	// como activo/inactivo, si/no, etc.
	public function toggle_ajax($field, $id) {
		$obj = $this->_get_object_model($id);
		$this->primary_key = array_pop(explode('.', $this->primary_key));
		if (empty($obj->{$this->primary_key}))
			show_404('users');
		else {
			$success = FALSE;
			if ($this->CI->db->update($this->table_name, array(
					$field=>$obj->{$field} == 1 ? 0 : 1,
				), array($this->primary_key => $id))) {
				$success = TRUE;
			}
			$this->output->set_content_type('application/json')
				->set_output(json_encode((object)array(
					'success'=>$success,
				)));
		}
	}
	
	// Funcion abstracta para eliminar datos
	protected function _delete_data($data) { }
	
	// Funcion abstracta para especificar si cierta fila puede ser eliminada
	public function allow_edit($row) { return TRUE; }
	
	// Funcion que agrega una columna al query para busqueda en el grid
	// La variable $arr contiene un listado de querys a unir en el formato
	// "[campo] LIKE '%[termino]%'"
	// Luego este listado se une por medio de un implode para generar una condicion tipo
	// "WHERE ... AND ([campo1] LIKE '%[termino]%' OR [campo2] LIKE '%[termino]%'...)
	private function _set_search_column($colObj,&$arr) {
		if (is_array($colObj))
			foreach ($colObj as $key=>$column)
				$this->_set_search_column($column,$index);
		elseif (is_object($colObj)) {
			$column = $colObj;
			if (isset($column->search) && is_object($column->search)) {
				$arr []= "{$column->search->field} LIKE '%".$this->CI->db->escape_like_str($this->catalog_state->search->term)."%'";
			}
			elseif (isset($column->search)) {
				$arr []= "{$column->name} LIKE '%".$this->CI->db->escape_like_str($this->catalog_state->search->term)."%'";
			}
		}
	}
	
	// Funcion que obtiene los datos a ser presentados en el grid
	// Lleva como parametro opcional un ID de un elemento en caso que se
	// quiera obtener solo uno, si no se especifica se obtiene todos los
	// elementos.
	// Devuelve un arreglo de objetos en caso $id sea NULL, y un objeto
	// en caso se especifique un identificador valido
	protected function _get_data($id = NULL) {
		$this->_set_command();
		
		if (!empty($id)) 
			$this->CI->db->where($this->primary_key, $id);
	
		// obtener todos los datos
		if ($id == NULL) {
			
			// hace el order by especificado en el estado del catalogo
			if ($this->catalog_state->sort->enabled && !empty($this->catalog_state->sort->field)) {
				$this->CI->db->order_by($this->catalog_state->sort->field, empty($this->catalog_state->sort->order) ? 'asc' : $this->catalog_state->sort->order, FALSE);
			}
			
			// incluye los querys para cuando se especifica un termino de busqueda
			if ($this->catalog_state->search->enabled) {
				$likes = array();
				foreach ($this->_get_grid_columns() as $column)
					$this->_set_search_column($column,$likes);
				if (count($likes) > 0) {
					$this->CI->db->where('('.implode(' OR ',$likes).')');
				}
			}
		
			// realiza la consulta de los datos
			$query = $this->CI->db->get();
			
			// con los datos obtenidos, hace la paginacion
			$pg = $this->catalog_state->pagination;
			$pg->total_records = $query->num_rows();
			if ($query->num_rows() > 0) {
				$records = $query->result();
				$offset = ($pg->current_page - 1) * $pg->rpp;
				$pg->total_pages = (int)($pg->total_records / $pg->rpp);
				if ($pg->total_records % $pg->rpp > 0)
					$pg->total_pages++;
				$this->catalog_state->pagination = $pg;
				$this->_set_catalog_state();
				return array_splice($records, $offset, $this->catalog_state->pagination->rpp);
			}
			else return array();
		}
		else {
			$query = $this->CI->db->get();
			return $query->num_rows() > 0 ? $query->row() : NULL;
		}
	}
	
	// Funcion base que tiene como objetivo establecer la consulta base,
	// con los campos a obtener, los joins y condiciones. Se puede hacer override
	// para obtener sets de datos complejos.
	protected function _set_command() {
		$this->CI->db->select('*')
			->from($this->table_name);
	}
	
	// Esta funcion devuelve un arreglo de objetos que especifican
	// las columnas a utilizar en el grid. Cada objeto lleva la siguiente info:
	// - name: El nombre de la columna en el query
	// - title: El encabezado que llevara en el header del grid
	// - type: El tipo de datos que manejara, dependiendo de esto se dibujara
	//			de distinta forma
	// - key: TRUE si el campo es la llave primaria del registro. En este caso
	//			se dibuja un checkbox si el atributo 'hidden' es FALSE
	// - hidden: TRUE si el campo no se desplegara en el grid
	protected function _get_grid_columns() {
		$columns = config_item('grid_columns');
		if (isset($columns[$this->controller_name]) && is_array($columns[$this->controller_name]))
			return $columns[$this->controller_name];
		elseif (!empty($columns) && is_array($columns) && is_object(@$columns[0])) {
			return $columns;
		}
		else {
			$cols = $this->CI->db->field_data($this->table_name);
			$data = array();
			foreach ($cols as $col) {
				$data []= (object)array(
						'name'=>$col->name,
						'title'=>ucfirst($col->name),
						'type'=>$col->type,
						'key'=>$col->primary_key == 1,
						'hidden'=>FALSE,
					);
			}
			return $data;
		}
	}

	// Esta funcion permite especificar un arreglo de objetos que ense�an
	// como pintar el formulario de ingreso/edicion. Cada objeto lleva los mismos
	// atributos que _get_grid_columns y tambien los siguientes:
	// - hide_on_edit/add: Permite esconder los campos en operaciones de edicion y/o adicion
	// - disable_on_edit/add: Permite deshabilitar los controles en operaciones especificas
	// - rules: Especifica reglas de validacion para el campo, las mismas que se usan
	//			en la libreria form_validation de code igniter
	// - default: Establecer un valor default para el formulario de adicion
	// - help: Mostrar un mensaje de ayuda junto al control
	protected function _get_form_columns() {
		$fields = config_item('form_fields');
		if (isset($fields[$this->controller_name]) && is_array($fields[$this->controller_name]))
			$fields = $fields[$this->controller_name];
		elseif (!empty($fields) && is_array($fields) && is_object(@$fields[0])) {
			$fields = $fields;
		}
		else {
			$fields = $this->_get_grid_columns();
		}
		if (is_array($fields)) {
			foreach ($fields as $field) {
				if ($field->type == 'money' && !isset($field->symbol) && isset($this->current_period->simbolo)) {
					$field->symbol = $this->current_period->simbolo;
				}
			}
		}
		return $fields;
	}
	
	// Esta funcion devuelve un objeto que contiene los atributos de la tabla.
	// Si se especifica el parametro $id, la informacion recabada sera del registro.
	// Si no se especifica, se obtendra un objeto vacio.
	protected function _get_object_model($id = NULL, $data = array()) {
		$obj = NULL;
		if ($id != NULL) {
			$obj = $this->_get_data($id);
		}
		if (empty($obj)) {
			$obj = new stdClass;
			$cols = $this->_get_form_columns();
			foreach ($cols as $col) {
				if (!is_array($col)) {
					$obj->{$col->name} = isset($col->default) && !empty($col->default) ? $col->default : NULL;
				}
			}
		}
		if (is_array($data) && !empty($data)) {
			foreach ($data as $k=>$v) {
				$obj->$k = $v;
			}
		}
		return $obj;
	}
	
	// Esta funcion obtiene el estado del catalogo de la sesion, y si no
	// existe lo inicializa. Usa el parametro $force para obligar a refrescar
	// el cache.
	protected function _get_catalog_state($force = FALSE) {
		$str = !$force ? $this->session->userdata($this->module_name.'_state') : NULL;
		if (empty($str)) {
			$this->catalog_state = (object)array(
					'sort'=>(object)array(
							'enabled'=>FALSE,
							'field'=>NULL,
							'order'=>NULL,
						),
					'pagination'=>(object)array(
							'rpp'=>20,
							'current_page'=>1,
							'total_pages'=>1,
							'total_records'=>1,
						),
					'search'=>(object)array(
							'enabled'=>FALSE,
							'term'=>NULL,
						),
				);
			$this->_set_catalog_state();
		}
		else $this->catalog_state = json_decode($str);
		if (!isset($this->catalog_state->sort) || !isset($this->catalog_state->pagination) || !isset($this->catalog_state->search))
			$this->_get_catalog_state(TRUE);
	}
	
	// Esta funcion almacena el estado actual del catalogo
	protected function _set_catalog_state() {
		$this->session->set_userdata($this->module_name.'_state',json_encode($this->catalog_state));
	}
	
	// Esta funcion actualiza los datos. Lleva dos parametros, el $id del registro,
	// y la $data con que se va a actualizar. $data debe ser un arreglo asociativo en
	// el formato [campo] => [nuevo_valor]
	protected function _update($id, $data) {
		return $this->CI->db->update($this->table_name, $data, array($this->primary_key=>$id));
	}
	
	// Esta funcion inserta un nuevo registro, con la informacion especificada en $data, la
	// cual debe llevar el formato [campo] => [valor]
	protected function _insert($data) {
		$this->CI->db->insert($this->table_name, $data);
		return $this->CI->db->insert_id();
	}
	
	// Esta funcion permite realizar acciones luego que se ingresa/actualiza un registro.
	// Lleva los siguientes parametros:
	// - $action: La accion seleccionada, dada en el arreglo $this->data_parts['form_actions']
	// - $id: El ID del registro agregado/actualizado
	// - $data: Los datos que se enviaron por POST
	// - $oper: Puede ser 'add' en caso de agregar, y 'edit' en caso de edicion
	protected function _after_save($action, $id, $data, $oper) {
		$return_url = $this->input->post('return_url');
		if (!empty($return_url)) {
			$this->_store_errors();
			header("Location: $return_url");
			return;
		}
		switch ($action) {
			case 'save-close':
				$this->_store_errors();
				redirect($this->module_name);
				break;
			case 'save-only':
				$this->_store_errors();
				redirect("{$this->module_name}/edit/$id");
				break;
			case 'save-new':
				$this->_store_errors();
				redirect("{$this->module_name}/add");
				break;
		}
	}
	
	// Esta funcion sobrecarga la de Base_Controler, agrega algunos archivos y funciones 
	// de CSS necesarias
	protected function _show_header($show = TRUE) {
		//$this->data_parts['css_files'] []= 'bootstrap-wysihtml5-0.0.2.css';
		parent::_show_header($show);
	}
	
	// Esta funcion sobrecarga la de Base_Controler, agrega algunos archivos y funciones 
	// de javascript necesarias
	protected function _show_footer($show = TRUE) {
		$this->data_parts['js_scripts'] []= "
				var autocompleteData = ".json_encode($this->autocomplete).",
					moduleName = '".str_replace('/','_',$this->module_name)."',
					moduleUrl = '".site_url($this->module_name)."',
					siteUrl = '".site_url()."',
					taheadResultFnc = function(item){};
			";
		$this->data_parts['js_files'] []= 'crud.js?v=3';
		$this->data_parts['js_files'] []= 'jquery.tihanycomplete.js';
		$this->data_parts['js_files'] []= 'jquery-ui-timepicker-addon.js';
		/*$this->data_parts['js_files'] []= 'tinymce/tiny_mce.js';
		$this->data_parts['js_files'] []= 'tinymce/jquery.tinymce.js';*/
		parent::_show_footer($show);
	}
	
	// Esta funcion devuelve un arreglo asociativo de objetos con las acciones a realizar
	// en el formulario de ingreso/edicion. Cada llave del arreglo es el nombre de la accion,
	// que se pasara como parametro en la funcion _after_save.
	protected function _get_form_actions() {
		$actions = config_item('form_actions');
		if (!empty($actions) && isset($actions[$this->controller_name]))
			return $actions[$this->controller_name];
		else
			return array(
				'save-close'=>(object)array(
						'label'=>'Guardar y cerrar',
						'link'=>FALSE,
						'primary'=>TRUE,
						'hidden'=>FALSE,
					),
				'save-only'=>(object)array(
						'label'=>'Guardar',
						'link'=>FALSE,
						'primary'=>FALSE,
						'hidden'=>FALSE,
					),
				'cancel'=>(object)array(
						'label'=>'Cancelar',
						'link'=>TRUE,
						'primary'=>FALSE,
						'hidden'=>FALSE,
					),
			);
	}
	
	function table_data() {
		$table = $this->input->post('table');
		$display = $this->input->post('display');
		$displayfnc = $this->input->post('displayfnc');
		
		$query = $this->db->select($this->primary_key.' AS id, '.(!empty($displayfnc) ? $displayfnc : $display).' AS value',FALSE)->get($table);
		
		$this->output->set_content_type('application/json')
			->set_output(json_encode($query->result()));
	}
	
	protected function _process_uploaded_file($name) {
		if (isset($_FILES['data']) && count($_FILES['data']) > 0) {
			$upload_path = realpath('./uploads').DIRECTORY_SEPARATOR;
			
			$fname = $_FILES['data']['name'][$name];
			$tmppath = $_FILES['data']['tmp_name'][$name];
			$size = $_FILES['data']['size'][$name];
			
			if (empty($fname) || empty($tmppath) || empty($size)) return FALSE;
			
			// si un archivo con ese mismo nombre existe, se crea un archivo [nombre]_(1).[ext], tal como es en windows.
			// si existiera un _(1), se crea un _(2) y asi.
			if (file_exists($upload_path.$fname)) {
				$finfo = pathinfo($upload_path.$fname);
				
				$i = 1;
				while (file_exists($upload_path.$finfo['filename']."_($i).".$finfo['extension']))
					$i++;
				$fname = "{$finfo['filename']}_($i).{$finfo['extension']}";
			}
			
			$finp = fopen($tmppath, 'r');
			$foutp = fopen($upload_path.$fname, 'w');
			
			$buffer = NULL;
			while (!feof($finp)) {
				$buffer = fread($finp, $size);
				fwrite($foutp, $buffer);
			}
			
			fclose($finp);
			fclose($foutp);
			
			return site_url("uploads/$fname");
		}
		return FALSE;
	}
	
	protected function set_default_sort($field, $order = 'asc') {
		$this->default_sort = (object)array(
			'enabled'=>TRUE,
			'field'=>$field,
			'order'=>$order,
		);
	}
	
}