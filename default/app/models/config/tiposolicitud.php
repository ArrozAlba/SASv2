<?php
/**
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Javier León
 * @copyright   Copyright (c) 2014  
 */

class Tiposolicitud extends ActiveRecord {

    /**
     * Método que se ejecuta antes de inicializar cualquier acción
     */
    public function initialize() {        
       // $this->has_many('sucursal');        
       // $this->validates_presence_of('parroquia', 'message: Ingresa el nombre de la parroquia');        
    }
    /**
     * Método para obtener codigo_solicitud
     * @return obj
     */
    public function getCorrelativo($a) {
        $numero_registros = $this->find("tiposolicitud_id = 1");
        $siglas = 'SASCO-00000';
        $numero_registros = $numero_registros+1;
        $a= array('codid'=>$siglas,'codvalue'=>$numero_registros);;
        return json_encode($a);
        }    
    /**
     * Método para setear
     * 
     * @param array $data
     * @return
     */
    public static function setTiposolicitud($name) {
        //Se aplica la autocarga
        $obj = new Tiposolicitud();        
        $obj->Tiposolicitud = ucfirst(Filter::get($name, 'string'));
        //Verifico si existe otra ciudad bajo el mismo nombre
        $old = new Tiposolicitud();
        if($old->find_first("nombre LIKE '%$obj->nombre%'")) {
            return $old;
        }        
        return $obj->create() ? $obj : FALSE;        
    }
    
    /**
     * Método que devuelve las ciudades paginadas o para un select
     * @param int $pag Número de página a mostrar.
     * @return ActiveRecord
     */
    public function getListadoTiposolicitud($order='order.nombre.asc', $page=0) {        
        $order = $this->get_order($order, 'nombre');
        if($page) {
            return $this->paginated("order: $order", "page: $page");
        } else {
            return $this->find("order: $order");
        }         
    }
       /**
     * Método para ver la información de una sucursal
     * @param int|string $id
     * @return Sucursal
     */
    public function getInformacionTiposolicitud($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'tiposolicitud.*  ';
        $condicion = "tiposolicitud.id = '$id'";
        return $this->find_first("columns: $columnas", "conditions: $condicion");
    } 
 

    
}
