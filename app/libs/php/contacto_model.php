<?php
require_once "model.php";

class Contacto_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    
}
?>