<?php

class Fondo_fijo extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/fondo_fijo','fondo_fijo','IdFondoFijo');
		
		$this->allow_add = FALSE;
	}
	
}