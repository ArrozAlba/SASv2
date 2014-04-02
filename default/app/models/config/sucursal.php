<?php
/**
 * Dailyscript - Web | App | Media
 * @category
 * @package     Models
 * @subpackage
 * @author      Iván D. Meléndez (ivan.melendez@dailyscript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

Load::models('params/pais');
Load::models('params/estado');
Load::models('params/municipio');
Load::models('params/parroquia');
class Sucursal extends ActiveRecord {
    
    /**
     * Constante para definir el id de la oficina principal
     */
    const OFICINA_PRINCIPAL = 1;

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->belongs_to('empresa');
        $this->belongs_to('pais');
        $this->belongs_to('estado');
        $this->belongs_to('municipio');        
        $this->belongs_to('parroquia');
        $this->has_many('usuario');

        $this->validates_presence_of('sucursal', 'message: Ingresa el nombre de la sucursal');        
        $this->validates_presence_of('direccion', 'message: Ingresa la dirección de la sucursal.');
		$this->validates_presence_of('pais_id', 'message: Indica el pais de ubicación de la sucursal.');
        $this->validates_presence_of('estado_id', 'message: Indica el estado de ubicación de la sucursal.');
		$this->validates_presence_of('municipio_id', 'message: Indica el municipio de ubicación de la sucursal.');
        $this->validates_presence_of('parroquia_id', 'message: Indica la parroquia de ubicación de la sucursal.');
                
    }  
    
    /**
     * Método para ver la información de una sucursal
     * @param int|string $id
     * @return Sucursal
     */
    public function getInformacionSucursal($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'sucursal.*, empresa.razon_social, empresa.rif, empresa.representante_legal, parroquia.nombre';
        $join = 'INNER JOIN empresa ON empresa.id = sucursal.empresa_id INNER JOIN parroquia ON parroquia.id = sucursal.parroquia_id';
        $condicion = ($isSlug) ? "sucursal.slug = '$id'" : "sucursal.id = '$id'";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
    
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoSucursal($order='order.sucursal.asc', $page='', $empresa=null) {
        $empresa = Filter::get($empresa, 'int');
        
        $columns = 'sucursal.*, empresa.rif, parroquia.nombre';
        $join = 'INNER JOIN empresa ON empresa.id = sucursal.empresa_id INNER JOIN parroquia ON parroquia.id = sucursal.parroquia_id';        
        $conditions = (empty($empresa)) ? 'sucursal.id > 0' : "empresa.id = '$empresa'";
        
        $order = $this->get_order($order, 'sucursal', array('sucursal'=>array('ASC'=>'sucursal.sucursal ASC, parroquia.nombre ASC, empresa.rif ASC',
                                                                              'DESC'=>'sucursal.sucursal DESC, ciudad.ciudad ASC, empresa.siglas ASC'),
                                                            'parroquia'=>array('ASC'=>'parroquia.nombre ASC, sucursal.direccion ASC, sucursal.sucursal ASC, empresa.rif ASC',
                                                                              'DESC'=>'parroquia.nombre DESC, sucursal.direccion ASC, sucursal.sucursal ASC, empresa.rif ASC'),
                                                            'telefono',
                                                            'fax',
                                                            'direccion'));
        if($page) {                
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");            
        }
    }
    
    /**
     * Método para setear
     * @param string $method Método a ejecutar (create, update, save)
     * @param array $data Array con la data => Input::post('model')
     * @param array $otherData Array con datos adicionales
     * @return Obj
     */
    public static function setSucursal($method, $data, $optData=null) {
        //Se aplica la autocarga
        $obj = new Sucursal($data);
        //Se verifica si contiene una data adicional para autocargar
        if ($optData) {
            $obj->dump_result_self($optData);
        }   
        if($method!='delete') {
            $obj->parroquia_id = Parroquia::setParroquia($obj->parroquia_id)->id;        
        }
        $rs = $obj->$method();
        
        return ($rs) ? $obj : FALSE;
    }
    /**
     * Método que se ejecuta antes de guardar y/o modificar     
     */
    public function before_save() {        
        $this->sucursal = Filter::get($this->sucursal, 'string');        
        //$this->sucursal_slug = DwUtils::getSlug($this->sucursal); 
        $this->pais_id = Filter::get($this->pais_id, 'string');
        $this->estado_id = Filter::get($this->estado_id, 'string');
        $this->municipio_id = Filter::get($this->municipio_id, 'string');
        $this->parroquia_id = Filter::get($this->parroquia_id, 'string');
        $this->direccion = Filter::get($this->direccion, 'string');
        $this->telefono = Filter::get($this->telefono, 'numeric');
        $this->celular = Filter::get($this->celular, 'numeric');
        $this->fax = Filter::get($this->fax, 'numeric');        
        
        $conditions = "sucursal = '$this->sucursal' AND parroquia_id = $this->parroquia_id AND empresa_id = $this->empresa_id";
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($this->count("conditions: $conditions")) {
            DwMessage::error('Lo sentimos, pero ya existe una sucursal registrada con el mismo nombre y parroquia.');
            return 'cancel';
        }   
    }   
    /**
     * Callback que se ejecuta antes de eliminar
     */
    public function before_delete() {
        if($this->id == 1) { //Para no eliminar la información de sucursal
            DwMessage::warning('Lo sentimos, pero esta sucursal no se puede eliminar.');
            return 'cancel';
        }
    }
}
