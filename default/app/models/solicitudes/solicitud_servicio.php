<?php
/**
 * infoalex
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
        $this->validates_presence_of('fecha_solicitud', 'message: Ingresa la fecha de Solicitud');
        $this->validates_presence_of('fecha_vencimiento', 'message: Ingresa la fecha de Vencimiento de la Solicitud');
        $this->validates_presence_of('titular_id', 'message: Ingresa la Cedula del Titular');        
        $this->validates_presence_of('beneficiario_id', 'message: Ingresa la Cedula del Beneficiario');
        $this->validates_presence_of('proveedor_id', 'message: Ingresa el Estado de Origen de la empresa');
        $this->validates_presence_of('medico_id', 'message: Ingresa el Municipio de Origen de la empresa');
        $this->validates_presence_of('patologia_id', 'message: Ingresa la Parroquia de la empresa');
        $this->validates_presence_of('servicio_id', 'message: Ingresa el nombre del representante legal.');
        $this->validates_presence_of('observacion', 'message: Ingresa la pagina Web de la empresa');
    }  

    
    /**
     * Método para ver la información de una sucursal
     * @param int|string $id
     * @return Sucursal
     */
    public function getInformacionSolicitudServicio($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'a.id, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.patologia_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, b.celular, b.nombre1 as nombre,b.apellido1 as apellido, c.id as idtitular, d.id idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, f.id idpatologia, f.descripcion as patologia, g.id idtiposolicitud, g.nombre as tiposolicitud ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN persona as b ON (c.persona_id = b.id) ';        
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN patologia as f ON (a.patologia_id = f.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $condicion = "a.id = '$id'";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
    /**
     * Método para ver la información de un reporte
     * @param int|string $id
     * @return Sucursal
     */
    public function getReporteSolicitudServicio($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas= 'a.id, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.patologia_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, b.celular, b.nombre1, b.nombre2, b.apellido1,b.apellido2, b.nacionalidad, b.sexo, b.cedula, b.telefono, c.id as idtitular, d.id idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, f.id idpatologia, f.descripcion as patologia, g.id idtiposolicitud, g.nombre as tiposolicitud ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN persona as b ON (c.persona_id = b.id) ';        
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN patologia as f ON (a.patologia_id = f.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $condicion = "a.id = '$id'";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
        

    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoRegistroSolicitudServicio($order='order.descripcion.asc', $page='', $empresa=null) {
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.patologia_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, b.celular, b.nombre1 as nombre, b.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, f.id idpatologia, f.descripcion as patologia, g.id as idtiposolicitud, g.nombre as tiposolicitud ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN persona as b ON (c.persona_id = b.id) ';        
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN patologia as f ON (a.patologia_id = f.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $conditions = "a.estado_solicitud = 'R' ";
        $order = $this->get_order($order, 'a', array('solicitud_servicio'=>array('ASC'=>'solicitud_servicio.descripcion ASC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              'DESC'=>'solicitud_servicio.descripcion DESC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columnas", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columnas", "join: $join", "conditions: $conditions", "order: $order", "page: $page");            
        }
    }
    
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoAprobacionSolicitudServicio($order='order.descripcion.asc', $page='', $empresa=null) {
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.patologia_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, b.celular, b.nombre1 as nombre, b.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, f.id idpatologia, f.descripcion as patologia, g.id as idtiposolicitud, g.nombre as tiposolicitud ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN persona as b ON (c.persona_id = b.id) ';        
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN patologia as f ON (a.patologia_id = f.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $conditions = "a.estado_solicitud = 'R' or a.estado_solicitud = 'A' ";
        $order = $this->get_order($order, 'a', array('solicitud_servicio'=>array('ASC'=>'solicitud_servicio.descripcion ASC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              'DESC'=>'solicitud_servicio.descripcion DESC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columnas", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columnas", "join: $join", "conditions: $conditions", "order: $order", "page: $page");            
        }
    }
    

    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoContabilizarSolicitudServicio($order='order.descripcion.asc', $page='', $empresa=null) {
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.patologia_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, b.celular, b.nombre1 as nombre, b.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, f.id idpatologia, f.descripcion as patologia, g.id as idtiposolicitud, g.nombre as tiposolicitud ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN persona as b ON (c.persona_id = b.id) ';        
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN patologia as f ON (a.patologia_id = f.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $conditions = "a.estado_solicitud = 'A' or a.estado_solicitud = 'C' ";
        $order = $this->get_order($order, 'a', array('solicitud_servicio'=>array('ASC'=>'solicitud_servicio.descripcion ASC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              'DESC'=>'solicitud_servicio.descripcion DESC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columnas", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columnas", "join: $join", "conditions: $conditions", "order: $order", "page: $page");            
        }
    }
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoSolicitudServicio($order='order.descripcion.asc', $page='', $empresa=null) {
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.patologia_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, b.celular, b.nombre1 as nombre, b.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, f.id idpatologia, f.descripcion as patologia, g.id as idtiposolicitud, g.nombre as tiposolicitud ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN persona as b ON (c.persona_id = b.id) ';        
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN patologia as f ON (a.patologia_id = f.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $conditions = "";
        $order = $this->get_order($order, 'solicitud_servicio', array('solicitud_servicio'=>array('ASC'=>'solicitud_servicio.descripcion ASC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              'DESC'=>'solicitud_servicio.descripcion DESC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columnas", "join: $join", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columnas", "join: $join", "order: $order", "page: $page");            
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
