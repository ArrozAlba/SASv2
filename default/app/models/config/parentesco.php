<?php
/**
 * @category
 * @package     Models
 * @subpackage
 * @author      Alexis Borges
 * @copyright   Copyright (c) 2014  
 */

class Parentesco extends ActiveRecord {

    /**
     * Método que se ejecuta antes de inicializar cualquier acción
     */
    public function initialize() {        
    }    
    /**
     * Método que devuelve las ciudades paginadas o para un select
     * @param int $pag Número de página a mostrar.
     * @return ActiveRecord
     */
    public function getListadoParentesco($order='order.descripcion.asc', $page=0) {        
        $order = $this->get_order($order, 'descripcion');
        if($page) {
            return $this->paginated("order: $order", "page: $page");
        } else {
            return $this->find("order: $order");
        }         
    }
       
}
