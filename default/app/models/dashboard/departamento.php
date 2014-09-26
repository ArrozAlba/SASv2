<?php

/**
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Javier León
 * @copyright   Copyright (c) 2014  
 */

class Departamento extends ActiveRecord {

    /**
     * Método que se ejecuta antes de inicializar cualquier acción
     */
    public function initialize() {        
       // $this->has_many('sucursal');        
       // $this->validates_presence_of('parroquia', 'message: Ingresa el nombre de la parroquia');        
    }

    /**
     * Método para setear
     * 
     * @param array $data
     * @return
     */
    public static function setDepartamento($name) {
        //Se aplica la autocarga
        $obj = new Departamento();        
        $obj->Departamento = ucfirst(Filter::get($name, 'string'));
        //Verifico si existe otra ciudad bajo el mismo nombre
        $old = new Departamento();
        if($old->find_first("parroquia LIKE '%$obj->parroquia%'")) {
            return $old;
        }        
        return $obj->create() ? $obj : FALSE;        
    }
    
    /**
     * Método que devuelve las ciudades paginadas o para un select
     * @param int $pag Número de página a mostrar.
     * @return ActiveRecord
     */
    public function getListadoDepartamento($order='order.nombre.asc', $page=0) {        
        $order = $this->get_order($order, 'nombre');
        if($page) {
            return $this->paginated("order: $order", "page: $page");
        } else {
            return $this->find("order: $order");
        }         
    }
    
    /**
     * Método para obtener las parroquias como json
     * @return type
     */
    public function getParroquiasToJson() {
        $rs =  $this->find("columns: parroquia", 'group: parroquia', 'order: parroquia ASC');
        $parroquias = array();
        foreach($rs as $parroquia) {            
            $parroquias[] = $parroquia->parroquia; 
        }
        return json_encode($parroquias);
    }
    
}
