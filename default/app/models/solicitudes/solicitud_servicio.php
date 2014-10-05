<?php
/**
 * infoalex
 * @category
 * @package     Models SolicitudServicio
 * @subpackage
 * @author      Alexis Borges
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
    }  
    /**
     * Método para ver 
     * @param int|string $id
     * @return 
     */
    public function getInformacionSolicitudServicio($id, $isSlug=false) {
        $id = ($isSlug) ? Filter::get($id, 'string') : Filter::get($id, 'numeric');
        $columnas = 'a.id, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.motivo_rechazo, a.observacion,b.id idmedico, b.nombre1 as nombrem, b.apellido1 as apellidom, c.cedula, c.celular, c.nombre1 as nombre, c.apellido1 as apellido, c.id as idtitular, d.id idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, f.nombre1 as nombreb, f.apellido1 as apellidob, f.id as idbeneficiario, g.id idtiposolicitud, g.nombre as tiposolicitud ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN medico as b ON (a.medico_id = b.id) ';
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN beneficiario as f ON (a.beneficiario_id = f.id) ';
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
        $columnas= 'a.id, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, c.celular, c.nombre1, c.nombre2, c.apellido1, c.apellido2, c.nacionalidad, c.sexo, c.cedula,  c.telefono, c.id as idtitular, d.id idproveedor, d.razon_social as proveedor, d.direccion as direccionp, e.id as idservicio, e.descripcion as servicio, g.id idtiposolicitud, g.nombre as tiposolicitud, h.nombre1 as nombrem1, h.nombre2 as nombrem2, h.apellido1 as apellidom1, h.apellido2 as apellidom2';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $join.= 'INNER JOIN medico as h ON (a.medico_id = h.id) ';
        $condicion = "a.id = '$id' ";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
    /**
     * Método que devuelve las sucursales
     * @param string $order
     * @param int $page 
     * @return ActiveRecord
     */
    public function getListadoRegistroSolicitudServicio($order='order.descripcion.asc', $page='',$tps, $empresa=null) {
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, c.celular, c.nombre1 as nombre, c.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, g.id as idtiposolicitud, g.nombre as tiposolicitud, h.nombre1 as nombreb, h.apellido1 as apellidob, h.id as idbeneficiario ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $join.= 'INNER JOIN beneficiario as h ON (a.beneficiario_id = h.id) ';
        $conditions = "g.id = '$tps' and a.estado_solicitud = 'R' or a.estado_solicitud= 'E' ";
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
    public function getListadoRegistroSolicitudServicioEscritorio($order='order.descripcion.asc', $page='', $empresa=null) {
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, c.celular, c.nombre1 as nombre, c.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, g.id as idtiposolicitud, g.nombre as tiposolicitud, h.nombre1 as nombreb, h.apellido1 as apellidob, h.id as idbeneficiario  ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $join.= 'INNER JOIN beneficiario as h ON (a.beneficiario_id = h.id) ';
        $conditions = "a.estado_solicitud = 'R' or a.estado_solicitud= 'E' ";
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
    public function getListadoAprobacionSolicitudServicio($order='order.descripcion.asc', $page='',$tps,$empresa=null) {
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, c.celular, c.nombre1 as nombre, c.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, g.id as idtiposolicitud, g.nombre as tiposolicitud, h.nombre1 as nombreb, h.apellido1 as apellidob, h.id as idbeneficiario ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $join.= 'INNER JOIN beneficiario as h ON (a.beneficiario_id = h.id) ';
        $conditions = "g.id = '$tps' and (a.estado_solicitud = 'R' or a.estado_solicitud = 'A') ";
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
    public function getListadoContabilizarSolicitudServicio($order='order.descripcion.asc', $page='',$tps,$empresa=null) {
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, c.celular, c.nombre1 as nombre, c.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, g.id as idtiposolicitud, g.nombre as tiposolicitud, h.nombre1 as nombreb, h.apellido1 as apellidob, h.id as idbeneficiario ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $join.= 'INNER JOIN beneficiario as h ON (a.beneficiario_id = h.id) ';
        $conditions = "g.id = '$tps' and a.estado_solicitud = 'A' or a.estado_solicitud = 'C' ";
        $order = $this->get_order($order, 'a', array('solicitud_servicio'=>array('ASC'=>'solicitud_servicio.descripcion ASC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              'DESC'=>'solicitud_servicio.descripcion DESC, solicitud_servicio.tipo_solicitud_servicio ASC',
                                                                              )));
        if($page) {                
            return $this->paginated("columns: $columnas", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } else {
            return $this->find("columns: $columnas", "join: $join", "conditions: $conditions", "order: $order", "page: $page");            
        }
    }
    /*
    Metodo para listar las Solicitudes con los siniestros ya cargados 
    */
    public function getListadoSiniestrosSolicitudServicio($order='order.descripcion.asc', $page='',$tps,$empresa=null) {
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, c.celular, c.nombre1 as nombre, c.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, g.id as idtiposolicitud, g.nombre as tiposolicitud,  h.nombre1 as nombreb, h.apellido1 as apellidob, h.id as idbeneficiario ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
        $join.= 'INNER JOIN proveedor as d ON (a.proveedor_id = d.id) ';
        $join.= 'INNER JOIN servicio as e ON (a.servicio_id = e.id) ';
        $join.= 'INNER JOIN tiposolicitud as g ON (a.tiposolicitud_id = g.id) ';
        $join.= 'INNER JOIN beneficiario as h ON (a.beneficiario_id = h.id) ';
        $conditions = "g.id = '$tps' and  (a.estado_solicitud = 'S' or a.estado_solicitud = 'G') ";
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
        $columnas = 'a.id as idsolicitudservicio, a.estado_solicitud, a.tiposolicitud_id, a.fecha_solicitud, a.codigo_solicitud, a.titular_id, a.beneficiario_id, a.patologia_id, a.proveedor_id, a.medico_id, a.servicio_id, a.fecha_vencimiento, a.observacion, c.celular, c.nombre1 as nombre, c.apellido1 as apellido, c.id as idtitular, d.id as idproveedor, d.nombre_corto as proveedor, e.id as idservicio, e.descripcion as servicio, f.id idpatologia, f.descripcion as patologia, g.id as idtiposolicitud, g.nombre as tiposolicitud ';
        $join= 'as a INNER JOIN titular as c ON (a.titular_id = c.id) ';
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
        $rs = $obj->$method();
        return ($rs) ? $obj : FALSE;
    }

    /**
     * Método que se ejecuta antes de guardar y/o modificar     
     */
    public function before_save() {        
    }
    
    /**
     * Callback que se ejecuta antes de eliminar
     */
    public function before_delete() {
      
    }
    //MIE3NTARAS
     public  function getInformacionSolicitudServicioPatologia($id, $order='solicitud_servicio_patologia.id') {
        $id = Filter::get($id, 'numeric');
        $columnas = 'solicitud_servicio_patologia.* , P.* , P.id as idpatologia ';
        $join= 'INNER JOIN solicitud_servicio_patologia ON (solicitud_servicio_patologia.solicitud_servicio_id = solicitud_servicio.id) ';
        $join.= 'INNER JOIN patologia as P ON (P.id = solicitud_servicio_patologia.patologia_id) ';
        
        $condicion = "solicitud_servicio_patologia.solicitud_servicio_id = '$id'"; 

        // return $this->find("columns: $columnas", "join: $join", "conditions: $condicion", "order: $order");
        return $this->find("columns: $columnas", "conditions: $condicion", "join: $join", "order: $order");
    } 
    
}
