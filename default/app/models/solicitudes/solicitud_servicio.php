<?php
/**
 * infoalex
 *
 * @category
 * @package     Models SolicitudServicio
 * @subpackage
 * @author      alexis borges
 * @copyright    
 */
class SolicitudServicio extends ActiveRecord {
    
    /**
     * Constante para definir el id de la oficina principal
     */
    const OFICINA_PRINCIPAL = 1;

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->has_one('titular');
        $this->has_one('beneficiario');
        $this->has_one('servicio');
        $this->has_one('patologia');
        $this->has_one('proveedor');
        $this->has_one('proveedor_medico');
        //$this->has_one('titular');

    }  
    
    /**
     * Método para ver la información de una sucursal
     * @param int|string $id
     * @return Sucursal
     */
    public function getInformacionSolicitudServicio($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'solicitud_servicio.*, persona.nombre1 as nombre,persona.apellido1 as apellido, titular.id, proveedor.id, proveedor.nombre_corto as proveedor, servicio.id, servicio.descripcion as servicio, patologia.id, patologia.descripcion as patologia, tiposolicitud.id, tiposolicitud.nombre as tiposolicitud ';
        $join= 'INNER JOIN proveedor ON proveedor.id = solicitud_servicio.proveedor_id ';
        $join.= 'INNER JOIN servicio ON servicio.id = solicitud_servicio.servicio_id ';        
        $join.= 'INNER JOIN patologia ON patologia.id = solicitud_servicio.patologia_id ';
        $join.= 'INNER JOIN tiposolicitud ON tiposolicitud.id = solicitud_servicio.tiposolicitud_id ';
        $join.= 'INNER JOIN persona ON persona.id = solicitud_servicio.titular_id ';
        $join.= 'INNER JOIN titular ON titular.id = solicitud_servicio.titular_id ';
        $condicion = "solicitud_servicio.id = '$id'";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
    
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoRegistroSolicitudServicio($order='order.descripcion.asc', $page='', $empresa=null) {
        $columns = 'solicitud_servicio.*, persona.nombre1 as nombre,persona.apellido1 as apellido, titular.id, proveedor.id, proveedor.nombre_corto as proveedor, servicio.id, servicio.descripcion as servicio, patologia.id, patologia.descripcion as patologia, tiposolicitud.id, tiposolicitud.nombre as tiposolicitud ';
        $join= 'INNER JOIN proveedor ON proveedor.id = solicitud_servicio.proveedor_id ';
        $join.= 'INNER JOIN servicio ON servicio.id = solicitud_servicio.servicio_id ';        
        $join.= 'INNER JOIN patologia ON patologia.id = solicitud_servicio.patologia_id ';
        $join.= 'INNER JOIN tiposolicitud ON tiposolicitud.id = solicitud_servicio.tiposolicitud_id ';
        $join.= 'INNER JOIN persona ON persona.id = solicitud_servicio.titular_id ';
        $join.= 'INNER JOIN titular ON titular.id = solicitud_servicio.titular_id ';
        $join.= 'INNER JOIN beneficiario ON beneficiario.id = solicitud_servicio.beneficiario_id ';
        $conditions = "estado_solicitud = 'R' ";
        $order = $this->get_order($order, 'solicitud_servicio', array('solicitud_servicio'=>array('ASC'=>'solicitud_servicio.descripcion ASC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              'DESC'=>'solicitud_servicio.descripcion DESC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");            
        }
    }
    
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoAprobacionSolicitudServicio($order='order.descripcion.asc', $page='', $empresa=null) {
        $columns = 'solicitud_servicio.*, persona.nombre1 as nombre,persona.apellido1 as apellido, titular.id, proveedor.id, proveedor.nombre_corto as proveedor, servicio.id, servicio.descripcion as servicio, patologia.id, patologia.descripcion as patologia, tiposolicitud.id, tiposolicitud.nombre as tiposolicitud ';
        $join= 'INNER JOIN proveedor ON proveedor.id = solicitud_servicio.proveedor_id ';
        $join.= 'INNER JOIN servicio ON servicio.id = solicitud_servicio.servicio_id ';        
        $join.= 'INNER JOIN patologia ON patologia.id = solicitud_servicio.patologia_id ';
        $join.= 'INNER JOIN tiposolicitud ON tiposolicitud.id = solicitud_servicio.tiposolicitud_id ';
        $join.= 'INNER JOIN persona ON persona.id = solicitud_servicio.titular_id ';
        $join.= 'INNER JOIN titular ON titular.id = solicitud_servicio.titular_id ';
        //$join.= 'INNER JOIN beneficiario ON beneficiario.id = solicitud_servicio.beneficiario_id ';
        $conditions = "estado_solicitud = 'R' ";
        $order = $this->get_order($order, 'solicitud_servicio', array('solicitud_servicio'=>array('ASC'=>'solicitud_servicio.descripcion ASC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              'DESC'=>'solicitud_servicio.descripcion DESC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");            
        }
    }
    

    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoContabilizarSolicitudServicio($order='order.descripcion.asc', $page='', $empresa=null) {
        $columns = 'solicitud_servicio.*, persona.nombre1 as nombre,persona.apellido1 as apellido, titular.id, proveedor.id, proveedor.nombre_corto as proveedor, servicio.id, servicio.descripcion as servicio, patologia.id, patologia.descripcion as patologia, tiposolicitud.id, tiposolicitud.nombre as tiposolicitud ';
        $join= 'INNER JOIN proveedor ON proveedor.id = solicitud_servicio.proveedor_id ';
        $join.= 'INNER JOIN servicio ON servicio.id = solicitud_servicio.servicio_id ';        
        $join.= 'INNER JOIN patologia ON patologia.id = solicitud_servicio.patologia_id ';
        $join.= 'INNER JOIN tiposolicitud ON tiposolicitud.id = solicitud_servicio.tiposolicitud_id ';
        $join.= 'INNER JOIN persona ON persona.id = solicitud_servicio.titular_id ';
        $join.= 'INNER JOIN titular ON titular.id = solicitud_servicio.titular_id ';
        //$join.= 'INNER JOIN beneficiario ON beneficiario.id = solicitud_servicio.beneficiario_id ';
        $conditions = "estado_solicitud = 'A' ";
        $order = $this->get_order($order, 'solicitud_servicio', array('solicitud_servicio'=>array('ASC'=>'solicitud_servicio.descripcion ASC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              'DESC'=>'solicitud_servicio.descripcion DESC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");            
        }
    }
    


    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoSolicitudServicio($order='order.descripcion.asc', $page='', $empresa=null) {
        $columns = 'solicitud_servicio.*, persona.nombre1 as nombre,persona.apellido1 as apellido, titular.id, proveedor.id, proveedor.nombre_corto as proveedor, servicio.id, servicio.descripcion as servicio, patologia.id, patologia.descripcion as patologia, tiposolicitud.id, tiposolicitud.nombre as tiposolicitud ';
        $join= 'INNER JOIN proveedor ON proveedor.id = solicitud_servicio.proveedor_id ';
        $join.= 'INNER JOIN servicio ON servicio.id = solicitud_servicio.servicio_id ';        
        $join.= 'INNER JOIN patologia ON patologia.id = solicitud_servicio.patologia_id ';
        $join.= 'INNER JOIN tiposolicitud ON tiposolicitud.id = solicitud_servicio.tiposolicitud_id ';
        $join.= 'INNER JOIN persona ON persona.id = solicitud_servicio.titular_id ';
        $join.= 'INNER JOIN titular ON titular.id = solicitud_servicio.titular_id ';
        //$join.= 'INNER JOIN beneficiario ON beneficiario.id = solicitud_servicio.beneficiario_id ';
        $conditions = "";
        $order = $this->get_order($order, 'solicitud_servicio', array('solicitud_servicio'=>array('ASC'=>'solicitud_servicio.descripcion ASC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              'DESC'=>'solicitud_servicio.descripcion DESC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columns", "join: $join", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columns", "join: $join", "order: $order", "page: $page");            
        }
    }
    

    /**
     * Método para setear
     * @param string $method Método a ejecutar (create, update, save)
     * @param array $data Array con la data => Input::post('model')
     * @param array $otherData Array con datos adicionales
     * @return Obj
     */
    public static function setSolicitudServicio($method, $data, $optData=null) {
        //Se aplica la autocarga
        $obj = new SolicitudServicio($data);
        //Se verifica si contiene una data adicional para autocargar
        if ($optData) {
            $obj->dump_result_self($optData);
        }   
        
        /*if($method!='delete') {
            $obj->ciudad_id = Ciudad::setCiudad($obj->ciudad)->id;        
        }*/
        $rs = $obj->$method();
        
        return ($rs) ? $obj : FALSE;
    }

    /**
     * Método que se ejecuta antes de guardar y/o modificar     
     */
    public function before_save() {        
        /* 
        $this->slug = DwUtils::getSlug($this->sucursal); 
        $this->direccion = Filter::get($this->direccion, 'string');
        $this->telefono = Filter::get($this->telefono, 'numeric');
        $this->celular = Filter::get($this->celular, 'numeric');
        $this->fax = Filter::get($this->fax, 'numeric');        
        
        $conditions = "sucursal = '$this->sucursal' AND ciudad_id = $this->ciudad_id AND empresa_id = $this->empresa_id";
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($this->count("conditions: $conditions")) {
            DwMessage::error('Lo sentimos, pero ya existe una sucursal registrada con el mismo nombre y ciudad.');
            return 'cancel';
        }
   */     
    }
    
    /**
     * Callback que se ejecuta antes de eliminar
     */
    public function before_delete() {
        /*if($this->id == 1) { //Para no eliminar la información de sucursal
            DwMessage::warning('Lo sentimos, pero esta sucursal no se puede eliminar.');
            return 'cancel';
        }*/
    }
    
}
