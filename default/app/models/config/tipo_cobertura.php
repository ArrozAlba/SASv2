<?php
/**
 * @category
 * @package     Models
 * @subpackage
 * @author      Alexis Borges
 * @copyright   Copyright (c) 2014  
 */

class TipoCobertura extends ActiveRecord {
    /**
     * Método que se ejecuta antes de inicializar cualquier acción
     */
    public function initialize() {        
       // $this->has_many('sucursal');        
       // $this->validates_presence_of('parroquia', 'message: Ingresa el nombre de la parroquia');        
    }

    public function getListado() {
        return $this->find('order: descripcion ASC');
    }
 

    
}
